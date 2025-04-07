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
            'register_setting'   => ['since' => '5.5.0'],
            'WP_Query'           => ['since' => '3.0.0'],
            'MyPlugin::boot'     => ['since' => '6.2.0'],
            'my_custom_hook'     => ['since' => '6.0.0'],
            'my_filter'          => ['since' => '5.3.0'],
        ];

        $checker = new CompatibilityChecker($sinceMap);
        $incompatible = $checker->check($symbols, $declaredVersion);

        $this->assertArrayHasKey('MyPlugin::boot', $incompatible);
        $this->assertArrayHasKey('my_custom_hook', $incompatible);
        $this->assertArrayNotHasKey('register_setting', $incompatible);
        $this->assertArrayNotHasKey('WP_Query', $incompatible);
        $this->assertArrayNotHasKey('my_filter', $incompatible);

        $expected = [
            'register_setting',
            'WP_Query',
            'MyPlugin::boot',
            'my_custom_hook',
            'my_filter',
        ];

        foreach ($expected as $symbol) {
            $this->assertContains($symbol, $symbols, "Missing symbol: {$symbol}");
        }
    }
}