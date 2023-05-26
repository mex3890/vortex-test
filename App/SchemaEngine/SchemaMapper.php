<?php

namespace App\SchemaEngine;

use Core\Database\JoinBuilder;
use Core\Database\Schema;

class SchemaMapper
{
    private const COLUMNS_TABLE = 'information_schema.columns';
    private const KEY_COLUMN_USAGE_TABLE = 'information_schema.KEY_COLUMN_USAGE';
    private const CONSTRAINTS_TABLE = 'information_schema.TABLE_CONSTRAINTS';
    private const COLUMN_NAME_COLUMN = 'COLUMN_NAME';
    private const TABLE_SCHEMA_COLUMN = 'TABLE_SCHEMA';
    private const TABLE_NAME_COLUMN = 'TABLE_NAME';
    private const CONSTRAINT_NAME_COLUMN = 'CONSTRAINT_NAME';
    private const CONSTRAINT_SCHEMA_COLUMN = 'CONSTRAINT_SCHEMA';

    public string $schema_name;
    public array $tables_name;
    public array $schema_skeleton;
    public array $tables;

    public function __construct()
    {
        $this->schema_name = $_ENV['DB_DATABASE'];
        $this->setSchemaTablesNames();
        $this->setTablesSkeleton();
        $this->mountSchemaTablesObject();
    }

    private function setSchemaTablesNames(): void
    {
        $this->tables_name = Schema::select('information_schema.tables', static::TABLE_NAME_COLUMN)
            ->where(static::TABLE_SCHEMA_COLUMN, $this->schema_name)
            ->whereNotIn('TABLE_NAME', 'information_schema.tables', ['migrations'])
            ->get()
            ->pullUp(static::TABLE_NAME_COLUMN);
    }

    private function setTablesSkeleton(): void
    {
        foreach ($this->tables_name as $table) {
            $this->schema_skeleton[$table] = Schema::select(static::COLUMNS_TABLE, [
                static::COLUMNS_TABLE . '.' . static::COLUMN_NAME_COLUMN,
                static::COLUMNS_TABLE . '.DATA_TYPE',
                static::COLUMNS_TABLE . '.COLUMN_KEY',
                static::COLUMNS_TABLE . '.CHARACTER_MAXIMUM_LENGTH',
                static::KEY_COLUMN_USAGE_TABLE . '.' . static::CONSTRAINT_NAME_COLUMN,
                static::KEY_COLUMN_USAGE_TABLE . '.REFERENCED_TABLE_NAME',
                static::KEY_COLUMN_USAGE_TABLE . '.REFERENCED_COLUMN_NAME',
                static::CONSTRAINTS_TABLE . '.CONSTRAINT_TYPE',
            ])->leftJoin(static::KEY_COLUMN_USAGE_TABLE, function (JoinBuilder $join) {
                return $join->on(
                    static::COLUMNS_TABLE . '.' . static::COLUMN_NAME_COLUMN,
                    static::KEY_COLUMN_USAGE_TABLE . '.' . static::COLUMN_NAME_COLUMN
                )->and(
                    static::COLUMNS_TABLE . '.' . static::TABLE_SCHEMA_COLUMN,
                    static::KEY_COLUMN_USAGE_TABLE . '.' . static::TABLE_SCHEMA_COLUMN
                )->and(
                    static::COLUMNS_TABLE . '.' . static::TABLE_NAME_COLUMN,
                    static::KEY_COLUMN_USAGE_TABLE . '.' . static::TABLE_NAME_COLUMN
                );
            })->leftJoin(static::CONSTRAINTS_TABLE, function (JoinBuilder $join) {
                return $join->on(
                    static::KEY_COLUMN_USAGE_TABLE . '.' . static::CONSTRAINT_NAME_COLUMN,
                    static::CONSTRAINTS_TABLE . '.' . static::CONSTRAINT_NAME_COLUMN
                )->and(
                    static::COLUMNS_TABLE . '.' . static::TABLE_NAME_COLUMN,
                    static::CONSTRAINTS_TABLE . '.' . static::TABLE_NAME_COLUMN
                )->and(
                    static::COLUMNS_TABLE . '.' . static::TABLE_SCHEMA_COLUMN,
                    static::CONSTRAINTS_TABLE . '.' . static::CONSTRAINT_SCHEMA_COLUMN
                );
            })->where(static::COLUMNS_TABLE . '.' . static::TABLE_SCHEMA_COLUMN, $this->schema_name)
                ->where(static::COLUMNS_TABLE . '.' . static::TABLE_NAME_COLUMN, $table)
                ->get();
        }
    }

    private function mountSchemaTablesObject(): void
    {
        foreach ($this->schema_skeleton as $table_name => $table) {
            $this->tables[] = new Table($table_name, $table);
        }
    }
}