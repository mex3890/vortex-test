<?php

namespace App\AutoRelation;

use Core\Helpers\StringFormatter;

class ModelsManager
{
    public array $models;
    private array $tables;
    private bool $with_pivot;

    public function __construct(array $tables, bool $with_pivot, bool $with_third_relations)
    {
        $this->tables = $tables;
        $this->with_pivot = $with_pivot;
        $this->discoverModels();
        $this->mountRelations();
        $this->mountThirdRelations();
        dd($this->models);
    }

    private function discoverModels(): void
    {
        /** @var Table $table */
        foreach ($this->tables as $index => $table) {
            $model_class = $this->mountModelName($table->name);

            if ($this->with_pivot) {
                $this->models[$model_class] = [
                    'table' => $table->name
                ];

                $this->tables[$index]->model = $model_class;

                continue;
            }

            if ($table->pivot) {
                continue;
            }

            $this->models[$model_class] = [
                'table' => $table->name,
            ];
        }
    }

    private function mountModelName(string $table_name, bool $from_foreign_key = false): string
    {
        if ($from_foreign_key) {
            return StringFormatter::retrieveCamelCase(substr($table_name, 0, -3));
        }

        return StringFormatter::retrieveCamelCase(StringFormatter::singularize($table_name));
    }

    private function mountRelations(): void
    {
        /** @var Table $table */
        foreach ($this->tables as $table) {
            if ($table->pivot) {
                $this->mountPivotRelations($table);
                continue;
            }

            if (!empty($table->foreign_keys)) {
                $this->mountDirectRelations($table);
            }
        }
    }

    private function mountDirectRelations(Table $table): void
    {
        /** @var Column $foreign_key */
        foreach ($table->foreign_keys as $foreign_key) {
            $model_name = $this->mountModelName($table->name);
            $related_model_name = $this->mountModelName($foreign_key->referenced_table);

            $this->models[$model_name]['relations'][$foreign_key->unique ? 'hasOne' : 'hasMany'][] = [
                'class' => $related_model_name,
                'referenced_column' => $foreign_key->referenced_column,
                'referenced_table' => $foreign_key->referenced_table,
                'foreign_key' => $foreign_key->name,
            ];

            $this->models[$related_model_name]['relations'][$foreign_key->unique ? 'belongsToOne' : 'belongsToMany'][] = [
                'class' => $model_name,
                'referenced_column' => $foreign_key->referenced_column,
                'referenced_table' => $foreign_key->referenced_table,
                'foreign_key' => $foreign_key->name,
            ];
        }
    }

    private function mountPivotRelations(Table $table): void
    {
        foreach ($table->pivot_columns as $index => $pivot_column) {
            $model_name = $this->mountModelName($table->pivot_columns[$index === 0 ? 1 : 0]->referenced_table);
            $related_model_name = $this->mountModelName($pivot_column->referenced_table);
            $table_model = $this->mountModelName($table->name);

            $this->models[$model_name]['relations'][$pivot_column->unique ? 'belongsToOne' : 'belongsToMany'][] = [
                'class' => $related_model_name,
                'referenced_column' => $pivot_column->referenced_column,
                'referenced_table' => $pivot_column->referenced_table,
                'foreign_key' => $pivot_column->name,
                'pivot_table' => $table->name,
            ];

            if ($this->with_pivot) {
                $this->models[$table_model]['relations'][$pivot_column->unique ? 'hasOne' : 'hasMany'][] = [
                    'class' => $related_model_name,
                    'referenced_column' => $pivot_column->referenced_column,
                    'referenced_table' => $pivot_column->referenced_table,
                    'foreign_key' => $pivot_column->name,
                ];
            }
        }
    }

    private function mountThirdRelations()
    {
        foreach ($this->models as $model_name => $model) {
            foreach ($model['relations'] as $relation_name => $relations) {
                foreach ($relations as $relation) {
                    foreach ($this->models[$relation['class']]['relations'] as $third_relation_name => $third_relations) {
                        foreach ($third_relations as $third_relation) {

                            $this->mountThirdRelation(
                                $model_name,
                                $model,
                                $relation_name,
                                $relation,
                                $third_relation_name,
                                $third_relation
                            );
                        }
                    }
                }
            }
        }
    }

    private function mountThirdRelation(
        string $model_name,
        array  $model,
        string $first_relation_name,
        array  $first_relation,
        string $final_relation_name,
        array  $final_relation
    ): void
    {
        if (isset($final_relation['pivot_table']) && $final_relation['pivot_table'] === $model['table']) {
            return;
        }

        $relation = [
            'main_model' => $model_name,
            'second_model' => $final_relation['class']
        ];

        switch ($first_relation_name) {
            case 'hasMany':

                break;
        }
    }
}
