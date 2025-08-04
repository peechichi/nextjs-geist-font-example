<?php
// Fetch OFAC records for modal
try {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM corporations WHERE category = 'OFAC' ORDER BY corporation_name_fullname, last_name");
    $stmt->execute();
    $ofac_records = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $ofac_records = [];
}
?>

<div id="ofacModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>OFAC Records</h2>
            <span class="close-modal" data-modal="ofacModal">&times;</span>
        </div>
        <div class="modal-body">
            <?php if(count($ofac_records) > 0): ?>
                <p class="modal-count"><?php echo count($ofac_records); ?> OFAC record(s) found</p>
                <div class="modal-table-container">
                    <table class="modal-table">
                        <thead>
                            <tr>
                                <th>Source Media</th>
                                <th>Date</th>
                                <th>Resolution</th>
                                <th>Type</th>
                                <th>Name</th>
                                <th>Alternate Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($ofac_records as $record): ?>
                            <tr>
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
                <p class="no-data">No OFAC records found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
