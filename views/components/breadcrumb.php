<?php
$segments = array_filter(explode('/', trim($location['path'], '/')));
$parentPath = '';
if (!empty($segments)) {
    $tempSegments = $segments;
    array_pop($tempSegments);
    $parentPath = implode('/', $tempSegments);
}
?>

<nav class="flex items-center gap-3 mb-8">
    <a href="?path=<?= urlencode($parentPath) ?>"
        class="px-3 py-3 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:text-indigo-500 transition-colors <?= empty($location['path']) ? 'pointer-events-none opacity-70' : '' ?>">
        <i class="ph-bold ph-arrow-left"></i>
    </a>

    <div class="flex-1 flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg overflow-x-auto no-scrollbar">
        <i class="ph-fill ph-house text-indigo-500"></i>
        <a href="?path=" class="text-sm font-medium hover:text-indigo-500 transition-colors">Root</a>

        <?php
        $currentBuildPath = '';
        foreach ($segments as $seg):
            $currentBuildPath .= ($currentBuildPath ? '/' : '') . $seg;
        ?>
            <i class="ph-bold ph-caret-right text-[10px] text-slate-300"></i>
            <a href="?path=<?= urlencode($currentBuildPath) ?>" class="text-sm font-medium hover:text-indigo-500 transition-colors whitespace-nowrap">
                <?= htmlspecialchars($seg) ?>
            </a>
        <?php endforeach; ?>
    </div>
</nav>
