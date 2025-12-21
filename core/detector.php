<?php
require_once __DIR__ . '/config.php';

function detectProject(string $dirPath): array
{
    $frameworks = [

        [
            'name' => 'Laravel',
            'marker' => 'artisan',
            'entry' => 'public',
            'type' => TYPE_FRAMEWORK,
            'icon' => 'laravel'
        ],
        [
            'name' => 'CodeIgniter',
            'marker' => 'spark',
            'entry' => 'public',
            'type' => TYPE_FRAMEWORK,
            'icon' => 'ci'
        ],
        [
            'name' => 'Yii2',
            'marker' => 'yii',
            'entry' => 'web',
            'type' => TYPE_FRAMEWORK,
            'icon' => 'yii'
        ],

        [
            'name' => 'Symfony',
            'marker' => 'bin/console',
            'entry' => 'public',
            'type' => TYPE_FRAMEWORK,
            'icon' => 'symfony'
        ],

        [
            'name' => 'Cake',
            'marker' => 'bin/cake',
            'entry' => 'webroot',
            'type' => TYPE_FRAMEWORK,
            'icon' => 'cake'
        ],

    ];

    // 1. Check Frameworks
    foreach ($frameworks as $fw) {
        if (file_exists("$dirPath/{$fw['marker']}") && is_dir("$dirPath/{$fw['entry']}")) {
            return [
                'projectType' => $fw['type'],
                'label'  => $fw['name'],
                'entryPoint'  => $fw['entry'],
                'icon'   => $fw['icon'],
                'isApp'  => true
            ];
        }
    }

    // 2. Check Wordpress
    if (file_exists("$dirPath/wp-config.php") && file_exists("$dirPath/index.php")) {
        return [
            'projectType' => TYPE_CMS,
            'label'  => 'Wordpress',
            'entryPoint'  => 'index.php',
            'icon'   => 'wp',
            'isApp'  => true
        ];
    }

    // 2. Check Native PHP
    if (file_exists("$dirPath/index.php")) {
        return [
            'projectType' => TYPE_APP,
            'label'  => 'PHP Native',
            'entryPoint'  => 'index.php',
            'icon'   => 'php',
            'isApp'  => true
        ];
    }

    // 3. Check Static HTML
    if (file_exists("$dirPath/index.html")) {
        return [
            'projectType' => TYPE_STATIC,
            'label'  => 'Static HTML',
            'entryPoint'  => 'index.html',
            'icon'   => 'html',
            'isApp'  => true
        ];
    }

    // 5. Check Node.js
    if (file_exists("$dirPath/package.json")) {
        $buildDir = is_dir("$dirPath/dist") ? 'dist' : (is_dir("$dirPath/build") ? 'build' : null);
        return [
            'projectType' => TYPE_APP,
            'entryPoint' => $buildDir,
            'label' => 'Node.js',
            'badge' => 'js',
            'icon' =>'js',
            'isApp' => true
        ];
    }

    // 4. Default: Just a Directory
    return [
        'projectType' => TYPE_DIRECTORY,
        'label'  => 'Folder',
        'entryPoint'  => null,
        'icon'   => 'folder',
        'isApp'  => false
    ];
}
