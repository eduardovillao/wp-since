<?php

namespace WP_Since\Runner;

use WP_Since\Resolver\VersionResolver;
use WP_Since\Scanner\PluginScanner;
use WP_Since\Checker\CompatibilityChecker;
use WP_Since\Utils\TablePrinter;
use WP_Since\Utils\VersionHelper;

class PluginCheckCommand
{
    public static function run(string $pluginPath, string $sinceMapPath, ?string $minWpVersion = null): int
    {
        if (!file_exists($sinceMapPath)) {
            echo "❌ wp-since.json not found. Run composer generate-since first.\n";
            return 1;
        }

        if (! $minWpVersion) {
            $versionResolver = VersionResolver::resolve($pluginPath);
            if (!$versionResolver || !$versionResolver['version']) {
                echo "❌ Could not determine the minimum required WP version.\n";
                return 1;
            }
        }

        $source          = $minWpVersion ? 'argument' : $versionResolver['source'] ?? '';
        $declaredVersion = $minWpVersion ?? $versionResolver['version'];

        echo "✅ Minimum version declared: {$declaredVersion} (from {$source})\n\n";

        $usedSymbols = PluginScanner::scan($pluginPath);
        $sinceMap = json_decode(file_get_contents($sinceMapPath), true);

        $checker = new CompatibilityChecker($sinceMap);
        $incompatible = $checker->check($usedSymbols, $declaredVersion);

        if (count($incompatible)) {
            echo "🚨 Compatibility issues found:\n\n";
            $rows = [];
            foreach ($incompatible as $symbol => $version) {
                if (strpos($symbol, ':') !== false) {
                    [$type, $name] = explode(':', $symbol, 2);
                    $rows[] = ["$name ($type)", $version];
                } else {
                    $rows[] = [$symbol, $version];
                }
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
