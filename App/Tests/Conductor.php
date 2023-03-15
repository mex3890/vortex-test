<?php

namespace App\Tests;

use Core\Database\Schema;
use Core\Helpers\ClassManager;

class Conductor
{
    public const TEST_TABLE_NAME = 'tests';
    public const NAME_COLUMN = 'name';
    public const DATE_COLUMN = 'date';
    public const COUNT_COLUMN = 'count';
    public const RESULT_COLUMN = 'result';

    private int $count;
    private bool $debug = false;
    private DummyRobot $dummyRobotClass;
    private float $initial_time;
    private float $final_time;
    private bool $store_in_database = true;
    private string $test_name;

    public function __construct(DummyRobot $dummyRobotClass, int $count = 1)
    {
        $this->dummyRobotClass = $dummyRobotClass;
        $this->count = $count;
        $this->generateDefaultName();
    }

    public function storeInDatabase(bool $store_in_database = true): static
    {
        $this->store_in_database = $store_in_database;

        return $this;
    }

    public function debug(): static
    {
        $this->debug = true;

        return $this;
    }

    private function finish(): void
    {
        $this->final_time = floor(microtime(true) * 1000);
    }

    public function testName(string $test_name): static
    {
        $this->test_name = $test_name;

        return $this;
    }

    public function generateDefaultName(): void
    {
        if (!isset($this->test_name)) {
            $this->test_name = 'test_' . date('Y-m-d');
        }
    }

    private function start(): void
    {
        $this->initial_time = floor(microtime(true) * 1000);
    }

    public function run(): void
    {
        $end_time = 0;

        for ($i = 0; $i < $this->count; $i++) {
            $this->start();
            ClassManager::callStaticFunction($this->dummyRobotClass::class, 'action');
            $this->finish();

            $end_time += ($this->final_time - $this->initial_time);
        }

        $media_time = $end_time / $this->count;

        if ($this->store_in_database) {
            Schema::insert(static::TEST_TABLE_NAME, [
                static::NAME_COLUMN => $this->test_name,
                static::RESULT_COLUMN => $media_time,
                static::DATE_COLUMN => date('Y-m-d H:i:s'),
                static::COUNT_COLUMN => $this->count,
            ])->get();
        }

        if ($this->debug) {
            dump([
                static::NAME_COLUMN => $this->test_name,
                static::RESULT_COLUMN => $media_time,
                static::DATE_COLUMN => date('Y-m-d H:i:s'),
                static::COUNT_COLUMN => $this->count,
            ]);
        }
    }
}
