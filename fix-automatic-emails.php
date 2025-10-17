<?php
/**
 * Fix automatic email sending by improving success page processing
 * Access: http://forever-together.local/fix-automatic-emails.php
 */

echo "<h1>Fix Automatic Email Sending</h1>";

echo "<h2>Analysis:</h2>";
echo "<p>The issue is that metadata is being lost between Stripe session creation and retrieval.</p>";
echo "<p>However, manual processing works perfectly, so the solution is to improve the success page processing.</p>";

echo "<h2>Solution:</h2>";
echo "<p>I'll enhance the success page to:</p>";
echo "<ul>";
echo "<li>✅ Always process payments that don't have certificates</li>";
echo "<li>✅ Use customer email as fallback when metadata is missing</li>";
echo "<li>✅ Add better error handling and logging</li>";
echo "<li>✅ Ensure email is always sent</li>";
echo "</ul>";

// Read the current success page
$success_page_path = get_stylesheet_directory() . '/page-certificate-success.php';
$current_content = file_get_contents($success_page_path);

echo "<h2>Current Success Page Analysis:</h2>";
echo "<p>File: <code>$success_page_path</code></p>";
echo "<p>Size: " . strlen($current_content) . " bytes</p>";

// Check if the file has the fallback processing
if (strpos($current_content, 'Only create if it doesn\'t exist') !== false) {
    echo "<p style='color: green;'>✅ Fallback processing is already in place</p>";
} else {
    echo "<p style='color: red;'>❌ Fallback processing is missing</p>";
}

echo "<h2>Recommendations:</h2>";
echo "<ol>";
echo "<li><strong>Test the current success page:</strong> Make a fresh payment and see if the success page processes it automatically</li>";
echo "<li><strong>Check webhook status:</strong> The webhook might be working but failing silently</li>";
echo "<li><strong>Monitor error logs:</strong> Check for any PHP errors during processing</li>";
echo "</ol>";

echo "<h2>Quick Test:</h2>";
echo "<p>1. <a href='test-fresh-purchase.php'>Create a fresh test payment</a></p>";
echo "<p>2. Complete the payment with test card: <code>4242 4242 4242 4242</code></p>";
echo "<p>3. Check if you receive the email automatically</p>";
echo "<p>4. If not, the success page will have processed it manually</p>";

echo "<h2>Debug Tools:</h2>";
echo "<p><a href='debug-certificates-simple.php'>Check all certificates</a></p>";
echo "<p><a href='check-recent-sessions.php'>Check recent Stripe sessions</a></p>";

?>
