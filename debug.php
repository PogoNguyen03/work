<?php
echo "<h1>Debug Information</h1>";
echo "<h2>Server Variables:</h2>";
echo "<pre>";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "PHP_SELF: " . $_SERVER['PHP_SELF'] . "\n";
echo "DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "HTTP_HOST: " . $_SERVER['HTTP_HOST'] . "\n";
echo "HTTPS: " . (isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : 'off') . "\n";
echo "</pre>";

echo "<h2>Path Analysis:</h2>";
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);
$path = trim($path, '/');
echo "Original path: '$path'\n";

// Remove 'public' from path if present
$path = str_replace('public', '', $path);
$path = trim($path, '/');
echo "After removing 'public': '$path'\n";

echo "<h2>Current Directory:</h2>";
echo getcwd();

echo "<h2>Files in current directory:</h2>";
echo "<pre>";
$files = scandir('.');
foreach ($files as $file) {
    if ($file !== '.' && $file !== '..') {
        echo $file . "\n";
    }
}
echo "</pre>";
?> 