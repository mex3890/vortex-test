<?php

namespace App\SchemaEngine\Reverse;

use App\SchemaEngine\SchemaMapper;
use Core\Helpers\FileDirManager;

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

                        if (empty($this->tables_dependencies[$table])) {
                            $this->final_table_order[] = $table;
                            unset($tables_dependencies[$table]);
                        }
                    }
                }
            }
        } while (!empty($this->tables_dependencies));

        $this->final_table_order = array_merge($this->final_table_order, $pivot_tables);
    }

    public function getTablesOrder(): array
    {
        return $this->final_table_order;
    }

    private function mountMigrationsContent()
    {
        foreach ($this->final_table_order as $table) {
            
        }
    }
}
