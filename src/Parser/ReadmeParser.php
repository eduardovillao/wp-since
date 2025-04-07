<?php

namespace WP_Since\Parser;

class ReadmeParser
{
    /**
     * Extrai a versão mínima do WordPress a partir do readme.txt de um plugin.
     *
     * @param string $readmePath Caminho para o arquivo readme.txt
     * @return string|null Versão mínima ou null se não encontrada
     */
    public static function getMinRequiredVersion(string $readmePath): ?string
    {
        if (!file_exists($readmePath)) {
            throw new \InvalidArgumentException("Arquivo readme.txt não encontrado em: $readmePath");
        }

        $lines = file($readmePath);

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
