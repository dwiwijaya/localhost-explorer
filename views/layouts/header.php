<header class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-8">
    <div class="flex items-center gap-4">
            <img src="../../folders.png" width="56" />

        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                <?= APP_NAME ?>
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Easily Manage Your Local Projects
            </p>
        </div>
    </div>

    <button @click="toggleTheme()"
        class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-all shadow-sm">
        <i :class="dark ? 'ph-duotone ph-sun text-yellow-500' : 'ph-duotone ph-moon text-indigo-500'" class="text-lg"></i>
        <span class="text-sm font-semibold" x-text="dark ? 'Light Mode' : 'Dark Mode'"></span>
    </button>
</header>
