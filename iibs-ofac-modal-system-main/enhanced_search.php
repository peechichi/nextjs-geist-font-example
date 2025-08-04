<?php
require_once 'functions.php';

// Enhanced search function specifically for corporation names
function enhancedCorporationSearch($query, $category = 'all') {
    global $pdo;
    
    try {
        // Build the base query with enhanced corporation name matching
        $sql = "SELECT *, 'IIBS' as source_type FROM iibs_records WHERE individual_corporation_involved = 'Corporation'";
        
        if ($category !== 'all') {
            if ($category === 'iibs') {
                $sql = "SELECT *, 'IIBS' as source_type FROM iibs_records WHERE individual_corporation_involved = 'Corporation'";
            } elseif ($category === 'ofac') {
                $sql = "SELECT *, 'OFAC' as source_type FROM ofac_records WHERE individual_corporation_involved = 'Corporation'";
            }
        } else {
            $sql = "
                (SELECT *, 'IIBS' as source_type FROM iibs_records WHERE individual_corporation_involved = 'Corporation')
                UNION ALL
                (SELECT *, 'OFAC' as source_type FROM ofac_records WHERE individual_corporation_involved = 'Corporation')
            ";
        }
        
        // Enhanced search conditions for corporation names
        $searchConditions = "
            AND (
                corporation_name_fullname LIKE :exact_match OR
                corporation_name_fullname LIKE :query OR
                alternate_name_alias LIKE :query OR
                alternate_name_alias_2 LIKE :query OR
                SOUNDEX(corporation_name_fullname) = SOUNDEX(:soundex_query) OR
                corporation_name_fullname REGEXP :regex_query
            )
        ";
        
        if ($category === 'all') {
            $sql = "SELECT * FROM (" . $sql . ") as combined_results WHERE 1=1 " . $searchConditions;
        } else {
            $sql .= $searchConditions;
        }
        
        $sql .= " ORDER BY 
            CASE 
                WHEN corporation_name_fullname = :exact_query THEN 1
                WHEN corporation_name_fullname LIKE :starts_with THEN 2
                WHEN alternate_name_alias = :exact_query THEN 3
                WHEN alternate_name_alias LIKE :starts_with THEN 4
                ELSE 5
            END,
            corporation_name_fullname ASC";
        
        $stmt = $pdo->prepare($sql);
        
        // Prepare search parameters
        $exactMatch = $query;
        $likeQuery = '%' . $query . '%';
        $startsWithQuery = $query . '%';
        $regexQuery = '[[:<:]]' . preg_quote($query, '/') . '[[:>:]]'; // Word boundary search
        
        $params = [
            'exact_match' => $exactMatch,
            'query' => $likeQuery,
            'soundex_query' => $query,
            'regex_query' => $regexQuery,
            'exact_query' => $exactMatch,
            'starts_with' => $startsWithQuery
        ];
        
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        error_log("Enhanced Corporation Search Error: " . $e->getMessage());
        return [];
    }
}

// Function to get search suggestions for corporation names
function getCorporationSuggestions($query, $limit = 10) {
    global $pdo;
    
    try {
        $sql = "
            (SELECT DISTINCT corporation_name_fullname as suggestion, 'IIBS' as source_type 
             FROM iibs_records 
             WHERE individual_corporation_involved = 'Corporation' 
             AND corporation_name_fullname LIKE :query)
            UNION
            (SELECT DISTINCT corporation_name_fullname as suggestion, 'OFAC' as source_type 
             FROM ofac_records 
             WHERE individual_corporation_involved = 'Corporation' 
             AND corporation_name_fullname LIKE :query)
            UNION
            (SELECT DISTINCT alternate_name_alias as suggestion, 'IIBS' as source_type 
             FROM iibs_records 
             WHERE individual_corporation_involved = 'Corporation' 
             AND alternate_name_alias LIKE :query AND alternate_name_alias IS NOT NULL)
            UNION
            (SELECT DISTINCT alternate_name_alias as suggestion, 'OFAC' as source_type 
             FROM ofac_records 
             WHERE individual_corporation_involved = 'Corporation' 
             AND alternate_name_alias LIKE :query AND alternate_name_alias IS NOT NULL)
            ORDER BY suggestion ASC
            LIMIT :limit
        ";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':query', '%' . $query . '%', PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        error_log("Corporation Suggestions Error: " . $e->getMessage());
        return [];
    }
}

// Function to get corporation statistics
function getCorporationStats() {
    global $pdo;
    
    try {
        $stats = [];
        
        // IIBS Corporation count
        $stmt = $pdo->query("SELECT COUNT(*) FROM iibs_records WHERE individual_corporation_involved = 'Corporation'");
        $stats['iibs_corporations'] = $stmt->fetchColumn();
        
        // OFAC Corporation count
        $stmt = $pdo->query("SELECT COUNT(*) FROM ofac_records WHERE individual_corporation_involved = 'Corporation'");
        $stats['ofac_corporations'] = $stmt->fetchColumn();
        
        // Total corporations
        $stats['total_corporations'] = $stats['iibs_corporations'] + $stats['ofac_corporations'];
        
        // Recent additions (last 30 days)
        $stmt = $pdo->query("
            SELECT COUNT(*) FROM (
                SELECT created_at FROM iibs_records WHERE individual_corporation_involved = 'Corporation' AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                UNION ALL
                SELECT created_at FROM ofac_records WHERE individual_corporation_involved = 'Corporation' AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            ) as recent
        ");
        $stats['recent_additions'] = $stmt->fetchColumn();
        
        return $stats;
        
    } catch (PDOException $e) {
        error_log("Corporation Stats Error: " . $e->getMessage());
        return [
            'iibs_corporations' => 0,
            'ofac_corporations' => 0,
            'total_corporations' => 0,
            'recent_additions' => 0
        ];
    }
}

// Function to highlight search terms in results
function highlightSearchTerms($text, $searchTerm) {
    if (empty($searchTerm) || empty($text)) {
        return htmlspecialchars($text);
    }
    
    $highlighted = preg_replace(
        '/(' . preg_quote($searchTerm, '/') . ')/i',
        '<mark style="background: #ffeb3b; padding: 2px 4px; border-radius: 3px; font-weight: bold;">$1</mark>',
        htmlspecialchars($text)
    );
    
    return $highlighted;
}

// Function to calculate search relevance score
function calculateRelevanceScore($record, $query) {
    $score = 0;
    $query = strtolower($query);
    
    $corporationName = strtolower($record['corporation_name_fullname'] ?? '');
    $alternateName = strtolower($record['alternate_name_alias'] ?? '');
    
    // Exact match gets highest score
    if ($corporationName === $query || $alternateName === $query) {
        $score += 100;
    }
    
    // Starts with query gets high score
    if (strpos($corporationName, $query) === 0 || strpos($alternateName, $query) === 0) {
        $score += 80;
    }
    
    // Contains query gets medium score
    if (strpos($corporationName, $query) !== false) {
        $score += 60;
    }
    if (strpos($alternateName, $query) !== false) {
        $score += 50;
    }
    
    // Word boundary match gets bonus
    if (preg_match('/\b' . preg_quote($query, '/') . '\b/i', $corporationName)) {
        $score += 40;
    }
    
    // Recent records get slight bonus
    $createdAt = strtotime($record['created_at']);
    $daysSinceCreated = (time() - $createdAt) / (24 * 60 * 60);
    if ($daysSinceCreated < 30) {
        $score += 10;
    }
    
    return $score;
}
?>
