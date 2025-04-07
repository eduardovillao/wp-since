<?php

require_once __DIR__ . '/vendor/autoload.php';

use WP_Since\Parser\ReadmeParser;

$pluginPath = $argv[1] ?? getcwd();
$jsonPath = __DIR__ . '/wp-since.json';

$readmePath = null;
if (file_exists($pluginPath . '/readme.txt')) {
    $readmePath = $pluginPath . '/readme.txt';
} elseif (file_exists($pluginPath . '/README.txt')) {
    $readmePath = $pluginPath . '/README.txt';
}

if (!$readmePath) {
    echo "❌ Arquivo readme.txt ou README.txt não encontrado em $pluginPath\n";
    exit(1);
}

if (!file_exists($jsonPath)) {
    echo "❌ Arquivo wp-since.json não encontrado. Gere com generate-since-json.php primeiro.\n";
    exit(1);
}

try {
    $declaredVersion = ReadmeParser::getMinRequiredVersion($readmePath);
    if (!$declaredVersion) {
        echo "⚠️ Versão mínima do WP não encontrada no readme.txt\n";
        exit(1);
    }

    echo "✅ Versão mínima declarada no readme: $declaredVersion\n";

    // Futuro: analisar código e comparar com o JSON
    // $usedSymbols = UsageScanner::analyze($pluginPath);
    // CompatChecker::compare($usedSymbols, $jsonPath, $declaredVersion);

} catch (Throwable $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
    exit(1);
}