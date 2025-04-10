<?php

namespace WP_Since\Scanner;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PhpParser\NodeVisitor\ParentConnectingVisitor;
use WP_Since\Resolver\IgnoreRulesResolver;

class PluginScanner
{
    public static function scan(string $path): array
    {
        $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $traverser = new NodeTraverser();

        $traverser->addVisitor(new ParentConnectingVisitor());
        $visitor = new NodeVisitor();
        $traverser->addVisitor($visitor);

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

            try {
                $stmts = $parser->parse($code);
                $traverser->traverse($stmts);
            } catch (\Exception $e) {
                // @TODO Add error handling
            }
        }

        return array_unique($visitor->getusedSymbols());
    }
}
