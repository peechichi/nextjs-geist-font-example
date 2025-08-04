<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IIBS & OFAC Modal System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="main-container">
        <h1>IIBS & OFAC Modal System</h1>
        <p style="color: white; font-size: 1.2rem; margin-bottom: 40px;">
            Professional search system for IIBS and OFAC records
        </p>
        
        <div class="modal-buttons">
            <button class="btn" onclick="openModal('iibsModal')">
                Open IIBS Modal
            </button>
            <button class="btn btn-secondary" onclick="openModal('ofacModal')">
                Open OFAC Modal
            </button>
        </div>
        
        <div class="enhanced-search-section" style="margin: 30px 0;">
            <a href="search_corporations.php" class="btn" style="background: linear-gradient(45deg, #28a745, #20c997); text-decoration: none; font-size: 1.1rem; padding: 15px 30px;">
                🏢 Enhanced Corporation Search
            </a>
            <p style="color: #ccc; margin-top: 10px; font-size: 0.9rem;">
                Advanced search with relevance scoring, phonetic matching, and real-time suggestions
            </p>
        </div>
        
        <div class="admin-section">
            <a href="login.php" class="btn btn-admin">Admin Login</a>
        </div>
    </div>

    <!-- IIBS Modal -->
    <div id="iibsModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('iibsModal')">&times;</span>
            <h2>IIBS Search</h2>
            <p style="text-align: center; color: #666; margin-bottom: 20px;">
                Search for individuals and entities in the IIBS database
            </p>
            <form class="search-form" action="search_iibs.php" method="GET">
                <div class="form-group">
                    <label for="iibs-query">Search Query:</label>
                    <input type="text" id="iibs-query" name="query" placeholder="Enter name, reference number, or description..." required>
                </div>
                <button type="submit" class="btn">Search IIBS</button>
            </form>
        </div>
    </div>

    <!-- OFAC Modal -->
    <div id="ofacModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('ofacModal')">&times;</span>
            <h2>OFAC Search</h2>
            <p style="text-align: center; color: #666; margin-bottom: 20px;">
                Search for sanctioned individuals and entities in the OFAC database
            </p>
            <form class="search-form" action="search_ofac.php" method="GET">
                <div class="form-group">
                    <label for="ofac-query">Search Query:</label>
                    <input type="text" id="ofac-query" name="query" placeholder="Enter name, reference number, or description..." required>
                </div>
                <button type="submit" class="btn btn-secondary">Search OFAC</button>
            </form>
        </div>
    </div>

    <script>
        // Modal functionality
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
            document.body.style.overflow = 'auto'; // Restore scrolling
        }

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const iibsModal = document.getElementById('iibsModal');
            const ofacModal = document.getElementById('ofacModal');
            
            if (event.target === iibsModal) {
                closeModal('iibsModal');
            }
            if (event.target === ofacModal) {
                closeModal('ofacModal');
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal('iibsModal');
                closeModal('ofacModal');
            }
        });

        // Form validation
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('.search-form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const input = form.querySelector('input[name="query"]');
                    if (input.value.trim().length < 2) {
                        e.preventDefault();
                        alert('Please enter at least 2 characters for search.');
                        input.focus();
                    }
                });
            });
        });
    </script>
</body>
</html>
