<?php

use PHPUnit\Framework\TestCase;
use WP_Since\Scanner\PluginScanner;

class PluginScannerTest extends TestCase
{
    public function testScanDetectsAllSymbols()
    {
        $path = __DIR__ . '/fixtures';
        $symbols = PluginScanner::scan($path);

        $expected = [
            'register_setting',
            'do_action',
            'init',
            'apply_filters',
            'custom_filter',
            'WP_Query',
            'MyClass::boot'
        ];

        foreach ($expected as $symbol) {
            $this->assertContains($symbol, $symbols, "Missing: {$symbol}");
        }

        $this->assertNotEmpty($symbols);
    }
}