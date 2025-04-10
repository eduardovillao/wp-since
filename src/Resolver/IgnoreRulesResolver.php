<?php

namespace WP_Since\Resolver;

class IgnoreRulesResolver
{
    public static function getIgnoredPaths(string $pluginPath): array
    {
        $ignorePatterns = [];

        $distignore = $pluginPath . '/.distignore';
        if (file_exists($distignore)) {
            $ignorePatterns = array_merge($ignorePatterns, self::parseIgnoreFile($distignore));
        }

        $gitattributes = $pluginPath . '/.gitattributes';
        if (file_exists($gitattributes)) {
            $ignorePatterns = array_merge($ignorePatterns, self::parseGitAttributes($gitattributes));
        }

        return array_map(function ($pattern) {
            return ltrim(rtrim($pattern, '/'), '/');
        }, $ignorePatterns);
    }

    private static function parseIgnoreFile(string $file): array
    {
        return array_filter(array_map('trim', file($file)), fn($line) => $line !== '' && $line[0] !== '#');
    }

    private static function parseGitAttributes(string $file): array
    {
        $lines = file($file);
        $ignores = [];

        foreach ($lines as $line) {
            if (strpos($line, 'export-ignore') !== false) {
                $parts = preg_split('/\s+/', trim($line));
                if (!empty($parts[0])) {
                    $ignores[] = $parts[0];
                }
            }
        }

        return $ignores;
    }

    public static function shouldIgnore(string $relativePath, array $ignorePaths): bool
    {
        foreach ($ignorePaths as $ignored) {
            $normalized = ltrim($ignored, '/');
            if (
                $relativePath === $normalized ||
                str_starts_with($relativePath, rtrim($normalized, '/') . '/')
            ) {
                return true;
            }
        }
        return false;
    }
}
