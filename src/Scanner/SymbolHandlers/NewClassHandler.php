<?php

namespace WP_Since\Scanner\SymbolHandlers;

use PhpParser\Node;

class NewClassHandler implements SymbolHandlerInterface
{
    public function supports(Node $node): bool
    {
        return $node instanceof Node\Expr\New_ && $node->class instanceof Node\Name;
    }

    public function extract(Node $node, array &$varMap = []): array
    {
        $symbols = [(string) $node->class];

        $parent = $node->getAttribute('parent');
        if (
            $parent instanceof Node\Expr\Assign &&
            $parent->var instanceof Node\Expr\Variable &&
            is_string($parent->var->name)
        ) {
            $varMap[$parent->var->name] = (string) $node->class;
        }

        return $symbols;
    }
}
