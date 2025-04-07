<?php

namespace WP_Since\Scanner;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;

class PluginScanner
{
    public static function scan(string $path): array
    {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $traverser = new NodeTraverser();

        $usedSymbols = [];

        $traverser->addVisitor(new class($usedSymbols) extends NodeVisitorAbstract {
            private $usedSymbols;

            public function __construct(&$usedSymbols)
            {
                $this->usedSymbols = &$usedSymbols;
            }

            public function enterNode(Node $node)
            {
                if ($node instanceof Node\Expr\FuncCall && $node->name instanceof Node\Name) {
                    $this->usedSymbols[] = (string)$node->name;

                    $hookFunctions = ['do_action', 'apply_filters'];
                    if (in_array((string)$node->name, $hookFunctions, true)) {
                        $hookNameNode = $node->args[0]->value ?? null;
                        if ($hookNameNode instanceof Node\Scalar\String_) {
                            $this->usedSymbols[] = $hookNameNode->value;
                        }
                    }
                }

                elseif ($node instanceof Node\Expr\New_ && $node->class instanceof Node\Name) {
                    $this->usedSymbols[] = (string)$node->class;
                }

                elseif (
                    $node instanceof Node\Expr\StaticCall &&
                    $node->class instanceof Node\Name &&
                    $node->name instanceof Node\Identifier
                ) {
                    $class = (string)$node->class;
                    $method = (string)$node->name;
                    $this->usedSymbols[] = "$class::$method";
                }

                // (Opcional futuro: métodos de instância)
            }
        });

        $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
        foreach ($rii as $file) {
            if ($file->isDir() || $file->getExtension() !== 'php') continue;
            $code = file_get_contents($file->getPathname());
            try {
                $stmts = $parser->parse($code);
                $traverser->traverse($stmts);
            } catch (\Exception $e) {
                // add logging error soon
            }
        }

        return array_unique($usedSymbols);
    }
}