<?php
// Fetch IIBS records for modal
try {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM corporations WHERE category = 'IIBS' ORDER BY corporation_name_fullname, last_name");
    $stmt->execute();
    $iibs_records = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $iibs_records = [];
}
?>

<div id="iibsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>IIBS Records</h2>
            <span class="close-modal" data-modal="iibsModal">&times;</span>
        </div>
        <div class="modal-body">
            <?php if(count($iibs_records) > 0): ?>
                <p class="modal-count"><?php echo count($iibs_records); ?> IIBS record(s) found</p>
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
                            <?php foreach($iibs_records as $record): ?>
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
                <p class="no-data">No IIBS records found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
