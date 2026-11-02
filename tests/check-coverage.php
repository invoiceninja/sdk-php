<?php

$report = $argv[1] ?? __DIR__ . '/../coverage/clover.xml';

if (!is_file($report)) {
    fwrite(STDERR, "Coverage report not found: {$report}\n");
    exit(1);
}

$coverage = simplexml_load_file($report);
$metrics = $coverage->project->metrics;
$statements = (int) $metrics['statements'];
$covered = (int) $metrics['coveredstatements'];

if ($statements === 0 || $covered !== $statements) {
    fwrite(STDERR, "Line coverage must be 100%: {$covered}/{$statements} statements covered.\n");
    exit(1);
}

echo "Line coverage: 100% ({$covered}/{$statements} statements).\n";
