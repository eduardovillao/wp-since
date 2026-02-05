<?php

namespace WP_Since\Scanner;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use WP_Since\Scanner\SymbolHandlers\SymbolHandlerInterface;

class SymbolExtractorVisitor extends NodeVisitorAbstract
{
    private array $usedSymbols;
    private array $varMap;
    private array $ignoredLines;

    /** @var SymbolHandlerInterface[] */
    private array $handlers;

    public function __construct(array &$usedSymbols, array &$varMap, array $ignoredLines = [])
    {
        $this->usedSymbols = &$usedSymbols;
        $this->varMap = &$varMap;
        $this->ignoredLines = $ignoredLines;

        $this->handlers = [
            new SymbolHandlers\FunctionCallHandler(),
            new SymbolHandlers\NewClassHandler(),
            new SymbolHandlers\StaticCallHandler(),
            new SymbolHandlers\MethodCallHandler(),
        ];
    }

    public function enterNode(Node $node)
    {
        if (in_array($node->getStartLine(), $this->ignoredLines, true)) {
            return null;
        }

        foreach ($this->handlers as $handler) {
            if ($handler->supports($node)) {
                $symbols = $handler->extract($node, $this->varMap);
                foreach ($symbols as $symbol) {
                    if (is_array($symbol) && isset($symbol['type'], $symbol['name'])) {
                        $this->usedSymbols[] = $symbol['type'] . ':' . $symbol['name'];
                    } else {
                        $this->usedSymbols[] = $symbol;
                    }
                }
                break;
            }
        }
    }
}
