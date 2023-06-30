<?php

namespace App\SchemaEngine\AutoRelation;

use App\SchemaEngine\Column;
use App\SchemaEngine\SchemaMapper;
use App\SchemaEngine\Table;
use App\SchemaEngine\TraceRelation;
use Core\Helpers\StrTool;

class DiscoverRelations
{
    private SchemaMapper $schema;
    private array $final_relationships;

    public function __construct(
        private readonly bool $with_pivot_model = true,
        private readonly bool $with_test = true)
    {
        ini_set('memory_limit', -1);
        $this->schema = new SchemaMapper();
    }

    public function setRelations(): array
    {
        $model_tables = [];

        foreach ($this->schema->tables as $table) {
            if ($table->pivot) {
                if ($this->with_pivot_model) {
                    $model_tables[StrTool::camelCase($table->name)] = $table->name;
                }

                $this->resolvePivotRelations($table);

                continue;
            }

            $model_tables[strtolower(StrTool::firstLetterUppercase(StrTool::singularize($table->name)))] = $table->name;

            if (empty($table->foreign_keys)) {
                if (empty($this->final_relationships[StrTool::singularize($table->name)])) {
                    $this->final_relationships[StrTool::singularize($table->name)] = [];
                }

                continue;
            }

            foreach ($table->foreign_keys as $foreign_key) {
                /** @var Column $foreign_key */
                $this->resolveRelation($table, $foreign_key);
            }
        }

        $this->setTraceRelations();

        return [
            'relationships' => $this->final_relationships,
            'models' => $model_tables,
        ];
    }

    private function resolvePivotRelations(Table $pivotTable): void
    {
        $first_model = $this->getModelNameByTable(substr($pivotTable->pivot_columns[0]->name, 0, -3));
        $second_model = $this->getModelNameByTable(substr($pivotTable->pivot_columns[1]->name, 0, -3));

        $this->final_relationships[$first_model][] = [
            'called_model' => $second_model,
            'caller_primary_key' => $pivotTable->pivot_columns[0]->referenced_column,
            'caller_foreign_key' => $pivotTable->pivot_columns[0]->name,
            'pivot_table' => $pivotTable->name,
            'called_primary_key' => $pivotTable->pivot_columns[1]->referenced_column,
            'called_foreign_key' => $pivotTable->pivot_columns[1]->name,
            'relation_type' => Relationships::BELONGS_TO_MANY,
        ];

        $this->final_relationships[$second_model][] = [
            'called_model' => $first_model,
            'caller_primary_key' => $pivotTable->pivot_columns[1]->referenced_column,
            'caller_foreign_key' => $pivotTable->pivot_columns[1]->name,
            'pivot_table' => $pivotTable->name,
            'called_primary_key' => $pivotTable->pivot_columns[0]->referenced_column,
            'called_foreign_key' => $pivotTable->pivot_columns[0]->name,
            'relation_type' => Relationships::BELONGS_TO_MANY,
        ];

        if ($this->with_pivot_model) {
            $pivot_model = StrTool::camelCase($pivotTable->name);

            $this->final_relationships[$pivot_model][] = [
                'called_model' => $first_model,
                'caller_primary_key' => $pivotTable->primary_keys[0]->name ?? 'id',
                'called_primary_key' => $this->schema->tables[$pivotTable->pivot_columns[0]->referenced_table]->primary_key[0] ?? 'id',
                'called_foreign_key' => $pivotTable->pivot_columns[0]->name,
                'relation_type' => Relationships::BELONGS_TO
            ];

            $this->final_relationships[$pivot_model][] = [
                'called_model' => $second_model,
                'caller_primary_key' => $pivotTable->primary_keys[1]->name ?? 'id',
                'called_primary_key' => $this->schema->tables[$pivotTable->pivot_columns[1]->referenced_table]->primary_key[0] ?? 'id',
                'called_foreign_key' => $pivotTable->pivot_columns[1]->name,
                'relation_type' => Relationships::BELONGS_TO
            ];
        }
    }

    private function resolveRelation(Table $table, Column $column): void
    {
        $main_model = $this->getModelNameByTable($table->name);
        $related_model = $this->getModelNameByTable($column->referenced_table);

        if ($column->unique) {
            $this->final_relationships[$main_model][] = [
                'called_model' => $related_model,
                'caller_primary_key' => $table->primary_keys[0]->name ?? 'id',
                'called_primary_key' => $column->referenced_column,
                'called_foreign_key' => $column->name,
                'relation_type' => Relationships::BELONGS_TO
            ];

            $this->final_relationships[$related_model][] = [
                'called_model' => $main_model,
                'caller_foreign_key' => $column->name,
                'relation_type' => Relationships::HAS_ONE
            ];

            return;
        }

        $this->final_relationships[$main_model][] = [
            'called_model' => $related_model,
            'caller_primary_key' => $table->primary_keys[0]->name ?? 'id',
            'called_primary_key' => $column->referenced_column,
            'called_foreign_key' => $column->name,
            'relation_type' => Relationships::BELONGS_TO
        ];

        $this->final_relationships[$related_model][] = [
            'called_model' => $main_model,
            'caller_primary_key' => 'id',
            'caller_foreign_key' => $column->name,
            'relation_type' => Relationships::HAS_MANY
        ];
    }

    private function setTraceRelations()
    {
        $tree = (new TraceRelation($this->final_relationships))->mountTree();
    }

    private function getModelNameByTable(string $table_name): string
    {
        return StrTool::singularize($table_name);
    }
}
