<?php
/**
 * Test success page processing
 * Access: http://forever-together.local/test-success-page.php
 */

echo "<h1>Test Success Page Processing</h1>";

echo "<h2>Check Recent Sessions for €2015 Payment:</h2>";
echo "<p><a href='check-recent-sessions.php' target='_blank'>Check Recent Stripe Sessions</a></p>";

echo "<h2>Manual Processing:</h2>";
echo "<p>If you find the €2015 session, copy the session ID and use it in:</p>";
echo "<p><code>http://forever-together.local/debug-success-processing.php?session_id=YOUR_SESSION_ID</code></p>";

echo "<h2>Alternative - Process All Recent Sessions:</h2>";
echo "<p><a href='process-all-sessions.php' target='_blank'>Process All Recent Sessions</a></p>";
echo "<p>This will create certificates for all recent payments that don't have certificates yet.</p>";

echo "<h2>Debug the Success Page:</h2>";
echo "<p>Let's check if the success page is working correctly:</p>";
echo "<p><a href='certificate-success' target='_blank'>Visit Success Page Directly</a></p>";

?>
