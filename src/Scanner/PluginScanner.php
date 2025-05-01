<?php

namespace WP_Since\Scanner;

use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PhpParser\NodeVisitor\ParentConnectingVisitor;
use WP_Since\Resolver\IgnoreRulesResolver;
use WP_Since\Scanner\SymbolExtractorVisitor;
use WP_Since\Resolver\InlineIgnoreResolver;

class PluginScanner
{
    public static function scan(string $path): array
    {
        $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $traverser = new NodeTraverser();

        $usedSymbols = [];
        $varMap = [];

        $traverser->addVisitor(new ParentConnectingVisitor());

        $ignorePaths = IgnoreRulesResolver::getIgnoredPaths($path);
        $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
        foreach ($rii as $file) {
            $relativePath = str_replace($path . '/', '', $file->getPathname());

            if (
                $file->isDir() ||
                $file->getExtension() !== 'php' ||
                IgnoreRulesResolver::shouldIgnore($relativePath, $ignorePaths)
            ) {
                continue;
            }

            $code = file_get_contents($file->getPathname());
            $ignoredLines = InlineIgnoreResolver::extractIgnoredLines($code);

            $visitor = new SymbolExtractorVisitor($usedSymbols, $varMap, $ignoredLines);
            $traverser->addVisitor($visitor);

            try {
                $stmts = $parser->parse($code);
                $traverser->traverse($stmts);
            } catch (\Exception $e) {
                // Add error handling
            }

            $traverser->removeVisitor($visitor);
        }

        return array_unique($usedSymbols);
    }
}
