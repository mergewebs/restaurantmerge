<?php

// Dependency-free coverage of the Blade wiring; run: php tests/check-auto-filter-wiring.php
$views = dirname(__DIR__).'/resources/views/';
$expected = [
    'cash-register-filters' => ['date'],
    'demo-access-filters' => ['search', 'date_from', 'date_to', 'device', 'browser', 'country', 'status'],
    'orders-filters' => ['status', 'search', 'type'],
    'finance-history-filters' => ['query', 'date_from', 'date_to'],
    'filterForm' => ['mode', 'date_from', 'date_to'],
    'finance-range-filters' => ['history_query', 'mode', 'date_from', 'date_to'],
    'finance-search-filters' => ['mode', 'date_from', 'date_to', 'history_query'],
];
$found = [];
$failures = [];
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($views));
foreach ($files as $file) {
    if (!$file->isFile() || !str_ends_with($file->getFilename(), '.blade.php')) continue;
    $source = file_get_contents($file->getPathname());
    preg_match_all('/(<form\b[^>]*\bmethod=["\']GET["\'][^>]*>)(.*?)<\/form>/si', $source, $forms, PREG_SET_ORDER);
    foreach ($forms as [, $tag, $body]) {
        preg_match('/\bid="([^"]+)"/', $tag, $match);
        $id = $match[1] ?? '';
        if (!str_contains($tag, 'data-auto-submit-form')) $failures[] = $file->getFilename().': GET form is not automatic';
        if (!$id || isset($found[$id])) $failures[] = $file->getFilename().': filter ID is missing or duplicated';
        preg_match_all('/\bname="([^"]+)"/', $body, $names);
        $actual = $names[1];
        $wanted = $expected[$id] ?? [];
        sort($actual);
        sort($wanted);
        if ($actual !== $wanted) $failures[] = $id.': filter names were removed, duplicated, or changed';
        $found[$id] = true;
    }
    if (preg_match('/<form\b[^>]*\bmethod=["\']POST["\'][^>]*data-auto-submit-form/i', $source) ||
        preg_match('/<form\b[^>]*data-auto-submit-form[^>]*\bmethod=["\']POST["\']/i', $source)) {
        $failures[] = $file->getFilename().': mutation form was opted into automatic filtering';
    }
}
foreach (array_keys($expected) as $id) {
    if (!isset($found[$id])) $failures[] = $id.': filter form missing';
}
if ($failures) {
    fwrite(STDERR, implode(PHP_EOL, $failures).PHP_EOL);
    exit(1);
}
echo count($found)." filter forms verified: automatic GET-only wiring, unique IDs, and unchanged filter names.\n";
