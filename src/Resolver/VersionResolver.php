<?php

namespace WP_Since\Resolver;

class VersionResolver
{
    public static function resolve(string $pluginPath): ?array
    {
        $pluginFile = self::findMainPluginFile($pluginPath);

        if ($pluginFile) {
            $version = self::extractVersionFromPluginHeader($pluginFile);
            if ($version) {
                return ['version' => $version, 'source' => 'main plugin file header'];
            }
        }

        $readme = self::findReadmeFile($pluginPath);
        if ($readme) {
            $version = self::extractVersionFromReadme($readme);
            if ($version) {
                return ['version' => $version, 'source' => 'readme'];
            }
        }

        return null;
    }

    private static function findMainPluginFile(string $pluginPath): ?string
    {
        $basename = basename($pluginPath);
        $candidate = "{$pluginPath}/{$basename}.php";
        if (file_exists($candidate)) {
            return $candidate;
        }

        return null;
    }

    private static function extractVersionFromPluginHeader(string $file): ?string
    {
        $contents = file_get_contents($file);
        if (!$contents) {
            return null;
        }

        if (preg_match('/^\s*\*\s+Requires at least:\s*([0-9.]+)/mi', $contents, $matches)) {
            return $matches[1];
        }

        return null;
    }

    private static function findReadmeFile(string $pluginPath): ?string
    {
        foreach (['readme.txt', 'README.txt'] as $filename) {
            $full = "{$pluginPath}/{$filename}";
            if (file_exists($full)) {
                return $full;
            }
        }
        return null;
    }

    private static function extractVersionFromReadme(string $file): ?string
    {
        $lines = file($file);
        foreach ($lines as $line) {
            if (stripos($line, 'Requires at least:') === 0) {
                if (preg_match('/Requires at least:\s*([0-9.]+)/i', $line, $matches)) {
                    return $matches[1];
                }
            }
        }
        return null;
    }
}
