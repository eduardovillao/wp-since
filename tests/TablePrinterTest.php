<?php

namespace WP_Since\Tests;

use PHPUnit\Framework\TestCase;
use WP_Since\Utils\TablePrinter;

class TablePrinterTest extends TestCase
{
    public function testRenderOutputsCorrectTable()
    {
        $headers = ['Name', 'Version'];
        $rows = [
            ['register_setting', '5.5.0'],
            ['some_function', '6.0.0'],
        ];

        ob_start();
        TablePrinter::render($rows, $headers);
        $output = ob_get_clean();

        $this->assertStringContainsString('register_setting', $output);
        $this->assertStringContainsString('some_function', $output);
        $this->assertStringContainsString('┌', $output);
        $this->assertStringContainsString('┴', $output);
        $this->assertStringContainsString('│ Name', $output);
        $this->assertStringContainsString('│ Version', $output);
    }
}
