<?php

use PHPUnit\Framework\TestCase;
use WP_Since\Scanner\PluginScanner;

final class PluginScannerTest extends TestCase
{
    public function testDetectsAllSymbols()
    {
        $pluginPath = __DIR__ . '/fixtures/plugin-full-test';
        $symbols = PluginScanner::scan($pluginPath);

        $expected = [
            'add_option',
            'WP_Query',
            'WP_Filesystem::get_contents',
            'WP_User::add_cap',
            'my_custom_hook',
            'my_filter_hook',
        ];

        foreach ($expected as $symbol) {
            $this->assertContains($symbol, $symbols, "Missing: $symbol");
        }
    }
}