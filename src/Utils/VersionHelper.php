<?php

namespace WP_Since\Utils;

class VersionHelper
{
    /**
     * Normaliza uma versão para o formato x.y.z (ex: 5.5 → 5.5.0)
     */
    public static function normalize(string $version): string
    {
        $parts = explode('.', $version);
        while (count($parts) < 3) {
            $parts[] = '0';
        }
        return implode('.', $parts);
    }

    /**
     * Compara duas versões, normalizando ambas.
     *
     * @param string $versionA
     * @param string $versionB
     * @return int Retorna -1 se A < B, 0 se A == B, 1 se A > B
     */
    public static function compare(string $versionA, string $versionB): int
    {
        return version_compare(
            self::normalize($versionA),
            self::normalize($versionB)
        );
    }
}