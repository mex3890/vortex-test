<?php

namespace App\SchemaEngine\Reverse;

use App\SchemaEngine\Column;
use App\SchemaEngine\SchemaMapper;
use App\SchemaEngine\Table;
use Carbon\Carbon;
use Core\Abstractions\Enums\PhpExtra;
use Core\Helpers\FileDirManager;
use Core\Helpers\StrTool;

class ReverseEngine
{
    private array $final_table_order;

    public function __construct(private readonly SchemaMapper $schema)
    {
        $this->setTablesOrder();
        $this->mountMigrationsContent();
    }

    private function setTablesOrder(): void
    {
        $tables_dependencies = [];
        $pivot_tables = [];

        foreach ($this->schema->tables as $table) {
            if (!empty($table->pivot_columns)) {
                $pivot_tables[] = $table->name;

                continue;
            }

            if (empty($table->foreign_keys)) {
                $this->final_table_order[] = $table->name;

                continue;
            }

            foreach ($table->foreign_keys as $foreign_key) {
                $tables_dependencies[$table->name][] = $foreign_key->referenced_table;
            }
        }

        do {
            foreach ($tables_dependencies as $table => $dependencies) {
                foreach ($dependencies as $index => $dependency) {
                    if (in_array($dependency, $this->final_table_order)) {
                        unset($tables_dependencies[$table][$index]);

                        if (empty($tables_dependencies[$table])) {
                            $this->final_table_order[] = $table;
                            unset($tables_dependencies[$table]);
                        }
                    }
                }
            }
        } while (!empty($tables_dependencies));

        $this->final_table_order = array_merge($this->final_table_order, $pivot_tables);
    }

    public function getTablesOrder(): array
    {
        return $this->final_table_order;
    }

    private function mountMigrationsContent(): void
    {
        foreach ($this->final_table_order as $index => $table) {
            $table = $this->schema->getTableByName($table);
            $migration_columns_string = $this->mountMigrationTableColumnsString($table);

            FileDirManager::createFileByTemplate(
                Carbon::now()->addSeconds($index)
                    ->format('Y_m_d_H_i_s') . "_create_{$table->name}_table.php",
                'Database\\Test',
                __DIR__ . '/../../Stubs/migration_template.php',
                [
                    'MigrationClassName' => 'Create' . StrTool::pascalCase($table->name) . 'Table',
                    '$table_name' => $table->name,
                    '//$columns' => $migration_columns_string
                ]
            );
        }
    }

    private function mountMigrationTableColumnsString(Table $table): string
    {
        $columns_string = '';

        /** @var Column $column */
        foreach ($table->columns as $index => $column) {
            $columns_string .= ($index === 0 ? '' : str_repeat(PhpExtra::WHITE_SPACE->value, 12))
                . '$table->' . $this->retrieveColumnMethodsString($column)
                . ";\n";
        }

        return substr($columns_string, 0, -1);
    }

    // TODO: Verify enum and set column types
    private function retrieveColumnMethodsString(Column $column): string
    {
        $string_column = match (strtolower($column->type)) {
                'bigint' => 'bigInt',
                'datetime' => 'dateTime',
//                'enum' => ''
//                'set' => ''
                'longblob' => 'longBlob',
                'longtext' => 'longText',
                'mediumblob' => 'mediumBlob',
                'mediumint' => 'mediumInt',
                'mediumtext' => 'mediumText',
                'smallint' => 'smallInt',
                'tinyblob' => 'tinyBlob',
                'tinyint' => 'tinyInt',
                'tinytext' => 'tinyText',
                'varbinary' => 'varBinary',
                default => $column->type,

            } . "('$column->name')";

        if (isset($column->max_length)) {
            $string_column = str_replace(')', ",$column->max_length)", $string_column);
        }

        $string_column .= $this->mountConstraintMethodsByColumn($column);

        return $string_column;
    }

    private function mountConstraintMethodsByColumn(Column $column): string
    {
        $constraints_string = '';

        if ($column->primary_key) {
            $constraints_string .= '->primaryKey()';
        }

        if ($column->unique) {
            $constraints_string .= '->unique()';
        }

        if ($default = $column->default) {
            $constraints_string .= "->default('$default')";
        }

        if (!$column->nullable) {
            $constraints_string .= '->notNull()';
        }

        if ($column->auto_increment) {
            $constraints_string .= '->autoIncrement()';
        }

        if ($column->cascade_on_update) {
            $constraints_string .= '->cascadeOnUpdate()';
        }

        if ($column->cascade_on_delete) {
            $constraints_string .= '->cascadeOnDelete()';
        }

        if (isset($column->referenced_table)) {
            $constraints_string .= "->foreignKey('$column->referenced_table', '$column->referenced_column')";
        }

        return $constraints_string;
    }
}
