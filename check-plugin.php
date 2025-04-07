<?php

require_once __DIR__ . '/vendor/autoload.php';

use WP_Since\Parser\ReadmeParser;
use WP_Since\Scanner\PluginScanner;
use WP_Since\Checker\CompatibilityChecker;
use WP_Since\Utils\TablePrinter;

$pluginPath = $argv[1] ?? getcwd();
$jsonPath = __DIR__ . '/wp-since.json';
$readmePath = file_exists($pluginPath . '/readme.txt') ? $pluginPath . '/readme.txt' : $pluginPath . '/README.txt';

echo "🔍 Scanning plugin files...\n";

if (!file_exists($readmePath)) {
    echo "❌ readme.txt not found in: $pluginPath\n";
    exit(1);
}

$declaredVersion = ReadmeParser::getMinRequiredVersion($readmePath);
if (!$declaredVersion) {
    echo "⚠️  Could not find minimum version in readme.txt\n";
    exit(1);
}

echo "✅ Found readme.txt → Minimum version declared: {$declaredVersion}\n\n";

if (!file_exists($jsonPath)) {
    echo "❌ wp-since.json not found. Please run generate-since-json.php first.\n";
    exit(1);
}
$sinceMap = json_decode(file_get_contents($jsonPath), true);

$scanner = new PluginScanner();
$symbols = $scanner->scan($pluginPath);

// Verifica compatibilidade
$checker = new CompatibilityChecker($sinceMap);
$incompatible = $checker->check($symbols, $declaredVersion);

if (empty($incompatible)) {
    echo "✅ No compatibility issues found.\n";
    echo "🎉 Plugin is fully compatible with declared version.\n";
    exit(0);
}

echo "🚨 Compatibility issues found:\n\n";
$rows = [];
foreach ($incompatible as $symbol => $version) {
    $rows[] = [$symbol, $version];
}

TablePrinter::render($rows, ['Symbol', 'Introduced in WP']);

$recommended = min($incompatible);
echo "📌 Suggested version required:  {$recommended}\n";

exit(1);