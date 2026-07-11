<?php

namespace WP_Since\Resolver;

class VersionResolver
{
    private const HEADER_BYTES = 8192;

    public static function resolve(string $pluginPath): ?array
    {
        $pluginFile = self::findMainPluginFile($pluginPath);
        if ($pluginFile) {
            $version = self::extractRequiresAtLeast(self::readFileHeader($pluginFile));
            if ($version) {
                return ['version' => $version, 'source' => 'main plugin file header'];
            }
        }

        $readme = self::findReadmeFile($pluginPath);
        if ($readme) {
            $version = self::extractRequiresAtLeast(self::readFileHeader($readme));
            if ($version) {
                return ['version' => $version, 'source' => 'readme'];
            }
        }

        return null;
    }

    private static function findMainPluginFile(string $pluginPath): ?string
    {
        foreach (glob("{$pluginPath}/*.php") ?: [] as $file) {
            if (stripos(self::readFileHeader($file), 'Plugin Name:') !== false) {
                return $file;
            }
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

    private static function extractRequiresAtLeast(string $contents): ?string
    {
        if (preg_match('/^(?:[ \t]*<\?php)?[ \t\/*#@]*Requires at least:\s*([0-9.]+)/mi', $contents, $matches)) {
            return $matches[1];
        }

        return null;
    }

    private static function readFileHeader(string $file): string
    {
        $handle = fopen($file, 'rb');
        if (!$handle) {
            return '';
        }

        $data = fread($handle, self::HEADER_BYTES);
        fclose($handle);

        return $data ?: '';
    }
}
