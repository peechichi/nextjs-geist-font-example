<?php
require_once 'functions.php';

// Get and sanitize search query
$query = isset($_GET['query']) ? sanitize_input($_GET['query']) : '';
$results = [];
$search_performed = false;

if (!empty($query)) {
    $results = searchOFAC($query);
    $search_performed = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OFAC Search Results</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="results-container">
        <h2>OFAC Search Results</h2>
        
        <?php if ($search_performed): ?>
            <div style="background: #ffebee; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; border-left: 4px solid #f44336;">
                <strong>Search Query:</strong> "<?php echo htmlspecialchars($query); ?>"
                <br>
                <strong>Results Found:</strong> <?php echo count($results); ?>
                <br>
                <small style="color: #d32f2f; font-weight: bold;">⚠️ OFAC Sanctions Database</small>
            </div>
        <?php endif; ?>

        <!-- Search Form -->
        <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
            <form action="search_ofac.php" method="GET" class="search-form">
                <div class="form-group">
                    <label for="query">Search OFAC Database:</label>
                    <input type="text" 
                           id="query" 
                           name="query" 
                           value="<?php echo htmlspecialchars($query); ?>"
                           placeholder="Enter name, reference number, or description..." 
                           required>
                </div>
                <button type="submit" class="btn btn-secondary">Search OFAC</button>
            </form>
        </div>

        <!-- Results Section -->
        <?php if ($search_performed): ?>
            <?php if (count($results) > 0): ?>
                <div style="background: #ffebee; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #f44336;">
                    <strong style="color: #d32f2f;">⚠️ WARNING:</strong> 
                    <span style="color: #d32f2f;">The following entities are on the OFAC sanctions list. Any transactions or dealings with these entities may be prohibited.</span>
                </div>
                
                <ul class="results-list">
                    <?php foreach ($results as $record): ?>
                        <li class="result-item" style="border-left: 4px solid #f44336;">
                            <div class="result-name" style="color: #d32f2f;">
                                🚫 <?php echo htmlspecialchars(formatDisplayName($record)); ?>
                                <?php if ($record['individual_corporation_involved'] === 'Corporation'): ?>
                                    <span style="background: #ffebee; color: #d32f2f; padding: 2px 8px; border-radius: 12px; font-size: 0.8rem; margin-left: 10px;">Corporation</span>
                                <?php else: ?>
                                    <span style="background: #ffebee; color: #d32f2f; padding: 2px 8px; border-radius: 12px; font-size: 0.8rem; margin-left: 10px;">Individual</span>
                                <?php endif; ?>
                            </div>
                            
                            <?php $alternates = getAlternateNames($record); ?>
                            <?php if (!empty($alternates)): ?>
                                <div style="color: #d32f2f; font-style: italic; margin: 5px 0;">
                                    <strong>Also known as:</strong> <?php echo htmlspecialchars(implode(', ', $alternates)); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="result-description">
                                <?php echo nl2br(htmlspecialchars($record['resolution'])); ?>
                            </div>
                            
                            <div class="result-reference">
                                Source: <?php echo htmlspecialchars($record['source_media']); ?>
                                <?php if ($record['source_media_date']): ?>
                                    | Date: <?php echo date('M d, Y', strtotime($record['source_media_date'])); ?>
                                <?php endif; ?>
                                | Status: <span style="color: #d32f2f; font-weight: bold;"><?php echo htmlspecialchars($record['status']); ?></span>
                                | Added: <?php echo date('M d, Y', strtotime($record['created_at'])); ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="no-results">
                    <h3>No Results Found</h3>
                    <p>No OFAC records match your search query "<?php echo htmlspecialchars($query); ?>".</p>
                    <p style="color: #28a745; font-weight: bold;">✅ This entity does not appear on the OFAC sanctions list.</p>
                    <p><small>Note: This search is based on the current database. Always verify with official OFAC sources for the most up-to-date information.</small></p>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div style="text-align: center; color: #666; padding: 40px;">
                <h3>OFAC Sanctions Database Search</h3>
                <p>Enter a search term above to check against the OFAC sanctions list.</p>
                <p>You can search by name, reference number, or description.</p>
                <div style="background: #fff3cd; padding: 15px; border-radius: 8px; margin-top: 20px; border-left: 4px solid #ffc107;">
                    <strong style="color: #856404;">About OFAC:</strong>
                    <p style="color: #856404; margin: 10px 0 0 0;">
                        The Office of Foreign Assets Control (OFAC) administers and enforces economic sanctions programs primarily against countries and groups of individuals, such as terrorists and narcotics traffickers.
                    </p>
                </div>
            </div>
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

        <!-- Search Tips -->
        <div style="background: #fff3cd; padding: 20px; border-radius: 10px; margin-top: 30px; border-left: 4px solid #ffc107;">
            <h4 style="color: #856404; margin-bottom: 10px;">Search Tips:</h4>
            <ul style="color: #856404; margin: 0; padding-left: 20px;">
                <li>Use partial names or keywords for broader results</li>
                <li>Search is case-insensitive</li>
                <li>You can search by reference numbers (e.g., "OFAC001")</li>
                <li>Try searching by description keywords</li>
                <li>Always verify results with official OFAC sources</li>
            </ul>
        </div>

        <!-- Disclaimer -->
        <div style="background: #f8d7da; padding: 20px; border-radius: 10px; margin-top: 20px; border-left: 4px solid #dc3545;">
            <h4 style="color: #721c24; margin-bottom: 10px;">⚠️ Important Disclaimer:</h4>
            <p style="color: #721c24; margin: 0; font-size: 0.9rem;">
                This search tool is for informational purposes only. Always consult the official OFAC website (treasury.gov/ofac) 
                for the most current and complete sanctions information. Users are responsible for ensuring compliance with all 
                applicable laws and regulations.
            </p>
        </div>
    </div>

    <script>
        // Auto-focus on search input
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('query').focus();
        });

        // Highlight search terms in results
        <?php if ($search_performed && !empty($query)): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const searchTerm = <?php echo json_encode($query); ?>;
            const resultItems = document.querySelectorAll('.result-item');
            
            resultItems.forEach(item => {
                const regex = new RegExp(`(${searchTerm})`, 'gi');
                item.innerHTML = item.innerHTML.replace(regex, '<mark style="background: #ffcdd2; padding: 2px; border: 1px solid #f44336;">$1</mark>');
            });
        });
        <?php endif; ?>

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const input = document.getElementById('query');
            if (input.value.trim().length < 2) {
                e.preventDefault();
                alert('Please enter at least 2 characters for search.');
                input.focus();
            }
        });
    </script>
</body>
</html>
