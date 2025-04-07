<?php

use PHPUnit\Framework\TestCase;
use WP_Since\Parser\ReadmeParser;

class ReadmeParserTest extends TestCase
{
    public function testParsesLowercaseReadme()
    {
        $file = __DIR__ . '/fixtures/readme-5.8.txt';

        $version = ReadmeParser::getMinRequiredVersion($file);
        $this->assertSame('5.8', $version);
    }

    public function testParsesUppercaseReadme()
    {
        $file = __DIR__ . '/fixtures/README-6.1.txt';

        $version = ReadmeParser::getMinRequiredVersion($file);
        $this->assertSame('6.1', $version);
    }

    public function testReturnsNullIfVersionNotFound()
    {
        $file = __DIR__ . '/fixtures/readme-empty.txt';

        $version = ReadmeParser::getMinRequiredVersion($file);
        $this->assertNull($version);
    }
}