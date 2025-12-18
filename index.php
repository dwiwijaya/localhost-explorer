<?php
$root = realpath(__DIR__);
$path = $_GET['path'] ?? '';
$currentDir = realpath($root . '/' . $path);

/* ===============================
 * SECURITY: cegah path traversal
 * =============================== */
if (!$currentDir || !str_starts_with($currentDir, $root)) {
    $currentDir = $root;
    $path = '';
}

/* ===============================
 * Deteksi framework & entry point
 * =============================== */
function getProjectEntry(string $dir): ?array
{
    // Yii2
    if (file_exists("$dir/yii") && is_dir("$dir/web")) {
        return ['entry' => 'web', 'label' => 'Yii2', 'badge' => 'yii'];
    }

    // Laravel
    if (file_exists("$dir/artisan") && is_dir("$dir/public")) {
        return ['entry' => 'public', 'label' => 'Laravel', 'badge' => 'laravel'];
    }

    // CodeIgniter 4
    if (file_exists("$dir/spark") && is_dir("$dir/public")) {
        return ['entry' => 'public', 'label' => 'CI4', 'badge' => 'ci'];
    }

    // Symfony
    if (file_exists("$dir/bin/console") && is_dir("$dir/public")) {
        return ['entry' => 'public', 'label' => 'Symfony', 'badge' => 'symfony'];
    }

    // CakePHP
    if (file_exists("$dir/bin/cake") && is_dir("$dir/webroot")) {
        return ['entry' => 'webroot', 'label' => 'CakePHP', 'badge' => 'cake'];
    }

    // WordPress
    if (file_exists("$dir/wp-config.php")) {
        return ['entry' => '', 'label' => 'WordPress', 'badge' => 'wp'];
    }

    // Generic PHP (public/)
    if (is_dir("$dir/public")) {
        return ['entry' => 'public', 'label' => 'PHP', 'badge' => 'php'];
    }

    // Plain PHP
    if (file_exists("$dir/index.php")) {
        return ['entry' => '', 'label' => 'PHP', 'badge' => 'php'];
    }

    // JS Project (deteksi package.json)
    if (file_exists("$dir/package.json")) {
        if (is_dir("$dir/dist")) {
            return ['entry' => 'dist', 'label' => 'JS (build)', 'badge' => 'js'];
        }
        if (is_dir("$dir/build")) {
            return ['entry' => 'build', 'label' => 'JS (build)', 'badge' => 'js'];
        }
        return ['entry' => null, 'label' => 'JS (dev)', 'badge' => 'js-dev'];
    }

    return null;
}

/* ===============================
 * Ambil folder
 * =============================== */
$folders = array_values(array_filter(scandir($currentDir), function ($item) use ($currentDir) {
    return $item !== '.' && $item !== '..' && is_dir("$currentDir/$item");
}));
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Localhost Explorer</title>

<style>
* { box-sizing: border-box; font-family: system-ui, sans-serif; }
body { background:#f4f6fb; padding:40px; }
.container { max-width:1100px; margin:auto; }

h1 { margin-bottom:4px; }
.path { color:#666; margin-bottom:24px; }

.grid {
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
    gap:20px;
}

.card {
    background:#fff;
    padding:20px;
    border-radius:14px;
    text-decoration:none;
    color:#333;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
    transition:.2s;
    position:relative;
}
.card:hover { transform:translateY(-5px); }

.icon { font-size:32px; margin-bottom:10px; }
.name { font-weight:600; }

.badge {
    position:absolute;
    top:14px;
    right:14px;
    font-size:12px;
    padding:4px 8px;
    border-radius:8px;
    color:#fff;
}

/* Badge colors */
.badge.yii { background:#40a9ff; }
.badge.laravel { background:#ff4d4f; }
.badge.ci { background:#20c997; }
.badge.symfony { background:#000; }
.badge.cake { background:#d63384; }
.badge.wp { background:#21759b; }
.badge.php { background:#777bb4; }
.badge.js { background:#f7df1e; color:#000; }
.badge.js-dev { background:#ff9800; }
</style>
</head>

<body>
<div class="container">
    <h1>Localhost Projects</h1>
    <p class="path">/<?= htmlspecialchars($path ?: '') ?></p>

    <div class="grid">
    <?php foreach ($folders as $folder): ?>
        <?php
        $fullPath = "$currentDir/$folder";
        $project = getProjectEntry($fullPath);

        $urlPath = trim($path . '/' . $folder, '/');
        $link = '?path=' . urlencode($urlPath);

        if ($project && $project['entry'] !== null) {
            $entryPath = trim($project['entry'], '/');
            $link = $urlPath . ($entryPath ? '/' . $entryPath : '');
        }
        ?>
        <a href="<?= htmlspecialchars($link) ?>" class="card">
            <div class="icon">📁</div>
            <div class="name"><?= htmlspecialchars($folder) ?></div>

            <?php if ($project): ?>
                <span class="badge <?= $project['badge'] ?>">
                    <?= htmlspecialchars($project['label']) ?>
                </span>
            <?php endif; ?>
        </a>
    <?php endforeach; ?>
    </div>
</div>
</body>
</html>
