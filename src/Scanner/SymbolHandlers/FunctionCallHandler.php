<?php

namespace WP_Since\Scanner\SymbolHandlers;

use PhpParser\Node;

class FunctionCallHandler implements SymbolHandlerInterface
{
    public function supports(Node $node): bool
    {
        return $node instanceof Node\Expr\FuncCall && $node->name instanceof Node\Name;
    }

    public function extract(Node $node, array &$varMap = []): array
    {
        $symbols = [(string) $node->name];

        if (in_array((string) $node->name, ['do_action', 'apply_filters'], true)) {
            $hookNameNode = $node->args[0]->value ?? null;
            if ($hookNameNode instanceof Node\Scalar\String_) {
                $symbols[] = $hookNameNode->value;
            }
        }

        return $symbols;
    }
}
