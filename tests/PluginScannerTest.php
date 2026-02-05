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
            'function:add_option',
            'function:set_transient',
            'hook:set_transient',  // Same name as function - no collision
            'class:WP_Query',
            'method:WP_Filesystem::get_contents',
            'method:WP_User::add_cap',
            'hook:my_custom_hook',
            'hook:my_filter_hook',
        ];

        foreach ($expected as $symbol) {
            $this->assertContains($symbol, $symbols, "Missing: $symbol");
        }
    }

    public function testIgnoresSymbolsMarkedWithIgnoreComment()
    {
        $path = __DIR__ . '/fixtures/plugin-ignore-comment';
        $symbols = PluginScanner::scan($path);

        $this->assertNotContains('function:add_option', $symbols, 'Should ignore symbol with @wp-since ignore');
        $this->assertNotContains('hook:should_be_ignored', $symbols, 'Should ignore symbol with @wp-since ignore');
        $this->assertNotContains('function:wp_is_block_theme', $symbols, 'Should ignore symbol with @wp-since ignore');
        $this->assertNotContains('hook:should_be_ignored_space', $symbols, 'Should ignore @wp-since ignore');
        $this->assertContains('hook:my_custom_hook', $symbols, 'Should detect hook without ignore comment');
        $this->assertContains('function:register_setting', $symbols, 'Should detect function without ignore');
        $this->assertContains('function:wp_detected_function', $symbols, 'Should detect function without ignore');
        $this->assertContains('hook:need_detect', $symbols, 'Should detect hook without ignore comment');
    }
}
