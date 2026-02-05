<?php

namespace WP_Since\Tests;

use PHPUnit\Framework\TestCase;
use WP_Since\Checker\CompatibilityChecker;

class CompatibilityCheckerTest extends TestCase
{
    public function testDetectsIncompatibleSymbols()
    {
        $sinceMap = [
            'function:register_setting' => ['since' => '5.5.0'],
            'class:WP_Query'            => ['since' => '3.0.0'],
            'method:MyClass::boot'      => ['since' => '6.2.0'],
            'hook:custom_hook'          => ['since' => '6.0.0'],
        ];

        $symbols = [
            'function:register_setting',
            'class:WP_Query',
            'method:MyClass::boot',
            'hook:custom_hook',
        ];

        $declaredVersion = '5.5';

        $checker = new CompatibilityChecker($sinceMap);
        $incompatible = $checker->check($symbols, $declaredVersion);

        $this->assertArrayHasKey('method:MyClass::boot', $incompatible);
        $this->assertArrayHasKey('hook:custom_hook', $incompatible);
        $this->assertArrayNotHasKey('function:register_setting', $incompatible);
        $this->assertArrayNotHasKey('class:WP_Query', $incompatible);
        $this->assertEquals('6.2.0', $incompatible['method:MyClass::boot']);
    }

    public function testAllSymbolsCompatible()
    {
        $sinceMap = [
            'function:function_one' => ['since' => '5.1.0'],
            'function:function_two' => ['since' => '5.0.0'],
        ];

        $symbols = ['function:function_one', 'function:function_two'];
        $declaredVersion = '5.5.0';

        $checker = new CompatibilityChecker($sinceMap);
        $incompatible = $checker->check($symbols, $declaredVersion);

        $this->assertEmpty($incompatible);
    }
}
