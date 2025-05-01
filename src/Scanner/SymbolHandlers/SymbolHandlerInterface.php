<?php

namespace WP_Since\Scanner\SymbolHandlers;

use PhpParser\Node;

interface SymbolHandlerInterface
{
    public function supports(Node $node): bool;

    /**
     * @return string[] Symbols extracted from the node
     */
    public function extract(Node $node, array &$varMap = []): array;
}
