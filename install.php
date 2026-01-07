<?php

echo PHP_EOL;
echo "======================================" . PHP_EOL;
echo " Localhost Explorer Installer" . PHP_EOL;
echo "======================================" . PHP_EOL;

$repoDir = realpath(__DIR__);
$docRoot = realpath(dirname($repoDir));

if (!$docRoot) {
    exit("❌ Unable to detect Apache document root" . PHP_EOL);
}

echo "📁 Repository : $repoDir" . PHP_EOL;
echo "📁 Doc Root   : $docRoot" . PHP_EOL;

// Safety check
if (basename($repoDir) !== 'localhost-explorer') {
    exit("❌ Please place this repository inside Apache document root" . PHP_EOL);
}

$timestamp = date('Ymd_His');

$files = [
    'index.php' => <<<PHP
<?php
header('Location: localhost-explorer/');
exit;
PHP,
    '.htaccess' => <<<HT
RewriteEngine On

# Redirect root to localhost-explorer
RewriteRule ^$ localhost-explorer/ [L]
HT
];

foreach ($files as $file => $content) {
    $target = $docRoot . DIRECTORY_SEPARATOR . $file;

    if (file_exists($target)) {
        rename($target, $target . ".bak_$timestamp");
        echo "📦 Backup created: $file" . PHP_EOL;
    }

    file_put_contents($target, $content);
    echo "✅ Created: $file" . PHP_EOL;
}

echo PHP_EOL;
echo "⚠️ IMPORTANT:" . PHP_EOL;
echo "Ensure Apache DirectoryIndex prioritizes index.php" . PHP_EOL;
echo PHP_EOL;
echo "DirectoryIndex index.php index.html index.htm" . PHP_EOL;
echo PHP_EOL;
echo "🌐 Open http://localhost/" . PHP_EOL;
