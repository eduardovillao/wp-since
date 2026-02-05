<?php

namespace WP_Since\Scanner\SymbolHandlers;

use PhpParser\Node;

class MethodCallHandler implements SymbolHandlerInterface
{
    public function supports(Node $node): bool
    {
        return $node instanceof Node\Expr\MethodCall &&
            $node->var instanceof Node\Expr\Variable &&
            $node->name instanceof Node\Identifier;
    }

    public function extract(Node $node, array &$varMap = []): array
    {
        $varName = $node->var->name;
        $method = (string) $node->name;

        if (is_string($varName) && isset($varMap[$varName])) {
            $class = $varMap[$varName];
            return [[
                'name' => "$class::$method",
                'type' => 'method',
            ]];
        }

        return [];
    }
}
