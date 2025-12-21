<?php
require_once 'core/icons.php';

$isApp = $info['isApp'];
$pathPrefix = $location['path'] ? $location['path'] . '/' : '';
$href = $isApp
  ? '/' . $pathPrefix . $name . ($info['entryPoint'] ? '/' . $info['entryPoint'] : '')
  : '?path=' . urlencode($pathPrefix . $name);

$typeStyles = [
  TYPE_FRAMEWORK => [
    'gradient'  => 'from-red-800 to-orange-950', // Gradasi Border Atas
    'iconBg'    => 'bg-gradient-to-br from-red-500/20 via-orange-500/10 to-transparent',
    'iconInner' => 'text-red-600 dark:text-red-400',
    'arrow'     => 'text-red-500' // Warna Solid Arrow
  ],
  TYPE_CMS => [
    'gradient'  => 'from-blue-800 to-cyan-950',
    'iconBg'    => 'bg-gradient-to-tr from-cyan-600/20 via-blue-400/10 to-transparent',
    'iconInner' => 'text-blue-600 dark:text-blue-400',
    'arrow'     => 'text-blue-500'
  ],
  TYPE_APP => [
    'gradient'  => 'from-indigo-800 to-purple-950',
    'iconBg'    => 'bg-gradient-to-bl from-indigo-600/20 via-purple-500/10 to-transparent',
    'iconInner' => 'text-indigo-600 dark:text-indigo-400',
    'arrow'     => 'text-indigo-500'
  ],
  TYPE_STATIC => [
    'gradient'  => 'from-emerald-700 to-teal-950',
    'iconBg'    => 'bg-gradient-to-tr from-emerald-500/20 via-teal-400/10 to-transparent',
    'iconInner' => 'text-emerald-600 dark:text-teal-400',
    'arrow'     => 'text-emerald-500'
  ],
  TYPE_DIRECTORY => [
    'gradient'  => 'from-slate-800 to-slate-950',
    'iconBg'    => 'bg-gradient-to-br from-slate-400/20 via-slate-200/10 to-transparent',
    'iconInner' => 'text-slate-500 dark:text-slate-400',
    'arrow'     => 'text-slate-400'
  ],
];

$style = $typeStyles[$info['projectType']] ?? $typeStyles[TYPE_DIRECTORY];
?>

<a href="<?= $href ?>"
  x-show="(filterType === 'ALL' || filterType === '<?= $info['projectType'] ?>') && (searchQuery === '' || '<?= strtolower(addslashes($name)) ?>'.includes(searchQuery.toLowerCase()))"
  class="group relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all overflow-hidden">

  <div class="absolute top-0 left-0 right-0 h-[4px] bg-slate-200 dark:bg-slate-800 group-hover:bg-gradient-to-r <?= $style['gradient'] ?> transition-all duration-300"></div>

  <div class="flex items-start gap-4 mb-4">
    <div class="relative group-hover:scale-110 transition-all duration-500">
      <div class="absolute inset-0 blur-xl opacity-0 group-hover:opacity-40 transition-opacity duration-500 <?= $style['iconBg'] ?>"></div>

      <div class="relative p-4 rounded-xl overflow-hidden border border-slate-100 dark:border-slate-800 border-white/20 shadow-sm bg-slate-50 dark:bg-slate-800/50 <?= $style['iconBg'] ?> transition-all duration-300 backdrop-blur-sm">
        <div class="absolute -top-4 -left-4 w-12 h-12 bg-white/20 dark:bg-white/10 rotate-45 blur-md opacity-0 opacity-100 transition-opacity"></div>

        <div class="<?= $style['iconInner'] ?> transition-colors duration-300">
          <?= renderIcon($info['icon'], "w-7 h-7") ?>
        </div>
      </div>
    </div>

    <div class="flex flex-col gap-1 w-full overflow-hidden">
      <span class="w-fit px-2 py-0.5 rounded-md font-bold uppercase tracking-widest bg-slate-100 dark:bg-slate-800 text-[9px] text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300 transition-colors">
        <?= str_replace('_', ' ', $info['projectType']) ?>
      </span>
      <h3 class="text-xl font-bold text-slate-700 dark:text-slate-300 group-hover:text-slate-900 dark:group-hover:text-white truncate transition-colors">
        <?= htmlspecialchars($name) ?>
      </h3>
    </div>
  </div>

  <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
    <div class="flex items-center gap-1.5 overflow-hidden opacity-60 group-hover:opacity-100 transition-opacity">
      <i class="ph-bold ph-terminal-window text-slate-400 text-xs"></i>
      <span class="text-[10px] text-slate-400 font-mono truncate">
        <?= $info['entryPoint'] ?: 'root' ?>
      </span>
    </div>

    <i class="ph-bold ph-arrow-right <?= $style['arrow'] ?> opacity-0 group-hover:opacity-100 transition-all group-hover:translate-x-1 duration-300"></i>
  </div>
</a>
