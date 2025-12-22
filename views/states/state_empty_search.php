<div x-show="searchQuery !== '' && visibleCount === 0" x-transition.opacity class="relative">
  <div class="relative mb-6">
    <i class="ph-duotone ph-magnifying-glass text-8xl text-slate-200 dark:text-slate-700"></i>
  </div>
  <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">
    No Results for "<span x-text="searchQuery" class="text-indigo-500"></span>"
  </h3>
  <p class="text-slate-500 dark:text-slate-400 max-w-xs">
    We couldn't find any projects matching your search.
  </p>
  <button @click="searchQuery = ''" class="mt-6 px-6 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-full text-sm font-semibold hover:bg-indigo-100 transition-colors">
    Clear Search
  </button>
</div>
