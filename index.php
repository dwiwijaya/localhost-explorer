<?php

/**
 * ================================
 * Localhost Project Explorer
 * ================================
 *
 * A modern, responsive project browser for local development environments.
 * Features automatic framework detection, dark mode, and intuitive navigation.
 *
 * @version 2.0
 * @author Developer
 */

// ================================
// Configuration & Security
// ================================

$root = realpath(__DIR__);
$path = $_GET['path'] ?? '';
$currentDir = realpath($root . '/' . $path);

/**
 * Security: Prevent path traversal attacks
 * Ensures users can only access directories within the root
 */
if (!$currentDir || strpos($currentDir, $root) !== 0) {
  $currentDir = $root;
  $path = '';
}

/**
 * Calculate parent directory path for breadcrumb navigation
 */
$parentPath = '';
if ($path) {
  $parts = explode('/', trim($path, '/'));
  array_pop($parts);
  $parentPath = implode('/', $parts);
}

// ================================
// Framework Detection System
// ================================

/**
 * Detect project framework and entry point
 *
 * Scans the directory for framework-specific files and returns
 * metadata about the detected framework including entry point,
 * display label, and badge color.
 *
 * @param string $dir Directory path to scan
 * @return array|null Framework metadata or null if no framework detected
 */
function getProjectEntry(string $dir): ?array
{
  // Framework detection map: [marker_file, entry_dir, display_name, badge_class]
  $frameworkMap = [
    ['yii', 'web', 'Yii2', 'yii'],
    ['artisan', 'public', 'Laravel', 'laravel'],
    ['spark', 'public', 'CodeIgniter 4', 'ci'],
    ['bin/console', 'public', 'Symfony', 'symfony'],
    ['bin/cake', 'webroot', 'CakePHP', 'cake'],
  ];

  // Check for PHP frameworks
  foreach ($frameworkMap as [$markerFile, $entryDir, $label, $badge]) {
    if (file_exists("$dir/$markerFile") && is_dir("$dir/$entryDir")) {
      return compact('entryDir', 'label', 'badge');
    }
  }

  // Check for WordPress
  if (file_exists("$dir/wp-config.php")) {
    return ['entryDir' => '', 'label' => 'WordPress', 'badge' => 'wp'];
  }

  // Generic PHP project detection
  if (file_exists("$dir/index.php")) {
    return ['entryDir' => 'public', 'label' => 'PHP', 'badge' => 'php'];
  }

  if (file_exists("$dir/index.php")) {
    return ['entryDir' => '', 'label' => 'PHP', 'badge' => 'php'];
  }

  // JavaScript project detection
  if (file_exists("$dir/package.json")) {
    $buildDir = is_dir("$dir/dist") ? 'dist' : (is_dir("$dir/build") ? 'build' : null);
    return ['entryDir' => $buildDir, 'label' => 'Node.js', 'badge' => 'js'];
  }

  return null;
}

// ================================
// Directory Listing
// ================================

/**
 * Get list of folders in current directory
 * Filters out . and .. and returns only directories
 */
$folders = array_values(array_filter(
  scandir($currentDir),
  fn($f) => $f !== '.' && $f !== '..' && is_dir("$currentDir/$f")
));

/**
 * Convert PHP SAPI name to human-readable format
 *
 * @return string Human-friendly PHP execution mode
 */
function getPhpMode(): string
{
  $modes = [
    'apache2handler' => 'Apache Module',
    'fpm-fcgi' => 'PHP-FPM',
    'cgi-fcgi' => 'CGI',
    'cli' => 'CLI (Command Line)',
  ];

  return $modes[php_sapi_name()] ?? php_sapi_name();
}

// ================================
// System Information
// ================================

$systemInfo = [
  'PHP Version' => PHP_VERSION,
  'PHP Mode' => getPhpMode(),
  'Server' => explode(' ', $_SERVER['SERVER_SOFTWARE'] ?? 'CLI')[0],
  'Document Root' => $_SERVER['DOCUMENT_ROOT'] ?? '-',
  'Operating System' => PHP_OS_FAMILY,
  'Memory Limit' => ini_get('memory_limit'),
  'Max Execution' => ini_get('max_execution_time') . 's',
  'Upload Max Size' => ini_get('upload_max_filesize'),
  'Timezone' => date_default_timezone_get(),
  'Server Time' => time(),
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="/folders.png">
  <title>Localhost Explorer - <?= htmlspecialchars($path ?: 'Root') ?></title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Phosphor Icons -->
  <script src="https://unpkg.com/@phosphor-icons/web"></script>

  <!-- Alpine.js for interactivity -->
  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <script>
    // Tailwind configuration
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          fontFamily: {
            sans: ['Inter', 'system-ui', 'sans-serif'],
          },
        }
      }
    }
  </script>

  <style>
    /* Dark mode transitions */
    * {
      transition-property: background-color, border-color, color;
      transition-duration: 200ms;
    }
  </style>
</head>

