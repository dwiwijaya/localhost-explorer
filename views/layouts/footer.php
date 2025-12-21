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
