<?php

namespace WP_Since\Scanner;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use WP_Since\Scanner\SymbolHandlers\SymbolHandlerInterface;

class SymbolExtractorVisitor extends NodeVisitorAbstract
{
    private array $usedSymbols;
    private array $varMap;

    /** @var SymbolHandlerInterface[] */
    private array $handlers;

    public function __construct(array &$usedSymbols, array &$varMap)
    {
        $this->usedSymbols = &$usedSymbols;
        $this->varMap = &$varMap;

        $this->handlers = [
            new SymbolHandlers\FunctionCallHandler(),
            new SymbolHandlers\NewClassHandler(),
            new SymbolHandlers\StaticCallHandler(),
            new SymbolHandlers\MethodCallHandler(),
        ];
    }

    public function enterNode(Node $node)
    {
        foreach ($this->handlers as $handler) {
            if ($handler->supports($node)) {
                $symbols = $handler->extract($node, $this->varMap);
                foreach ($symbols as $symbol) {
                    $this->usedSymbols[] = $symbol;
                }
                break;
            }
        }
    }
}
