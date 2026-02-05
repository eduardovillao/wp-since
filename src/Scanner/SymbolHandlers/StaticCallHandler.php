<?php

namespace WP_Since\Scanner\SymbolHandlers;

use PhpParser\Node;

class StaticCallHandler implements SymbolHandlerInterface
{
    public function supports(Node $node): bool
    {
        return $node instanceof Node\Expr\StaticCall &&
            $node->class instanceof Node\Name &&
            $node->name instanceof Node\Identifier;
    }

    public function extract(Node $node, array &$varMap = []): array
    {
        $class = (string) $node->class;
        $method = (string) $node->name;
        return [[
            'name' => "$class::$method",
            'type' => 'method',
        ]];
    }
}
