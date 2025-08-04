<?php
require_once 'enhanced_search.php';

// Get and sanitize search parameters
$query = isset($_GET['query']) ? sanitize_input($_GET['query']) : '';
$category = isset($_GET['category']) ? sanitize_input($_GET['category']) : 'all';
$results = [];
$search_performed = false;
$suggestions = [];

// Get corporation statistics
$stats = getCorporationStats();

if (!empty($query)) {
    $results = enhancedCorporationSearch($query, $category);
    $search_performed = true;
    
    // Sort results by relevance score
    usort($results, function($a, $b) use ($query) {
        $scoreA = calculateRelevanceScore($a, $query);
        $scoreB = calculateRelevanceScore($b, $query);
        return $scoreB - $scoreA; // Descending order
    });
} else {
    // Get suggestions for empty query (popular corporation names)
    $suggestions = getCorporationSuggestions('', 20);
}

// Get search suggestions for autocomplete
$searchSuggestions = [];
if (strlen($query) >= 2) {
    $searchSuggestions = getCorporationSuggestions($query, 10);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced Corporation Search - IIBS & OFAC System</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .search-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 8px 8px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }
        
        .suggestion-item {
            padding: 10px 15px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .suggestion-item:hover {
            background: #f8f9fa;
        }
        
        .suggestion-item:last-child {
            border-bottom: none;
        }
        
        .suggestion-source {
            font-size: 0.8rem;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: bold;
        }
        
        .suggestion-source.iibs {
            background: #e8f5e8;
            color: #2e7d32;
        }
        
        .suggestion-source.ofac {
            background: #ffebee;
            color: #c62828;
        }
        
        .search-container {
            position: relative;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        
        .relevance-score {
            background: #e3f2fd;
            color: #1976d2;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: bold;
            margin-left: 10px;
        }
        
        .search-filters {
            display: flex;
            gap: 15px;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .filter-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .advanced-search {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .search-tips {
            background: #fff3cd;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #ffc107;
            margin-top: 20px;
        }
        
        .popular-searches {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        
        .popular-item {
            display: inline-block;
            background: white;
            padding: 8px 15px;
            margin: 5px;
            border-radius: 20px;
            text-decoration: none;
            color: #333;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }
        
        .popular-item:hover {
            background: #007bff;
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="results-container">
        <h2>Enhanced Corporation Search</h2>
        
        <!-- Statistics Dashboard -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number" style="color: #2e7d32;"><?php echo $stats['iibs_corporations']; ?></div>
                <div class="stat-label">IIBS Corporations</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" style="color: #c62828;"><?php echo $stats['ofac_corporations']; ?></div>
                <div class="stat-label">OFAC Corporations</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" style="color: #1976d2;"><?php echo $stats['total_corporations']; ?></div>
                <div class="stat-label">Total Corporations</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" style="color: #f57c00;"><?php echo $stats['recent_additions']; ?></div>
                <div class="stat-label">Recent Additions (30 days)</div>
            </div>
        </div>

        <?php if ($search_performed): ?>
            <div style="background: #e3f2fd; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                <strong>Search Query:</strong> "<?php echo htmlspecialchars($query); ?>"
                <br>
                <strong>Category:</strong> <?php echo ucfirst($category); ?>
                <br>
                <strong>Results Found:</strong> <?php echo count($results); ?> corporation(s)
            </div>
        <?php endif; ?>

        <!-- Enhanced Search Form -->
        <div class="advanced-search">
            <form action="search_corporations.php" method="GET" class="search-form">
                <div class="search-container">
                    <div class="form-group">
                        <label for="query">Search Corporation Names:</label>
                        <input type="text" 
                               id="query" 
                               name="query" 
                               value="<?php echo htmlspecialchars($query); ?>"
                               placeholder="Enter corporation name, alternate name, or keywords..." 
                               autocomplete="off"
                               required>
                        <div class="search-suggestions" id="suggestions"></div>
                    </div>
                </div>
                
                <div class="search-filters">
                    <div class="filter-group">
                        <label for="category">Category:</label>
                        <select id="category" name="category">
                            <option value="all" <?php echo $category === 'all' ? 'selected' : ''; ?>>All Sources</option>
                            <option value="iibs" <?php echo $category === 'iibs' ? 'selected' : ''; ?>>IIBS Only</option>
                            <option value="ofac" <?php echo $category === 'ofac' ? 'selected' : ''; ?>>OFAC Only</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn">
                        🔍 Search Corporations
                    </button>
                    
                    <?php if ($search_performed): ?>
                        <a href="search_corporations.php" class="btn" style="background: #6c757d; text-decoration: none;">
                            Clear Search
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Search Results -->
        <?php if ($search_performed): ?>
            <?php if (count($results) > 0): ?>
                <div style="margin-bottom: 20px;">
                    <h3>Search Results (<?php echo count($results); ?> found)</h3>
                </div>
                
                <ul class="results-list">
                    <?php foreach ($results as $index => $record): ?>
                        <?php $relevanceScore = calculateRelevanceScore($record, $query); ?>
                        <li class="result-item">
                            <div class="result-name">
                                <?php echo highlightSearchTerms($record['corporation_name_fullname'], $query); ?>
                                <span class="category-badge <?php echo strtolower($record['source_type']); ?>">
                                    <?php echo $record['source_type']; ?>
                                </span>
                                <span class="relevance-score">
                                    Relevance: <?php echo $relevanceScore; ?>%
                                </span>
                            </div>
                            
                            <?php $alternates = getAlternateNames($record); ?>
                            <?php if (!empty($alternates)): ?>
                                <div style="color: #666; font-style: italic; margin: 5px 0;">
                                    <strong>Also known as:</strong> 
                                    <?php 
                                    $highlightedAlternates = array_map(function($alt) use ($query) {
                                        return highlightSearchTerms($alt, $query);
                                    }, $alternates);
                                    echo implode(', ', $highlightedAlternates);
                                    ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="result-description">
                                <?php echo nl2br(highlightSearchTerms($record['resolution'], $query)); ?>
                            </div>
                            
                            <div class="result-reference">
                                <strong>Source:</strong> <?php echo htmlspecialchars($record['source_media']); ?>
                                <?php if ($record['source_media_date']): ?>
                                    | <strong>Date:</strong> <?php echo date('M d, Y', strtotime($record['source_media_date'])); ?>
                                <?php endif; ?>
                                | <strong>Status:</strong> <?php echo htmlspecialchars($record['status']); ?>
                                | <strong>Added:</strong> <?php echo date('M d, Y', strtotime($record['created_at'])); ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="no-results">
                    <h3>No Corporation Records Found</h3>
                    <p>No corporation records match your search query "<?php echo htmlspecialchars($query); ?>".</p>
                    <p>Try using different keywords, check the spelling, or search for alternate names.</p>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div style="text-align: center; color: #666; padding: 40px;">
                <h3>Enhanced Corporation Database Search</h3>
                <p>Search for corporations in both IIBS and OFAC databases with advanced matching algorithms.</p>
                <p>Features include exact matching, phonetic search, and relevance scoring.</p>
            </div>
            
            <?php if (!empty($suggestions)): ?>
                <div class="popular-searches">
                    <h4>Popular Corporation Names:</h4>
                    <?php foreach (array_slice($suggestions, 0, 10) as $suggestion): ?>
                        <a href="search_corporations.php?query=<?php echo urlencode($suggestion['suggestion']); ?>" 
                           class="popular-item">
                            <?php echo htmlspecialchars($suggestion['suggestion']); ?>
                            <small>(<?php echo $suggestion['source_type']; ?>)</small>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Navigation -->
        <div style="text-align: center; margin-top: 30px;">
            <a href="index.php" class="back-btn">Back to Home</a>
            <?php if (isset($_SESSION['admin'])): ?>
                <a href="admin_dashboard.php" class="btn" style="margin-left: 10px; text-decoration: none;">
                    Admin Dashboard
                </a>
            <?php endif; ?>
        </div>

        <!-- Enhanced Search Tips -->
        <div class="search-tips">
            <h4 style="color: #856404; margin-bottom: 10px;">Enhanced Search Features:</h4>
            <ul style="color: #856404; margin: 0; padding-left: 20px;">
                <li><strong>Exact Match:</strong> Search for exact corporation names (highest relevance)</li>
                <li><strong>Partial Match:</strong> Find corporations containing your search terms</li>
                <li><strong>Phonetic Search:</strong> Find similar-sounding corporation names</li>
                <li><strong>Alternate Names:</strong> Search includes all known aliases and alternate names</li>
                <li><strong>Word Boundary:</strong> Match whole words within corporation names</li>
                <li><strong>Relevance Scoring:</strong> Results ranked by relevance to your search</li>
                <li><strong>Auto-suggestions:</strong> Get suggestions as you type</li>
                <li><strong>Category Filtering:</strong> Filter by IIBS or OFAC sources</li>
            </ul>
        </div>
    </div>

    <script>
        // Enhanced search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const queryInput = document.getElementById('query');
            const suggestionsDiv = document.getElementById('suggestions');
            let searchTimeout;

            // Auto-focus on search input
            queryInput.focus();

            // Real-time search suggestions
            queryInput.addEventListener('input', function() {
                const query = this.value.trim();
                
                clearTimeout(searchTimeout);
                
                if (query.length >= 2) {
                    searchTimeout = setTimeout(() => {
                        fetchSuggestions(query);
                    }, 300);
                } else {
                    hideSuggestions();
                }
            });

            // Hide suggestions when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.search-container')) {
                    hideSuggestions();
                }
            });

            // Fetch suggestions via AJAX
            function fetchSuggestions(query) {
                fetch(`get_suggestions.php?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        displaySuggestions(data);
                    })
                    .catch(error => {
                        console.error('Error fetching suggestions:', error);
                    });
            }

            // Display suggestions
            function displaySuggestions(suggestions) {
                if (suggestions.length === 0) {
                    hideSuggestions();
                    return;
                }

                let html = '';
                suggestions.forEach(suggestion => {
                    html += `
                        <div class="suggestion-item" onclick="selectSuggestion('${suggestion.suggestion}')">
                            <span>${suggestion.suggestion}</span>
                            <span class="suggestion-source ${suggestion.source_type.toLowerCase()}">${suggestion.source_type}</span>
                        </div>
                    `;
                });

                suggestionsDiv.innerHTML = html;
                suggestionsDiv.style.display = 'block';
            }

            // Hide suggestions
            function hideSuggestions() {
                suggestionsDiv.style.display = 'none';
            }

            // Select suggestion
            window.selectSuggestion = function(suggestion) {
                queryInput.value = suggestion;
                hideSuggestions();
                queryInput.focus();
            };

            // Form validation
            document.querySelector('form').addEventListener('submit', function(e) {
                const input = document.getElementById('query');
                if (input.value.trim().length < 2) {
                    e.preventDefault();
                    alert('Please enter at least 2 characters for search.');
                    input.focus();
                }
            });

            // Keyboard navigation for suggestions
            queryInput.addEventListener('keydown', function(e) {
                const suggestions = suggestionsDiv.querySelectorAll('.suggestion-item');
                const currentActive = suggestionsDiv.querySelector('.suggestion-item.active');
                
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (currentActive) {
                        currentActive.classList.remove('active');
                        const next = currentActive.nextElementSibling;
                        if (next) {
                            next.classList.add('active');
                        } else {
                            suggestions[0].classList.add('active');
                        }
                    } else if (suggestions.length > 0) {
                        suggestions[0].classList.add('active');
                    }
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (currentActive) {
                        currentActive.classList.remove('active');
                        const prev = currentActive.previousElementSibling;
                        if (prev) {
                            prev.classList.add('active');
                        } else {
                            suggestions[suggestions.length - 1].classList.add('active');
                        }
                    } else if (suggestions.length > 0) {
                        suggestions[suggestions.length - 1].classList.add('active');
                    }
                } else if (e.key === 'Enter') {
                    if (currentActive) {
                        e.preventDefault();
                        currentActive.click();
                    }
                } else if (e.key === 'Escape') {
                    hideSuggestions();
                }
            });
        });

        // Add active class styling
        const style = document.createElement('style');
        style.textContent = `
            .suggestion-item.active {
                background: #007bff !important;
                color: white !important;
            }
            .suggestion-item.active .suggestion-source {
                background: rgba(255,255,255,0.2) !important;
                color: white !important;
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
