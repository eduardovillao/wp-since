<?php

namespace WP_Since\Tests;

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

    public function testIgnoresSymbolsMarkedWithIgnoreComment()
    {
        $path = __DIR__ . '/fixtures/plugin-ignore-comment';
        $symbols = PluginScanner::scan($path);

        $this->assertNotContains('add_option', $symbols, 'Should ignore symbol with @wp-since ignore');
        $this->assertNotContains('should_be_ignored', $symbols, 'Should ignore symbolwith @wp-since ignore');
        $this->assertNotContains('wp_is_block_theme', $symbols, 'Should ignore symbol with @wp-since ignore');
        $this->assertNotContains('should_be_ignored_space', $symbols, 'Should ignore symbol with @wp-since ignore');
        $this->assertContains('do_action', $symbols, 'Should detect function call without ignore comment');
        $this->assertContains('my_custom_hook', $symbols, 'Should detect function call without ignore comment');
        $this->assertContains('register_setting', $symbols, 'Should detect function call without ignore comment');
        $this->assertContains('wp_detected_function', $symbols, 'Should detect function call without ignore comment');
        $this->assertContains('need_detect', $symbols, 'Should detect function call without ignore comment');
    }
}
