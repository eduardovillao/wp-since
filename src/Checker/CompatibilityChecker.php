<?php
namespace WP_Since\Checker;

use WP_Since\Utils\VersionHelper;

class CompatibilityChecker
{
    private array $sinceMap;

    public function __construct(array $sinceMap)
    {
        $this->sinceMap = $sinceMap;
    }

    public function check(array $symbols, string $declaredVersion): array
    {
        $incompatible = [];

        foreach ($symbols as $symbol) {
            if (isset($this->sinceMap[$symbol]) &&
                VersionHelper::compare($declaredVersion, $this->sinceMap[$symbol]['since']) < 0) {
                $incompatible[$symbol] = $this->sinceMap[$symbol]['since'];
            }
        }

        return $incompatible;
    }
}