<?php

namespace WP_Since\Tests\Integration;

use PHPUnit\Framework\TestCase;
use WP_Since\Resolver\VersionResolver;
use WP_Since\Scanner\PluginScanner;
use WP_Since\Checker\CompatibilityChecker;

class FullCompatibilityFlowTest extends TestCase
{
    public function testDetectsAllTypesOfSymbolsCorrectly()
    {
        $pluginPath = __DIR__ . '/../fixtures/plugin-full-test';

        $declaredVersion = VersionResolver::resolve($pluginPath);
        $symbols = PluginScanner::scan($pluginPath);

        $this->assertNotNull($declaredVersion, 'Declared version should not be null');
        $this->assertEquals('5.5', $declaredVersion['version']);

        $sinceMap = [
            'function:add_option'             => ['since' => '2.0.0'],
            'function:set_transient'          => ['since' => '2.8.0'],
            'hook:set_transient'              => ['since' => '6.8.0'],  // Same name, different type
            'class:WP_Query'                  => ['since' => '3.0.0'],
            'method:WP_Filesystem::get_contents' => ['since' => '5.1.0'],
            'method:WP_User::add_cap'         => ['since' => '5.7.0'],
            'hook:my_custom_hook'             => ['since' => '6.0.0'],
            'hook:my_filter_hook'             => ['since' => '5.3.0'],
        ];

        $checker = new CompatibilityChecker($sinceMap);
        $incompatible = $checker->check($symbols, $declaredVersion['version']);

        $this->assertArrayHasKey('method:WP_User::add_cap', $incompatible);
        $this->assertArrayHasKey('hook:my_custom_hook', $incompatible);
        $this->assertArrayHasKey('hook:set_transient', $incompatible);  // 6.8.0 > 5.5

        $this->assertArrayNotHasKey('function:add_option', $incompatible);
        $this->assertArrayNotHasKey('function:set_transient', $incompatible);  // 2.8.0 < 5.5
        $this->assertArrayNotHasKey('class:WP_Query', $incompatible);
        $this->assertArrayNotHasKey('method:WP_Filesystem::get_contents', $incompatible);
        $this->assertArrayNotHasKey('hook:my_filter_hook', $incompatible);

        $expected = array_keys($sinceMap);
        foreach ($expected as $symbol) {
            $this->assertContains($symbol, $symbols, "Missing symbol: {$symbol}");
        }

        // phpcs:disable Generic.Files.LineLength.TooLong
        $this->assertNotContains('function:some_ignored_func_folder', $symbols, 'Should ignore folder from /ignored-folder/');
        $this->assertNotContains('function:some_ignored_func_file', $symbols, 'Should ignore specific file /ignore-this.php');
        $this->assertNotContains('function:some_ignored_func_noslash', $symbols, 'Should ignore folder from ignored-no-slash/');
    }
}
