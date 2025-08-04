<?php
header('Content-Type: application/json');
require_once 'enhanced_search.php';

// Get query parameter
$query = isset($_GET['query']) ? sanitize_input($_GET['query']) : '';

// Return empty array if query is too short
if (strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

// Get suggestions
$suggestions = getCorporationSuggestions($query, 8);

// Return JSON response
echo json_encode($suggestions);
?>
