<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: index.php");
    exit();
}
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corporation Search Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Corporation Search System</h1>
        <div class="header-info">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></span>
            <a href="logout.php" class="logout-link">Logout</a>
        </div>
    </header>
    
    <main class="dashboard">
        <section class="search-section">
            <h2>Search Corporations</h2>
            <form action="search_corporations.php" method="GET" class="search-form">
                <div class="search-inputs">
                    <input type="text" name="corporation_name" placeholder="Enter corporation name..." required>
                    <select name="category">
                        <option value="">All Categories</option>
                        <option value="OFAC">OFAC</option>
                        <option value="IIBS">IIBS</option>
                    </select>
                    <button type="submit">Search Corporations</button>
                </div>
            </form>
        </section>
        
        <section class="modal-section">
            <h2>Corporation Data Access</h2>
            <div class="modal-buttons">
                <button id="openOfac" class="modal-btn ofac-btn">View OFAC Corporations</button>
                <button id="openIibs" class="modal-btn iibs-btn">View IIBS Corporations</button>
            </div>
        </section>
    </main>
    
    <?php include 'modals/ofac_modal.php'; ?>
    <?php include 'modals/iibs_modal.php'; ?>
    
    <script>
    // Modal functionality
    document.getElementById('openOfac').addEventListener('click', function() {
        document.getElementById('ofacModal').style.display = 'flex';
    });
    
    document.getElementById('openIibs').addEventListener('click', function() {
        document.getElementById('iibsModal').style.display = 'flex';
    });
    
    document.querySelectorAll('.close-modal').forEach(function(closeBtn) {
        closeBtn.addEventListener('click', function() {
            this.closest('.modal').style.display = 'none';
        });
    });
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    });
    </script>
</body>
</html>
