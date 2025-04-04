<?php
require 'vendor/autoload.php';

use PhpParser\Error;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;

$sourceDir = __DIR__ . '/wp-source';
$outputPath = __DIR__ . '/wp-since.json';

$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
$traverser = new NodeTraverser();

$result = [];

class SinceExtractor extends NodeVisitorAbstract
{
    private $file;
    private $result;

    public function __construct($file, &$result)
    {
        $this->file = $file;
        $this->result = &$result;
    }

    public function enterNode(Node $node)
    {
        $doc = $node->getDocComment();
        if (!$doc) return;

        if ($node instanceof Node\Stmt\Function_) {
            $name = $node->name->toString();
            $since = $this->extractSince($doc->getText());
            if ($since) {
                $this->result[$name] = [
                    'type' => 'function',
                    'since' => $since,
                    'file' => $this->file
                ];
            }
        } elseif ($node instanceof Node\Stmt\Class_ || $node instanceof Node\Stmt\Interface_ || $node instanceof Node\Stmt\Trait_) {
            $name = $node->name->toString();
            $since = $this->extractSince($doc->getText());
            if ($since) {
                $this->result[$name] = [
                    'type' => $node instanceof Node\Stmt\Class_ ? 'class' : ($node instanceof Node\Stmt\Interface_ ? 'interface' : 'trait'),
                    'since' => $since,
                    'file' => $this->file
                ];
            }
        } elseif ($node instanceof Node\Stmt\ClassMethod) {
            if ($node->isPrivate()) return;
            $class = $node->getAttribute('parent');
            $className = $class ? $class->name->toString() : 'Anonymous';
            $methodName = $node->name->toString();
            $since = $this->extractSince($doc->getText());
            if ($since && $className !== 'Anonymous') {
                $this->result["$className::$methodName"] = [
                    'type' => 'method',
                    'since' => $since,
                    'file' => $this->file
                ];
            }
        }
    }

    private function extractSince($docText)
    {
        if (preg_match('/@since\s+([0-9.]+)/', $docText, $matches)) {
            return $matches[1];
        }
        return null;
    }
}

$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($sourceDir));

foreach ($rii as $file) {
    if ($file->isDir() || $file->getExtension() !== 'php') continue;
    $relativePath = str_replace($sourceDir . '/', '', $file->getPathname());

    try {
        $code = file_get_contents($file->getPathname());
        $ast = $parser->parse($code);

        foreach ($ast as $node) {
            if ($node instanceof Node\Stmt\Class_ || $node instanceof Node\Stmt\Interface_ || $node instanceof Node\Stmt\Trait_) {
                foreach ($node->stmts as $stmt) {
                    $stmt->setAttribute('parent', $node);
                }
            }
        }

        $traverser->addVisitor(new SinceExtractor($relativePath, $result));
        $traverser->traverse($ast);
        $traverser->removeVisitor($traverser->getVisitors()[0]); // limpa pra próximo arquivo

    } catch (Error $e) {
        echo "Erro ao processar {$relativePath}: {$e->getMessage()}\n";
    }
}

file_put_contents($outputPath, json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
echo "✅ Arquivo gerado em: {$outputPath}\n";
