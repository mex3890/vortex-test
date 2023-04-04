<?php

namespace App\AutoRelation;

use Core\Helpers\FileDirManager;
use Core\Helpers\StrTool;

class ModelsManager
{
    private const MODEL_ROOT_PATH = 'App\\Models\\';
    private const MODEL_DUMMY = 'MountModel';
    private const MODEL_TABLE_NAME = 'table_name';
    private const STUB_PATH = __DIR__ . '/../Stubs/scanned_model.php';
//                __DIR__ . '\\..\\..\\vendor\\vortex-framework\\vortex-framework\\Core\\Stubs\\scanned_model.php',

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
        $this->mountModelClass();
    }

    private function discoverModels(): void
    {
        /** @var Table $table */
        foreach ($this->tables as $index => $table) {
            $model_class = $this->mountModelName($table->name);

            if ($this->with_pivot) {
                $primary_key = $table->primary_keys[0]->name;

                foreach ($table->primary_keys as $column) {
                    if ($column->name === 'id' || $column->name === 'uuid') {
                        $primary_key = $column->name;
                    }
                }

                $this->models[$model_class] = [
                    'table' => $table->name,
                    'primary_key' => $primary_key
                ];

                $this->tables[$index]->model = $model_class;

                continue;
            }

            if ($table->pivot) {
                continue;
            }

            $primary_key = $table->primary_keys[0]->name;

            foreach ($table->primary_keys as $column) {
                if ($column->name === 'id' || $column->name === 'uuid') {
                    $primary_key = $column->name;
                }
            }

            $this->models[$model_class] = [
                'table' => $table->name,
                'primary_key' => $primary_key
            ];
        }
    }

    private function mountModelName(string $table_name, bool $from_foreign_key = false): string
    {
        if ($from_foreign_key) {
            return StrTool::retrieveCamelCase(substr($table_name, 0, -3));
        }

        return StrTool::retrieveCamelCase(StrTool::singularize($table_name));
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
            $this->models[$model_name]['relations']['hasOne'][] = [
                'class' => $related_model_name,
                'referenced_column' => $foreign_key->referenced_column,
                'referenced_table' => $foreign_key->referenced_table,
                'foreign_key' => $foreign_key->name,
            ];

            $this->models[$related_model_name]['relations'][$foreign_key->unique ? 'belongsToOne' : 'hasMany'][] = [
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
            foreach ($model['relations'] as $relation_type => $relations) {
                foreach ($relations as $relation) {
                    foreach ($this->models[$relation['class']]['relations'] as $pivot_relation_type => $pivot_relations) {
                        foreach ($pivot_relations as $pivot_relation) {
//                            dd($model_name, $relation_type, $relation['class'], $pivot_relation_type, $pivot_relation['class']);
                        }
                    }
                }
            }
        }
    }

    private function mountThirdRelation(): void
    {

    }

    public function getModels(): array
    {
        return $this->models;
    }

    private function mountModelClass(): void
    {
        foreach ($this->models as $model_name => $model) {
            if (!isset($model['relations'])) {
                $this->createClass($model_name, $model['table']);

                continue;
            }

            $relations = $this->mountStringClassRelations($model_name, $model);

            $this->createClass($model_name, $model['table'], $relations);
        }
    }

    private function createClass(string $class_name, string $class_table, ?string $relations = null): void
    {
        FileDirManager::createFileByTemplate(
            $class_name . '.php',
            self::MODEL_ROOT_PATH,
            self::STUB_PATH,
            [
                self::MODEL_DUMMY => $class_name,
                self::MODEL_TABLE_NAME => $class_table,
                '// $relations' => is_null($relations) ? '' : "\n\n" . $relations,
                '// $has_relation' => is_null($relations) ? '' : 'use Core\Database\Query\SelectBuilder;',
            ]
        );
    }

    private function mountStringClassRelations(string $model_name, array $model): string
    {
        $final_relations_string = '';

        foreach ($model['relations'] as $relation_type => $relations) {
            foreach ($relations as $index => $relation) {
                if ($final_relations_string !== '') {
                    $final_relations_string .= "\n\n";
                }

                switch ($relation_type) {
                    case 'hasOne':
                        $final_relations_string .= $this->mountSingleRelationString(
                            StrTool::singularize($relation['referenced_table']),
                            'hasOne',
                            [
                                "{$relation['class']}::class",
                                "'{$model['primary_key']}'",
                                "'{$relation['referenced_column']}'",
                                "'{$relation['foreign_key']}'",
                            ]
                        );

                        break;
                    case 'belongsToOne':
                        $final_relations_string .= $this->mountSingleRelationString(
                            strtolower(StrTool::singularize($relation['class'])),
                            'belongsToOne',
                            [
                                "{$relation['class']}::class",
                                "'{$relation['foreign_key']}'",
                            ]
                        );

                        break;
                    case 'belongsToMany':
                        $caller_foreign_key = strtolower($model_name) . '_id';

                        $final_relations_string .= $this->mountSingleRelationString(
                            $relation['referenced_table'],
                            'belongsToMany',
                            [
                                "{$relation['class']}::class",
                                "'{$model['primary_key']}'",
                                "'$caller_foreign_key'",
                                "'{$relation['referenced_column']}'",
                                "'{$relation['foreign_key']}'",
                                "'{$relation['pivot_table']}'",
                            ]
                        );

                        break;
                    case 'hasMany':
                        $caller_foreign_key = strtolower($model_name) . '_id';
                        $final_relations_string .= $this->mountSingleRelationString(
                            strtolower(StrTool::pluralize($relation['class'])),
                            'hasMany',
                            [
                                "{$relation['class']}::class",
                                "'$caller_foreign_key'",
                            ]
                        );

                        break;
                }
            }
        }

        return $final_relations_string;
    }

    private function mountSingleRelationString(
        string $relation_name,
        string $relation_type,
        array  $parameters
    ): string
    {
        $string_parameters = '';

        foreach ($parameters as $index => $parameter) {
            if ($index !== 0) {
                $string_parameters .= ', ';
            }

            $string_parameters .= $parameter;
        }

        return "    public function "
            . $relation_name
            . "(): SelectBuilder\n    {\n"
            . "        return \$this->$relation_type($string_parameters);\n"
            . "    }";
    }
}
