<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: index.php");
    exit();
}

require_once 'config.php';

$corporation_name = $_GET['corporation_name'] ?? '';
$category = $_GET['category'] ?? '';

try {
    $pdo = getDBConnection();
    
    // Build the search query
    $sql = "SELECT * FROM corporations WHERE (
        corporation_name_fullname LIKE :name OR 
        alternate_name_alias LIKE :name OR 
        alternate_name_alias_2 LIKE :name OR
        CONCAT(first_name, ' ', COALESCE(middle_name, ''), ' ', last_name) LIKE :name
    )";
    
    $params = [':name' => '%' . $corporation_name . '%'];
    
    if (!empty($category)) {
        $sql .= " AND category = :category";
        $params[':category'] = $category;
    }
    
    $sql .= " ORDER BY corporation_name_fullname ASC, last_name ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    die("Search error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corporation Search Results</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Search Results for "<?php echo htmlspecialchars($corporation_name); ?>"</h1>
        <a href="admin.php" class="back-link">Back to Dashboard</a>
    </header>
    
    <main class="results-container">
        <?php if(count($results) > 0): ?>
            <p class="results-count"><?php echo count($results); ?> record(s) found</p>
            <div class="table-container">
                <table class="results-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category</th>
                            <th>Source Media</th>
                            <th>Source Date</th>
                            <th>Resolution</th>
                            <th>Type</th>
                            <th>Name</th>
                            <th>Alternate Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($results as $record): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($record['id']); ?></td>
                            <td><span class="category-badge <?php echo strtolower($record['category']); ?>">
                                <?php echo htmlspecialchars($record['category']); ?>
                            </span></td>
                            <td><?php echo htmlspecialchars($record['source_media'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($record['source_media_date'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($record['resolution'] ?? ''); ?></td>
                            <td><span class="type-badge <?php echo strtolower($record['individual_corporation_involved'] ?? 'corporation'); ?>">
                                <?php echo htmlspecialchars($record['individual_corporation_involved'] ?? 'Corporation'); ?>
                            </span></td>
                            <td class="name-cell">
                                <?php if ($record['individual_corporation_involved'] === 'Individual'): ?>
                                    <?php 
                                    $fullName = trim(
                                        ($record['first_name'] ?? '') . ' ' . 
                                        ($record['middle_name'] ?? '') . ' ' . 
                                        ($record['last_name'] ?? '') . ' ' . 
                                        ($record['name_ext'] ?? '')
                                    );
                                    echo htmlspecialchars($fullName);
                                    ?>
                                <?php else: ?>
                                    <?php echo htmlspecialchars($record['corporation_name_fullname'] ?? ''); ?>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($record['alternate_name_alias'] ?? ''); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="no-results">
                <p>No records found matching "<?php echo htmlspecialchars($corporation_name); ?>"</p>
                <?php if(!empty($category)): ?>
                    <p>in category: <?php echo htmlspecialchars($category); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
