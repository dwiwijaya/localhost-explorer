<?php $stats = getSystemStats(); ?>

<section class="mb-10">
    <h2 class="text-xl font-bold flex items-center gap-2 mb-6">
        <i class="ph-duotone ph-info text-indigo-500"></i>
        System Information
    </h2>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">

        <?php foreach ($stats as $label => $value): ?>
            <div class="bg-white dark:bg-slate-900 p-4 border border-slate-100 dark:border-slate-800 rounded-xl shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">
                    <?= $label ?>
                </p>

                <?php if ($label === 'PHP Info'): ?>
                    <a href="?phpinfo=1" class="text-indigo-500 hover:underline">View</a>
                <?php else: ?>
                    <p class="text-sm font-bold text-slate-700 dark:text-slate-200">
                        <?= htmlspecialchars($value) ?>
                    </p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>