<?php

namespace WP_Since\Tests;

use PHPUnit\Framework\TestCase;
use WP_Since\Resolver\VersionResolver;

class VersionResolverTest extends TestCase
{
    public function testExtractsVersionFromMainPluginFile()
    {
        $path = __DIR__ . '/fixtures/plugin-with-header';
        $resolved = VersionResolver::resolve($path);

        $this->assertSame('6.2', $resolved['version']);
        $this->assertSame('plugin', $resolved['source']);
    }

    public function testExtractsVersionFromReadmeIfNoHeader()
    {
        $path = __DIR__ . '/fixtures/plugin-with-readme-only';
        $resolved = VersionResolver::resolve($path);

        $this->assertSame('5.8', $resolved['version']);
        $this->assertSame('readme', $resolved['source']);
    }

    public function testReturnsNullIfVersionNotFoundAnywhere()
    {
        $path = __DIR__ . '/fixtures/plugin-without-version';
        $resolved = VersionResolver::resolve($path);

        $this->assertNull($resolved);
    }
}
