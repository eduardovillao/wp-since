<?php

use PHPUnit\Framework\TestCase;
use WP_Since\Checker\CompatibilityChecker;

class CompatibilityCheckerTest extends TestCase
{
    public function testDetectsIncompatibleSymbols()
    {
        $sinceMap = [
            'register_setting' => ['since' => '5.5.0'],
            'WP_Query'         => ['since' => '3.0.0'],
            'MyClass::boot'    => ['since' => '6.2.0'],
            'custom_hook'      => ['since' => '6.0.0'],
        ];

        $symbols = [
            'register_setting',
            'WP_Query',
            'MyClass::boot',
            'custom_hook',
        ];

        $declaredVersion = '5.5';

        $checker = new CompatibilityChecker($sinceMap);
        $incompatible = $checker->check($symbols, $declaredVersion);

        $this->assertArrayHasKey('MyClass::boot', $incompatible);
        $this->assertArrayHasKey('custom_hook', $incompatible);
        $this->assertArrayNotHasKey('register_setting', $incompatible);
        $this->assertArrayNotHasKey('WP_Query', $incompatible);
        $this->assertEquals('6.2.0', $incompatible['MyClass::boot']);
    }

    public function testAllSymbolsCompatible()
    {
        $sinceMap = [
            'function_one' => ['since' => '5.1.0'],
            'function_two' => ['since' => '5.0.0'],
        ];

        $symbols = ['function_one', 'function_two'];
        $declaredVersion = '5.5.0';

        $checker = new CompatibilityChecker($sinceMap);
        $incompatible = $checker->check($symbols, $declaredVersion);

        $this->assertEmpty($incompatible);
    }
}