<?php

namespace WP_Since\Scanner;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class NodeVisitor extends NodeVisitorAbstract
{
    protected $usedSymbols;

    protected $varMap;

    public function __construct()
    {
        $this->usedSymbols = [];
        $this->varMap = [];
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
            $this->usedSymbols[] = "{$class}::{$method}";
        } elseif (
            $node instanceof Node\Expr\MethodCall &&
            $node->var instanceof Node\Expr\Variable &&
            $node->name instanceof Node\Identifier
        ) {
            $varName = $node->var->name;
            $method = (string)$node->name;
            if (is_string($varName) && isset($this->varMap[$varName])) {
                $class = $this->varMap[$varName];
                $this->usedSymbols[] = "{$class}::{$method}";
            }
        }

        return null;
    }

    public function getusedSymbols(): array
    {
        return $this->usedSymbols;
    }
}
