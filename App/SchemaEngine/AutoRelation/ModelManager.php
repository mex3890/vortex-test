<?php

namespace App\SchemaEngine\AutoRelation;

use App\Exceptions\RelationNotFound;
use Core\Cosmo\Cosmo;
use Core\Helpers\ArrayTool;
use Core\Helpers\FileDirManager;
use Core\Helpers\StrTool;

class ModelManager
{
    private const MODEL_ROOT_PATH = 'App\\Models\\';
    private const MODEL_DUMMY = 'MountModel';
    private const MODEL_TABLE_NAME = 'table_name';
    private const STUB_PATH = __DIR__ . '/../../Stubs/scanned_model.php';

//                __DIR__ . '\\..\\..\\vendor\\vortex-framework\\vortex-framework\\Core\\Stubs\\scanned_model.php',

    public static function mount(array $relations, array $tables, Cosmo $cosmo): void
    {
        new static($relations, $tables, $cosmo);
    }

    /**
     * @param array $traces
     * @param array $tables
     * @param Cosmo $cosmo
     */
    private function __construct(
        private readonly array $traces,
        private readonly array $tables,
        private readonly Cosmo $cosmo
    )
    {
        foreach ($this->traces as $model_name => $trace) {
            $final_model = StrTool::pascalCase($model_name);

            try {
                $this->mountModelClass(
                    $final_model,
                    $this->tables[$final_model],
                    $this->mountStringClassRelations($trace)
                );

                $this->cosmo->fileSuccessRow($final_model, 'created');
            } catch (RelationNotFound $exception) {
                $this->cosmo->fileFailRow($final_model, 'Failed');
                $this->cosmo->fileFailRow($exception->getMessage(), 'X');
            }
        }
    }

    /**
     * @param array $trace
     * @return string
     */
    private function mountStringClassRelations(array $trace): string
    {
        $final_relations_string = '';

        foreach ($trace as $relations) {
            $final_relations_string .= $this->mountSingleTraceString(array_reverse($relations));
        }

        return $final_relations_string;
    }

    private function mountSingleTraceString(array $trace): string
    {
        $relation_name = [];
        $trace_relations = '';
        $returned_model = '';

        foreach ($trace as $relation) {
            $relation_name[] = $relation['called_model'];
            $trace_relations .= ($this->mountSingleRelation($relation) . '        ');
            $returned_model = $relation['called_model'];
        }


        return "public function "
            . ArrayTool::toString(array_reverse($relation_name), '_', '', '')
            . "(): SelectBuilder {\n    "
            . "return \$this->trace("
            . StrTool::pascalCase($returned_model)
            . "::class, [\n        "
            . substr($trace_relations, 0, -4)
            . "]);\n}\n\n";
    }

    private function mountSingleRelation(array $relation): string
    {
        return match ($relation['relation_type']) {
            Relationships::HAS_ONE => $this->mountSingleRelationString(
                Relationships::HAS_ONE->value,
                [
                    "{$relation['called_model']}::class",
                    "'{$relation['caller_foreign_key']}'",
                ]
            ),
            Relationships::BELONGS_TO => $this->mountSingleRelationString(
                Relationships::BELONGS_TO->value,
                [
                    "{$relation['called_model']}::class",
                    "'{$relation['caller_primary_key']}'",
                    "'{$relation['called_primary_key']}'",
                    "'{$relation['called_foreign_key']}'",
                ]
            ),
            Relationships::BELONGS_TO_MANY => $this->mountSingleRelationString(
                Relationships::BELONGS_TO_MANY->value,
                [
                    "{$relation['called_model']}::class",
                    "'{$relation['caller_primary_key']}'",
                    "'{$relation['caller_foreign_key']}'",
                    "'{$relation['pivot_table']}'",
                    "'{$relation['called_primary_key']}'",
                    "'{$relation['called_foreign_key']}'",
                ]
            ),
            Relationships::HAS_MANY => $this->mountSingleRelationString(
                Relationships::HAS_MANY->value,
                [
                    "{$relation['called_model']}::class",
                    "'{$relation['caller_primary_key']}'",
                    "'{$relation['caller_foreign_key']}'",
                ]
            ),
        };
    }

    private function mountSingleRelationString(
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

        return "\$this->enableTraceMode()->$relation_type($string_parameters),\n";
    }

    private function mountModelClass(string $model_name, string $table_name, string $relations): void
    {
        $this->createClass($model_name, $table_name, $relations);
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
}
