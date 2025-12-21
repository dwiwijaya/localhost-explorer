<div x-show="searchQuery !== '' && visibleCount === 0" x-transition.opacity
  class="flex flex-col items-center justify-center py-20 px-4 text-center bg-white dark:bg-slate-900 rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-700 mt-4">
  <div class="relative mb-6">
    <i class="ph-duotone ph-magnifying-glass text-8xl text-slate-200 dark:text-slate-700"></i>
    <i class="ph-bold ph-question absolute bottom-2 right-2 text-3xl text-indigo-500 animate-bounce"></i>
  </div>
  <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">
    No Results for "<span x-text="searchQuery" class="text-indigo-500"></span>"
  </h3>
  <p class="text-slate-500 dark:text-slate-400 max-w-xs mx-auto">
    We couldn't find any projects matching your search.
  </p>
  <button @click="searchQuery = ''" class="mt-6 px-6 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-full text-sm font-semibold hover:bg-indigo-100 transition-colors">
    Clear Search
  </button>
</div>
