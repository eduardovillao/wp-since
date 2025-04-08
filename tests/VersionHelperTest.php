<?php

use PHPUnit\Framework\TestCase;
use WP_Since\Utils\VersionHelper;

class VersionHelperTest extends TestCase
{
    public function testNormalizeShortVersions()
    {
        $this->assertEquals('5.5.0', VersionHelper::normalize('5.5'));
        $this->assertEquals('5.0.0', VersionHelper::normalize('5'));
        $this->assertEquals('6.1.2', VersionHelper::normalize('6.1.2'));
    }

    public function testCompareVersions()
    {
        $this->assertSame(0, VersionHelper::compare('5.5', '5.5.0'));
        $this->assertSame(0, VersionHelper::compare('6.1.0', '6.1'));
        $this->assertSame(1, VersionHelper::compare('6.2', '6.1.5'));
        $this->assertSame(-1, VersionHelper::compare('5.9', '6.0'));
    }
}
