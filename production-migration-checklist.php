<?php
/**
 * Production Migration Checklist
 * Run this after uploading to live site
 */

echo "<h1>Production Migration Checklist</h1>";

echo "<h2>🔧 Configuration Changes Required:</h2>";

echo "<h3>1. Stripe Configuration</h3>";
echo "<ul>";
echo "<li>✅ Switch from Test to Live Keys in WordPress Admin → Settings → Stripe Settings</li>";
echo "<li>✅ Update webhook secret to live webhook secret</li>";
echo "<li>✅ Create new webhook in Stripe Dashboard</li>";
echo "</ul>";

echo "<h3>2. Webhook URL Updates</h3>";
echo "<p><strong>New Webhook URL:</strong> <code>https://red-grouse-914732.hostingersite.com/wp-json/tf/v1/stripe-webhook</code></p>";
echo "<p>Update this in your Stripe Dashboard webhook settings.</p>";

echo "<h3>3. Email Configuration</h3>";
echo "<ul>";
echo "<li>✅ Update SMTP settings for production</li>";
echo "<li>✅ Change 'From' email to: <code>noreply@together-forever.fund</code></li>";
echo "<li>✅ Test email sending</li>";
echo "</ul>";

echo "<h3>4. URL References</h3>";
echo "<ul>";
echo "<li>✅ Success URL: <code>https://red-grouse-914732.hostingersite.com/certificate-success</code></li>";
echo "<li>✅ Cancel URL: <code>https://red-grouse-914732.hostingersite.com/certificate</code></li>";
echo "<li>✅ Activation URL: <code>https://red-grouse-914732.hostingersite.com/activate-certificate</code></li>";
echo "</ul>";

echo "<h2>📋 Testing Checklist:</h2>";
echo "<ol>";
echo "<li><strong>Test Certificate Purchase:</strong> Go to /certificate and make a test purchase</li>";
echo "<li><strong>Verify Email:</strong> Check if certificate email is received</li>";
echo "<li><strong>Test Activation:</strong> Activate the certificate on /activate-certificate</li>";
echo "<li><strong>Check Database:</strong> Verify certificate is created in database</li>";
echo "</ol>";

echo "<h2>🎯 Files to Check:</h2>";
echo "<ul>";
echo "<li><code>wp-content/themes/together-forever/inc/stripe-integration.php</code> - Check URLs</li>";
echo "<li><code>wp-content/themes/together-forever/certificate-success.php</code> - Check processing</li>";
echo "<li><code>wp-content/themes/together-forever/activate-certificate.php</code> - Check activation</li>";
echo "</ul>";

echo "<h2>🚨 Important Notes:</h2>";
echo "<ul>";
echo "<li><strong>Remove debug files:</strong> Delete all debug-*.php files from production</li>";
echo "<li><strong>Test thoroughly:</strong> Make sure everything works before going live</li>";
echo "<li><strong>Monitor logs:</strong> Check error logs for any issues</li>";
echo "</ul>";

?>
