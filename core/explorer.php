<?php
function getScanPath() {
    $root = realpath($_SERVER['DOCUMENT_ROOT']);
    $requestedPath = $_GET['path'] ?? '';
    $fullPath = realpath($root . '/' . $requestedPath);

    // Security: Prevents Traversal
    if (!$fullPath || strpos($fullPath, $root) !== 0) {
        return ['path' => '', 'abs' => $root];
    }
    return ['path' => $requestedPath, 'abs' => $fullPath];
}

function getFolders(string $dir) {
    if (!is_dir($dir)) return [];
    $items = scandir($dir);
    return array_values(array_filter($items, function($item) use ($dir) {
        return is_dir("$dir/$item") && !in_array($item, EXCLUDE_DIRS);
    }));
}
