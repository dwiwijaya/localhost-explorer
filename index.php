<?php
require_once 'core/config.php';
require_once 'core/detector.php';
require_once 'core/explorer.php';
require_once 'core/helper.php';

$location = getScanPath();
$folders = getFolders($location['abs']);
?>

<script>
    function setupTheme() {
        return {
            searchQuery: '',
            visibleCount: 0,
            filterType: 'ALL',
            dark: localStorage.theme === 'dark' ||
                (!localStorage.theme && window.matchMedia('(prefers-color-scheme: dark)').matches),

            toggleTheme() {
                this.dark = !this.dark;
            },

            init() {
                // apply theme immediately
                document.documentElement.classList.toggle('dark', this.dark);

                // watch for changes
                this.$watch('dark', val => {
                    localStorage.theme = val ? 'dark' : 'light';
                    document.documentElement.classList.toggle('dark', val);
                });
            }
        }
    }
</script>

<!DOCTYPE html>
<html lang="en" x-data="setupTheme()">

<head>
    <?php include 'views/layouts/meta.php'; ?>
</head>

<body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100">

    <div class="container mx-auto px-6 py-10 max-w-7xl">
        <?php include 'views/layouts/header.php'; ?>

        <?php include 'views/components/breadcrumb.php'; ?>

        <?php include 'views/components/system_info.php'; ?>

        <main class="mt-10">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
                <div>
                    <h2 class="text-xl font-bold flex items-center gap-2 mb-4">
                        <i class="ph-duotone ph-stack text-indigo-500"></i>
                        Project Assets <span class="text-sm font-normal opacity-50">(<?= count($folders) ?> Items)</span>
                    </h2>
                    <div class="flex p-1 bg-slate-200/50 dark:bg-slate-800/50 rounded-xl w-fit">
                        <?php
                        $types = ['ALL', TYPE_FRAMEWORK, TYPE_CMS, TYPE_APP, TYPE_DIRECTORY];
                        foreach ($types as $type): ?>
                            <button
                                @click="filterType = '<?= $type ?>'"
                                :class="filterType === '<?= $type ?>' ? 'bg-white dark:bg-slate-700 shadow-sm text-indigo-600 dark:text-white' : 'text-slate-500 hover:text-slate-400'"
                                class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all uppercase tracking-wider">
                                <?= $type === 'ALL' ? 'All' : str_replace('_', ' ', $type) ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="relative w-full md:w-72">
                    <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input
                        <?php if (count($folders) === 0) echo 'disabled'; ?>
                        type="text" x-model="searchQuery" placeholder="Search projects..."
                        class="w-full pl-10 pr-4 py-2 disabled:cursor-not-allowed disabled:opacity-50 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm"
                        >
                </div>
            </div>

            <div x-data="{
                    visibleCount: <?= count($folders) ?>,
                    updateCount() {
                        this.$nextTick(() => {
                            this.visibleCount = Array.from($el.querySelectorAll('.project-card')).filter(el => el.style.display !== 'none').length
                        })
                    }
                }"
                x-init="$watch('searchQuery', () => updateCount()); $watch('filterType', () => updateCount())">
            </div>

            <?php if (empty($folders)): ?>
                <?php include 'views/states/state_empty_folder.php'; ?>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
                    <?php foreach ($folders as $name):
                        $info = detectProject($location['abs'] . '/' . $name);
                        include 'views/components/project_card.php';
                    endforeach; ?>
                </div>

                <?php include 'views/states/state_empty_search.php'; ?>
            <?php endif; ?>
        </main>
        <?php include 'views/layouts/footer.php'; ?>
    </div>
</body>

</html>
