<?php

namespace WP_Since\Scanner;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;
use PhpParser\NodeVisitor\ParentConnectingVisitor;
use WP_Since\Resolver\IgnoreRulesResolver;

class PluginScanner
{
    public static function scan(string $path): array
    {
        $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $traverser = new NodeTraverser();

        $usedSymbols = [];
        $varMap = [];

        $traverser->addVisitor(new ParentConnectingVisitor());
        $traverser->addVisitor(new class ($usedSymbols, $varMap) extends NodeVisitorAbstract {
            private $usedSymbols;
            private $varMap;

            public function __construct(&$usedSymbols, &$varMap)
            {
                $this->usedSymbols = &$usedSymbols;
                $this->varMap = &$varMap;
            }

            public function enterNode(Node $node)
            {
                if ($node instanceof Node\Expr\FuncCall && $node->name instanceof Node\Name) {
                    $this->usedSymbols[] = (string)$node->name;

                    if (in_array((string)$node->name, ['do_action', 'apply_filters'], true)) {
                        $hookNameNode = $node->args[0]->value ?? null;
                        if ($hookNameNode instanceof Node\Scalar\String_) {
                            $this->usedSymbols[] = $hookNameNode->value;
                        }
                    }
                } elseif ($node instanceof Node\Expr\New_ && $node->class instanceof Node\Name) {
                    $this->usedSymbols[] = (string)$node->class;

                    if (
                        $node->getAttribute('parent') instanceof Node\Expr\Assign &&
                        $node->getAttribute('parent')->var instanceof Node\Expr\Variable
                    ) {
                        $varName = $node->getAttribute('parent')->var->name;
                        if (is_string($varName)) {
                            $this->varMap[$varName] = (string)$node->class;
                        }
                    }
                } elseif (
                    $node instanceof Node\Expr\StaticCall &&
                    $node->class instanceof Node\Name &&
                    $node->name instanceof Node\Identifier
                ) {
                    $class = (string)$node->class;
                    $method = (string)$node->name;
                    $this->usedSymbols[] = "$class::$method";
                } elseif (
                    $node instanceof Node\Expr\MethodCall &&
                    $node->var instanceof Node\Expr\Variable &&
                    $node->name instanceof Node\Identifier
                ) {
                    $varName = $node->var->name;
                    $method = (string)$node->name;
                    if (is_string($varName) && isset($this->varMap[$varName])) {
                        $class = $this->varMap[$varName];
                        $this->usedSymbols[] = "$class::$method";
                    }
                }
            }
        });

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
                // Add error handling
            }
        }

        return array_unique($usedSymbols);
    }
}