<body
  class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-950 dark:to-slate-900 min-h-screen"
  x-data="{
    searchQuery: '',
    darkMode: localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches),
    init() {
      this.$watch('darkMode', val => {
        localStorage.setItem('theme', val ? 'dark' : 'light');
        document.documentElement.classList.toggle('dark', val);
      });
      document.documentElement.classList.toggle('dark', this.darkMode);
    }
  }">
  <div class="container mx-auto px-4 py-8 max-w-7xl">

    <!-- Header Section -->
    <div class="mb-8">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div class="flex-1">
          <div class="flex items-center gap-3 mb-2">
            <img src="/folders.png" width="56" />
            <div>
              <h1 class="text-3xl font-bold text-slate-900 dark:text-white">
                Localhost Explorer
              </h1>
              <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                Manage your local development projects
              </p>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-2">
          <button
            @click="darkMode = !darkMode"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-medium transition-colors shadow-sm">
            <i class="ph-duotone ph-sun" x-show="darkMode"></i>
            <i class="ph-duotone ph-moon" x-show="!darkMode"></i>
          </button>
        </div>
      </div>

      <!-- Breadcrumb -->
      <div class="flex items-center gap-3 w-full h-full">


        <a
          <?php if ($path): ?>
          href="?path=<?= urlencode($parentPath) ?>"
          <?php endif; ?>
          class="h-full inline-flex items-center gap-2 px-4 py-3 rounded-lg border
    transition-colors shadow-sm
    <?= $path
      ? 'text-slate-700 dark:text-slate-200 cursor-pointer'
      : 'text-slate-700 dark:text-slate-500 cursor-not-allowed'
    ?> bg-white dark:bg-slate-800
            border border-slate-200 dark:border-slate-700">
          <i class="ph-bold ph-arrow-left"></i>
        </a>

        <?php
        $segments = array_filter(explode('/', trim($path, '/')));
        $currentPath = '';
        ?>

        <div class="flex flex-1  items-center gap-2 text-sm bg-white dark:bg-slate-800
            border border-slate-200 dark:border-slate-700
            rounded-md px-3 py-2 shadow-sm">

          <!-- Folder icon -->
          <i class="ph-fill ph-folder text-yellow-500 text-base"></i>

          <!-- Breadcrumb -->
          <div class="flex items-center flex-wrap gap-1 text-slate-600 dark:text-slate-400">

            <!-- Root -->
            <a href="?path=" class="hover:text-indigo-600 dark:hover:text-indigo-400 font-medium">
              Root
            </a>

            <?php foreach ($segments as $seg): ?>
              <?php $currentPath .= '/' . $seg; ?>

              <i class="ph-bold ph-caret-right text-xs opacity-60"></i>

              <a href="?path=<?= urlencode(trim($currentPath, '/')) ?>"
                class="hover:text-indigo-600 dark:hover:text-indigo-400 font-medium">
                <?= htmlspecialchars($seg) ?>
              </a>
            <?php endforeach; ?>

          </div>
        </div>

      </div>
    </div>

    <!-- System Information Cards -->
    <div class="mb-8">
      <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
        <i class="ph-duotone ph-info text-red-500 dark:text-red-300"></i>
        System Information
      </h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
        <?php foreach ($systemInfo as $label => $value): ?>
          <div class="bg-white dark:bg-slate-800 rounded-lg p-4 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md transition-shadow">
            <div class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1">
              <?= htmlspecialchars($label) ?>
            </div>

            <?php if ($label === 'Server Time'): ?>
              <div
                x-data="{ t: <?= (int) $value ?> }"
                x-init="setInterval(() => t++, 1000)"
                class="text-sm font-semibold text-slate-900 dark:text-white"
                x-text="new Date(t * 1000).toLocaleString()">
              </div>
            <?php else: ?>
              <div class="text-sm font-semibold text-slate-900 dark:text-white break-all">
                <?= htmlspecialchars($value) ?>
              </div>
            <?php endif; ?>

          </div>
        <?php endforeach; ?>

      </div>
    </div>

    <!-- Search Bar -->
    <div class="mb-6">
      <div class="relative">
        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl"></i>
        <input
          type="text"
          x-model="searchQuery"
          placeholder="Search projects by name..."
          class="w-full pl-12 pr-4 py-3.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent shadow-sm">
      </div>
    </div>

    <!-- Projects Grid -->
    <div x-data="{
    get visibleCount() {
        return Array.from($el.querySelectorAll('.project-card')).filter(el => el.style.display !== 'none').length
    }
}">
      <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
        <i class="ph-duotone ph-folders text-yellow-500"></i>
        Projects (<?= count($folders) ?>)
      </h2>

      <?php if (empty($folders)): ?>
        <div
          class="flex flex-col items-center justify-center py-20 px-4 text-center
          bg-white/60 dark:bg-slate-800/60
          rounded-2xl border-2 border-dashed
          border-slate-200 dark:border-slate-700">

          <!-- Icon -->
          <div class="relative mb-6">
            <i class="ph-duotone ph-folder-open text-8xl text-slate-200 dark:text-slate-700"></i>
            <i class="ph-bold ph-plus-circle absolute -bottom-1 -right-1 text-3xl text-indigo-500"></i>
          </div>

          <!-- Title -->
          <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">
            No Projects Yet
          </h3>

          <!-- Description -->
          <p class="text-slate-500 dark:text-slate-400 max-w-sm mx-auto">
            This directory doesn’t contain any projects.
            Add a new folder or upload files to get started.
          </p>

          <!-- Hint -->
          <div class="mt-6 text-sm text-slate-400 dark:text-slate-500 flex items-center gap-2">
            <i class="ph-bold ph-info"></i>
            <span>Tip: Each folder will be treated as a project</span>
          </div>

        </div>

      <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
          <?php foreach ($folders as $folder): ?>
            <?php
            // ... (tetap gunakan logika PHP yang sama untuk $project, $link, $badgeColor) ...
            $project = getProjectEntry("$currentDir/$folder");
            $url = trim($path . '/' . $folder, '/');
            $link = '?path=' . urlencode($url);
            if ($project && isset($project['entryDir']) && $project['entryDir'] !== null) {
              $link = '/' . $url . ($project['entryDir'] ? '/' . $project['entryDir'] : '');
            }
            $badgeColors = ['laravel' => 'bg-red-500', 'yii' => 'bg-blue-500', 'ci' => 'bg-green-500', 'symfony' => 'bg-gray-900', 'cake' => 'bg-pink-600', 'wp' => 'bg-cyan-600', 'php' => 'bg-indigo-500', 'js' => 'bg-yellow-400 text-gray-900'];
            $badgeColor = $badgeColors[$project['badge'] ?? ''] ?? 'bg-slate-500';
            ?>

            <a
              href="<?= htmlspecialchars($link) ?>"
              class="project-card group relative bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700 hover:border-indigo-300 dark:hover:border-indigo-600 shadow-sm hover:shadow-xl transition-all duration-200 hover:-translate-y-1"
              x-data="{ folderName: '<?= strtolower(addslashes($folder)) ?>' }"
              x-show="folderName.includes(searchQuery.toLowerCase())"
              x-transition:enter="transition ease-out duration-200"
              x-transition:enter-start="opacity-0 scale-95"
              x-transition:enter-end="opacity-100 scale-100">

              <?php if ($project): ?>
                <div class="absolute top-4 right-4 px-2.5 py-1 <?= $badgeColor ?> text-white text-xs font-bold rounded-md shadow-sm">
                  <?= htmlspecialchars($project['label']) ?>
                </div>
              <?php endif; ?>

              <div class="mb-4 inline-flex items-center justify-center w-14 h-14 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30 rounded-xl group-hover:scale-110 transition-transform">
                <i class="ph-fill ph-folder text-3xl text-indigo-600 dark:text-indigo-400"></i>
              </div>

              <h3 class="text-base font-semibold text-slate-900 dark:text-white mb-2 truncate pr-16">
                <?= htmlspecialchars($folder) ?>
              </h3>

              <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                <i class="ph-bold ph-<?= $project ? 'check-circle' : 'folder' ?> <?= $project ? 'text-green-500' : 'text-slate-400' ?>"></i>
                <span><?= $project ? 'Framework Detected' : 'Standard Folder' ?></span>
              </div>
            </a>
          <?php endforeach; ?>
        </div>

        <div
          x-show="searchQuery !== '' && visibleCount === 0"
          x-transition.opacity
          class="flex flex-col items-center justify-center py-20 px-4 text-center bg-white/50 dark:bg-slate-800/50 rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-700 mt-4">
          <div class="relative mb-6">
            <i class="ph-duotone ph-magnifying-glass text-8xl text-slate-200 dark:text-slate-700"></i>
            <i class="ph-bold ph-question absolute bottom-2 right-2 text-3xl text-indigo-500 animate-bounce"></i>
          </div>
          <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">
            Project "<span x-text="searchQuery" class="text-indigo-500"></span>" Not Found
          </h3>
          <p class="text-slate-500 dark:text-slate-400 max-w-xs mx-auto">
            We couldn't find any folder matching your search. Try checking for typos or use a different keyword.
          </p>
          <button
            @click="searchQuery = ''"
            class="mt-6 px-6 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-full text-sm font-semibold hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors">
            Clear Search
          </button>
        </div>
      <?php endif; ?>
    </div>

    <!-- Footer -->
    <div class="mt-12 pt-8 border-t border-slate-200 dark:border-slate-700">
      <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-slate-600 dark:text-slate-400">
        <div class="flex items-center gap-2">
          <i class="ph-bold ph-code text-indigo-500"></i>
          <span>Localhost Explorer v2.0</span>
        </div>
        <div class="flex items-center gap-4">
          <span class="flex items-center gap-2">
            <i class="ph-fill ph-circle text-green-500 animate-pulse"></i>
            Server Running
          </span>
          <span>•</span>
          <span><?= count($folders) ?> project<?= count($folders) !== 1 ? 's' : '' ?> found</span>
        </div>
      </div>
    </div>

  </div>
</body>

</html>
