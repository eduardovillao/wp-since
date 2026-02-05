<?php
require __DIR__ . '/vendor/autoload.php';

use PhpParser\Error;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;
use PhpParser\NodeVisitor\ParentConnectingVisitor;

$sourceDir = __DIR__ . '/wp-source';
$outputPath = __DIR__ . '/wp-since.json';

$excludedPaths = [
    'wp-content/',
    'wp-admin/includes/class-pclzip.php',
    'wp-admin/includes/noop.php',
    'wp-includes/ID3/',
    'wp-includes/IXR/',
    'wp-includes/PHPMailer/',
    'wp-includes/pomo/',
    'wp-includes/Requests/',
    'wp-includes/SimplePie/',
    'wp-includes/Text/',
    'wp-includes/sodium_compat/',
    'wp-includes/js/tinymce',
    'wp-includes/class-simplepie.php',
    'wp-includes/atomlib.php',
    'wp-includes/class-avif-info.php',
    'wp-includes/class-json.php',
    'wp-includes/class-pop3.php',
    'wp-includes/class-requests.php',
    'wp-includes/class-snoopy.php',
    'wp-includes/compat.php',
    'wp-includes/rss.php',
];

$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
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
        $docText = $doc ? $doc->getText() : null;
        $since = $this->extractTag($docText, '@since');
        $deprecated = $this->extractTag($docText, '@deprecated');

        if ($node instanceof Node\Stmt\Function_) {
            $this->addResult($node->name->toString(), 'function', $since, $deprecated);
        } elseif ($node instanceof Node\Stmt\Class_ || $node instanceof Node\Stmt\Interface_ || $node instanceof Node\Stmt\Trait_) {
            if ($node->name === null) {
                return; // Skip anonymous classes
            }
            $type = $node instanceof Node\Stmt\Class_ ? 'class' : ($node instanceof Node\Stmt\Interface_ ? 'interface' : 'trait');
            $this->addResult($node->name->toString(), $type, $since, $deprecated);
        } elseif ($node instanceof Node\Stmt\ClassMethod && !$node->isPrivate()) {
            $class = $node->getAttribute('parent');
            $className = $class instanceof Node\Stmt\Class_ && $class->name ? $class->name->toString() : 'Anonymous';
            $methodName = $node->name->toString();
            $methodSince = $since ?: ($class && $class->getDocComment() ? $this->extractTag($class->getDocComment()->getText(), '@since') : null);
            if ($className !== 'Anonymous' && $methodSince) {
                $this->addResult("$className::$methodName", 'method', $methodSince, $deprecated);
            }
        } elseif (
            $node instanceof Node\Expr\FuncCall &&
            $node->name instanceof Node\Name &&
            in_array($node->name->toString(), ['do_action', 'apply_filters'], true)
        ) {
            $hookNameNode = $node->args[0]->value ?? null;
            if ($hookNameNode instanceof Node\Scalar\String_) {
                $hookName = $hookNameNode->value;
                $this->addResult($hookName, 'hook', $since, $deprecated);
            }
        }
    }

    private function extractTag($docText, $tag)
    {
        if ($docText && preg_match('/' . preg_quote($tag) . '\s+([0-9.]+)/', $docText, $matches)) {
            $version = $matches[1];
            if ($version === 'MU') return '3.0.0';
            if (preg_match('/^\d+\.\d+(\.\d+)?$/', $version)) return $version;
        }
        return null;
    }

    private function addResult($name, $type, $since, $deprecated)
    {
        if ($since) {
            $this->result["$type:$name"] = array_filter([
                'type' => $type,
                'since' => $since,
                'deprecated' => $deprecated,
                'file' => $this->file
            ]);
        }
    }
}

$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($sourceDir));

foreach ($rii as $file) {
    if ($file->isDir() || $file->getExtension() !== 'php') continue;
    $relativePath = str_replace($sourceDir . '/', '', $file->getPathname());

    foreach ($excludedPaths as $excluded) {
        if (strpos($relativePath, $excluded) === 0) continue 2;
    }

    try {
        $code = file_get_contents($file->getPathname());
        $ast = $parser->parse($code);

        $traverser = new NodeTraverser();
        $traverser->addVisitor(new ParentConnectingVisitor());
        $traverser->addVisitor(new SinceExtractor($relativePath, $result));
        $traverser->traverse($ast);

    } catch (Error $e) {
        echo "Processing error {$relativePath}: {$e->getMessage()}\n";
    }
}

file_put_contents($outputPath, json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
echo "✅ File generated in: {$outputPath}\n";
