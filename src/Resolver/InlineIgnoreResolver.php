<?php

namespace WP_Since\Resolver;

use PhpParser\Lexer\Emulative;

class InlineIgnoreResolver
{
    public static function extractIgnoredLines(string $code): array
    {
        $lexer = new Emulative(['usedAttributes' => ['startLine']]);
        $lexer->startLexing($code);

        $ignoredLines = [];

        foreach ($lexer->getTokens() as $token) {
            if (is_array($token) && in_array($token[0], [T_COMMENT, T_DOC_COMMENT], true)) {
                if (strpos($token[1], '@wp-since ignore') !== false) {
                    $ignoredLines[] = $token[2];
                }
            }
        }

        return $ignoredLines;
    }
}
