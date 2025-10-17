<?php
/**
 * Complete Certificate Dashboard - Admin Only
 * Shows ALL certificates with full details and status
 */

// Load WordPress
require_once(dirname(__FILE__) . '/wp-load.php');

// Check if user is admin
if (!current_user_can('administrator')) {
    wp_die('Access denied. Admin only.');
}

// Include certificate system
require_once(get_stylesheet_directory() . '/inc/certificate-system.php');

global $wpdb;
$table_name = $wpdb->prefix . 'tf_certificates';

// Check if table exists
$table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name;

if (!$table_exists) {
    echo "<p style='color: red;'>❌ Certificate table doesn't exist</p>";
    exit;
}

// Handle actions
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'clear_all':
            if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
                $result = $wpdb->query("DELETE FROM $table_name");
                wp_cache_flush();
                echo "<div style='background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
                echo "✅ Cleared $result certificates from database.";
                echo "</div>";
            }
            break;
            
        case 'reset_status':
            $cert_id = intval($_POST['cert_id']);
            if ($cert_id > 0) {
                $result = $wpdb->update(
                    $table_name,
                    array('is_active' => 1, 'activated_at' => null, 'activated_by_email' => ''),
                    array('id' => $cert_id),
                    array('%d', '%s', '%s'),
                    array('%d')
                );
                if ($result) {
                    echo "<div style='background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
                    echo "✅ Certificate status reset to Active.";
                    echo "</div>";
                }
            }
            break;
    }
}

// Get all certificates
$certificates = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC");

// Calculate statistics
$total_certificates = count($certificates);
$active_certificates = count(array_filter($certificates, function($cert) { return $cert->is_active == 1; }));
$used_certificates = $total_certificates - $active_certificates;
$total_amount = array_sum(array_column($certificates, 'amount'));

?>

<!DOCTYPE html>
<html>
<head>
    <title>Certificate Dashboard - Admin</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .stats { display: flex; gap: 20px; margin: 20px 0; }
        .stat-box { background: #f8f9fa; padding: 15px; border-radius: 5px; border-left: 4px solid #007cba; }
        .stat-number { font-size: 24px; font-weight: bold; color: #007cba; }
        .stat-label { color: #666; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .status-active { color: green; font-weight: bold; }
        .status-used { color: red; font-weight: bold; }
        .actions { margin: 20px 0; }
        .btn { padding: 8px 16px; margin: 5px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-warning { background: #ffc107; color: black; }
        .btn-info { background: #17a2b8; color: white; }
        .btn-success { background: #28a745; color: white; }
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
        .modal-content { background: white; margin: 15% auto; padding: 20px; width: 300px; border-radius: 5px; }
        .search-box { margin: 20px 0; }
        .search-box input { padding: 8px; width: 300px; }
    </style>
</head>
<body>

<h1>🎫 Certificate Dashboard</h1>

<!-- Statistics -->
<div class="stats">
    <div class="stat-box">
        <div class="stat-number"><?php echo $total_certificates; ?></div>
        <div class="stat-label">Total Certificates</div>
    </div>
    <div class="stat-box">
        <div class="stat-number"><?php echo $active_certificates; ?></div>
        <div class="stat-label">Active Certificates</div>
    </div>
    <div class="stat-box">
        <div class="stat-number"><?php echo $used_certificates; ?></div>
        <div class="stat-label">Used Certificates</div>
    </div>
    <div class="stat-box">
        <div class="stat-number">€<?php echo number_format($total_amount, 2); ?></div>
        <div class="stat-label">Total Amount</div>
    </div>
</div>

<!-- Search -->
<div class="search-box">
    <input type="text" id="searchInput" placeholder="Search certificates..." onkeyup="filterTable()">
</div>

<!-- Actions -->
<div class="actions">
    <button class="btn btn-info" onclick="exportCSV()">📊 Export CSV</button>
    <button class="btn btn-warning" onclick="showClearModal()">🗑️ Clear All Certificates</button>
    <button class="btn btn-success" onclick="location.reload()">🔄 Refresh</button>
</div>

<!-- Certificates Table -->
<?php if (empty($certificates)): ?>
    <p>No certificates found.</p>
<?php else: ?>
    <table id="certificatesTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Certificate Code</th>
                <th>Beneficiary</th>
                <th>From</th>
                <th>Giver</th>
                <th>Email</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Activated By</th>
                <th>Activated At</th>
                <th>Created</th>
                <th>Session ID</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($certificates as $cert): ?>
                <tr>
                    <td><?php echo $cert->id; ?></td>
                    <td><code><?php echo htmlspecialchars($cert->certificate_code); ?></code></td>
                    <td><?php echo htmlspecialchars($cert->beneficiary_name); ?></td>
                    <td><?php echo htmlspecialchars($cert->beneficiary_from); ?></td>
                    <td><?php echo htmlspecialchars($cert->giver_name); ?></td>
                    <td><?php echo htmlspecialchars($cert->recipient_email); ?></td>
                    <td>€<?php echo number_format($cert->amount, 2); ?></td>
                    <td>
                        <?php if ($cert->is_active == 1): ?>
                            <span class="status-active">✅ Active</span>
                        <?php else: ?>
                            <span class="status-used">❌ Used</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $cert->activated_by_email ? htmlspecialchars($cert->activated_by_email) : '-'; ?></td>
                    <td><?php echo $cert->activated_at ? $cert->activated_at : '-'; ?></td>
                    <td><?php echo $cert->created_at; ?></td>
                    <td><small><?php echo substr($cert->stripe_session_id, 0, 20) . '...'; ?></small></td>
                    <td>
                        <?php if ($cert->is_active != 1): ?>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="action" value="reset_status">
                                <input type="hidden" name="cert_id" value="<?php echo $cert->id; ?>">
                                <button type="submit" class="btn btn-warning" onclick="return confirm('Reset this certificate to Active?')">Reset</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<!-- Clear All Modal -->
<div id="clearModal" class="modal">
    <div class="modal-content">
        <h3>⚠️ Clear All Certificates</h3>
        <p>Are you sure you want to delete ALL certificates from the database?</p>
        <p><strong>This action cannot be undone!</strong></p>
        <form method="post">
            <input type="hidden" name="action" value="clear_all">
            <input type="hidden" name="confirm" value="yes">
            <button type="submit" class="btn btn-danger">Yes, Clear All</button>
            <button type="button" class="btn" onclick="hideClearModal()">Cancel</button>
        </form>
    </div>
</div>

<script>
function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('certificatesTable');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    }
}

function showClearModal() {
    document.getElementById('clearModal').style.display = 'block';
}

function hideClearModal() {
    document.getElementById('clearModal').style.display = 'none';
}

function exportCSV() {
    const table = document.getElementById('certificatesTable');
    const rows = table.getElementsByTagName('tr');
    let csv = [];
    
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const cols = row.getElementsByTagName('td');
        let rowData = [];
        
        for (let j = 0; j < cols.length - 1; j++) { // Exclude Actions column
            rowData.push('"' + cols[j].textContent.replace(/"/g, '""') + '"');
        }
        csv.push(rowData.join(','));
    }
    
    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'certificates_' + new Date().toISOString().split('T')[0] + '.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('clearModal');
    if (event.target == modal) {
        hideClearModal();
    }
}
</script>

</body>
</html>
