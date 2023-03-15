<?php

namespace App\Tests;

use Core\Database\Schema;
use Core\Helpers\ClassManager;
use Core\Helpers\DateTime;

class Conductor
{
    public const TEST_TABLE_NAME = 'tests';
    public const NAME_COLUMN = 'name';
    public const DATE_COLUMN = 'date';
    public const COUNT_COLUMN = 'count';
    public const RESULT_COLUMN = 'average_in_milliseconds';
    public const OPERATION_TIME_COLUMN = 'operation_full_time_in_milliseconds';

    private int $count;
    private string $dummyRobotClass;
    private float $initial_time;
    private float $final_time;
    private bool $store_in_database = true;
    private string $test_name;

    public function __construct(string $dummyRobotClass, int $count = 1)
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

    private function finish(): void
    {
        $this->final_time = DateTime::retrieveCurrentMillisecond();
    }

    public function testName(string $test_name): static
    {
        $this->test_name = $test_name;

        return $this;
    }

    private function generateDefaultName(): void
    {
        if (!isset($this->test_name)) {
            $this->test_name = 'test_' . date('Y-m-d');
        }
    }

    private function start(): void
    {
        $this->initial_time = DateTime::retrieveCurrentMillisecond();
    }

    public function run(): array
    {
        $end_time = 0;

        $full_time_operation = DateTime::retrieveCurrentMillisecond();

        for ($i = 0; $i < $this->count; $i++) {
            $this->start();
            ClassManager::callStaticFunction($this->dummyRobotClass, 'action');
            $this->finish();

            $end_time += ($this->final_time - $this->initial_time);
        }

        $full_time_operation = DateTime::retrieveCurrentMillisecond() - $full_time_operation;

        $media_time = bcdiv($end_time, $this->count, 5);

        if ($this->store_in_database) {
            Schema::insert(static::TEST_TABLE_NAME, [
                static::NAME_COLUMN => $this->test_name,
                static::OPERATION_TIME_COLUMN => $full_time_operation,
                static::RESULT_COLUMN => $media_time,
                static::DATE_COLUMN => date('Y-m-d H:i:s'),
                static::COUNT_COLUMN => $this->count,
            ])->get();
        }

        return [
            static::NAME_COLUMN => $this->test_name,
            static::OPERATION_TIME_COLUMN => $full_time_operation,
            static::RESULT_COLUMN => $media_time,
            static::DATE_COLUMN => date('Y-m-d H:i:s'),
            static::COUNT_COLUMN => $this->count,
        ];
    }
}
