<?php

namespace WP_Since\Runner;

use WP_Since\Parser\ReadmeParser;
use WP_Since\Scanner\PluginScanner;
use WP_Since\Checker\CompatibilityChecker;
use WP_Since\Utils\TablePrinter;
use WP_Since\Utils\VersionHelper;

class PluginCheckCommand
{
    public static function run(string $pluginPath, string $sinceMapPath): int
    {
        if (!file_exists($sinceMapPath)) {
            echo "❌ wp-since.json not found. Run composer generate-since first.\n";
            return 1;
        }

        $readmePath = null;
        if (file_exists($pluginPath . '/readme.txt')) {
            $readmePath = $pluginPath . '/readme.txt';
        } elseif (file_exists($pluginPath . '/README.txt')) {
            $readmePath = $pluginPath . '/README.txt';
        }

        echo "🔍 Scanning plugin files...\n";

        if (!$readmePath) {
            echo "❌ No readme.txt found in $pluginPath\n";
            return 1;
        }

        $declaredVersion = ReadmeParser::getMinRequiredVersion($readmePath);
        if (!$declaredVersion) {
            echo "⚠️  Minimum version not declared in readme.txt\n";
            return 1;
        }

        echo "✅ Found readme.txt → Minimum version declared: {$declaredVersion}\n\n";

        $usedSymbols = PluginScanner::scan($pluginPath);
        $sinceMap = json_decode(file_get_contents($sinceMapPath), true);

        $checker = new CompatibilityChecker($sinceMap);
        $incompatible = $checker->check($usedSymbols, $declaredVersion);

        if (count($incompatible)) {
            echo "🚨 Compatibility issues found:\n\n";
            $rows = [];
            foreach ($incompatible as $symbol => $version) {
                $rows[] = [$symbol, $version];
            }
            TablePrinter::render($rows, ['Symbol', 'Introduced in WP']);

            $versions = array_values($incompatible);
            $maxVersion = array_reduce($versions, function ($carry, $v) {
                return VersionHelper::compare($carry, $v) < 0 ? $v : $carry;
            }, $declaredVersion);

            echo "📌 Suggested version required:  {$maxVersion}\n";
            return 1;
        }

        echo "✅ All good! Your plugin is compatible with WP {$declaredVersion}.\n";
        return 0;
    }
}
