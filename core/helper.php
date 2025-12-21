<?php

function getPhpMode(): string
{
  $modes = [
    'apache2handler' => 'Apache Module',
    'fpm-fcgi' => 'PHP-FPM',
    'cgi-fcgi' => 'CGI',
    'cli' => 'CLI',
  ];
  return $modes[php_sapi_name()] ?? php_sapi_name();
}

function getSystemStats(): array
{
  return [
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
}
