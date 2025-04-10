<?php

namespace WP_Since\Tests\Resolver;

use PHPUnit\Framework\TestCase;
use WP_Since\Resolver\IgnoreRulesResolver;

class IgnoreRulesResolverTest extends TestCase
{
    public function testShouldIgnoreExactMatch()
    {
        $ignorePaths = ['tests/', 'assets/', 'example.php'];

        $this->assertTrue(IgnoreRulesResolver::shouldIgnore('tests/helper.php', $ignorePaths));
        $this->assertTrue(IgnoreRulesResolver::shouldIgnore('assets/img/logo.png', $ignorePaths));
        $this->assertTrue(IgnoreRulesResolver::shouldIgnore('example.php', $ignorePaths));
    }

    public function testShouldNotIgnoreNonMatchingPath()
    {
        $ignorePaths = ['tests/', 'vendor/', 'docs/readme.txt'];

        $this->assertFalse(IgnoreRulesResolver::shouldIgnore('src/Plugin.php', $ignorePaths));
        $this->assertFalse(IgnoreRulesResolver::shouldIgnore('includes/functions.php', $ignorePaths));
    }

    public function testShouldIgnoreNestedPaths()
    {
        $ignorePaths = ['admin/'];

        $this->assertTrue(IgnoreRulesResolver::shouldIgnore('admin/settings/page.php', $ignorePaths));
        $this->assertTrue(IgnoreRulesResolver::shouldIgnore('admin/page.php', $ignorePaths));
    }

    public function testShouldIgnoreWithOrWithoutLeadingSlash()
    {
        $ignorePaths = ['/build/', 'temp/'];

        $this->assertTrue(IgnoreRulesResolver::shouldIgnore('build/bundle.js', $ignorePaths));
        $this->assertTrue(IgnoreRulesResolver::shouldIgnore('temp/cache.php', $ignorePaths));
    }
}
