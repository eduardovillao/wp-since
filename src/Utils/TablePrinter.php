<?php

namespace WP_Since\Utils;

class TablePrinter
{
    public static function render(array $rows, array $headers): void
    {
        $widths = [];
        foreach ($headers as $i => $header) {
            $widths[$i] = strlen($header);
        }

        foreach ($rows as $row) {
            foreach ($row as $i => $cell) {
                $widths[$i] = max($widths[$i], strlen($cell));
            }
        }

        $drawLine = function($left, $middle, $right, $fill = '─') use ($widths) {
            echo $left;
            foreach ($widths as $i => $w) {
                echo str_repeat($fill, $w + 2);
                echo $i < count($widths) - 1 ? $middle : $right;
            }
            echo "\n";
        };

        $drawRow = function($row, $sep = '│') use ($widths) {
            echo $sep;
            foreach ($row as $i => $cell) {
                echo ' ' . str_pad($cell, $widths[$i]) . ' ' . $sep;
            }
            echo "\n";
        };

        $drawLine('┌', '┬', '┐');
        $drawRow($headers);
        $drawLine('├', '┼', '┤');
        foreach ($rows as $row) {
            $drawRow($row);
        }
        $drawLine('└', '┴', '┘');
        echo "\n";
    }
}