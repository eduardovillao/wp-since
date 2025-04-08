<?php

use PHPUnit\Framework\TestCase;
use WP_Since\Parser\ReadmeParser;
use WP_Since\Scanner\PluginScanner;
use WP_Since\Checker\CompatibilityChecker;

class FullCompatibilityFlowTest extends TestCase
{
    public function testDetectsAllTypesOfSymbolsCorrectly()
    {
        $pluginPath = __DIR__ . '/../fixtures/plugin-full-test';
        $readmePath = $pluginPath . '/readme.txt';

        $declaredVersion = ReadmeParser::getMinRequiredVersion($readmePath);
        $symbols = PluginScanner::scan($pluginPath);

        $this->assertEquals('5.5', $declaredVersion);

        $sinceMap = [
            'add_option'                      => ['since' => '2.0.0'],
            'WP_Query'                        => ['since' => '3.0.0'],
            'WP_Filesystem::get_contents'     => ['since' => '5.1.0'],
            'WP_User::add_cap'                => ['since' => '5.7.0'],
            'my_custom_hook'                  => ['since' => '6.0.0'],
            'my_filter_hook'                  => ['since' => '5.3.0'],
        ];

        $checker = new CompatibilityChecker($sinceMap);
        $incompatible = $checker->check($symbols, $declaredVersion);

        $this->assertArrayHasKey('WP_User::add_cap', $incompatible);
        $this->assertArrayHasKey('my_custom_hook', $incompatible);

        $this->assertArrayNotHasKey('add_option', $incompatible);
        $this->assertArrayNotHasKey('WP_Query', $incompatible);
        $this->assertArrayNotHasKey('WP_Filesystem::get_contents', $incompatible);
        $this->assertArrayNotHasKey('my_filter_hook', $incompatible);

        $expected = array_keys($sinceMap);
        foreach ($expected as $symbol) {
            $this->assertContains($symbol, $symbols, "Missing symbol: {$symbol}");
        }
    }
}