<?php

namespace App\SchemaEngine\AutoRelation;

use App\Exceptions\RelationNotFound;
use Core\Cosmo\Cosmo;
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
     * @param array $relations
     * @param array $tables
     * @param Cosmo $cosmo
     */
    private function __construct(
        private readonly array $relations,
        private readonly array $tables,
        private readonly Cosmo $cosmo
    )
    {
        foreach ($this->relations as $model_name => $relation) {
            $final_model = StrTool::firstLetterUppercase($model_name);

            try {
                $this->mountModelClass(
                    $final_model,
                    $this->tables[$model_name],
                    $this->mountStringClassRelations($relation)
                );

                $this->cosmo->fileSuccessRow($final_model, 'created');
            } catch (RelationNotFound $exception) {
                $this->cosmo->fileFailRow($final_model, 'Failed');
                $this->cosmo->fileFailRow($exception->getMessage(), 'X');
            }
        }
    }

    /**
     * @param array $relations
     * @return string
     * @throws RelationNotFound
     */
    private function mountStringClassRelations(array $relations): string
    {
        $final_relations_string = '';

        foreach ($relations as $relation) {
            if ($final_relations_string !== '') {
                $final_relations_string .= "\n\n";
            }

            $class_name = StrTool::absoluteUpperFistLetter($relation['called_model']);

            $final_relations_string .= match ($relation['relation_type']) {
                Relationships::HAS_ONE => $this->mountSingleRelationString(
                    StrTool::singularize($relation['called_model']),
                    Relationships::HAS_ONE->value,
                    [
                        "$class_name::class",
                        "'{$relation['caller_foreign_key']}'",
                    ]
                ),
                Relationships::BELONGS_TO => $this->mountSingleRelationString(
                    StrTool::pluralize($relation['called_model']),
                    Relationships::BELONGS_TO->value,
                    [
                        "$class_name::class",
                        "'{$relation['caller_primary_key']}'",
                        "'{$relation['called_primary_key']}'",
                        "'{$relation['called_foreign_key']}'",
                    ]
                ),
                Relationships::BELONGS_TO_MANY => $this->mountSingleRelationString(
                    StrTool::pluralize($relation['called_model']),
                    Relationships::BELONGS_TO_MANY->value,
                    [
                        "$class_name::class",
                        "'{$relation['caller_primary_key']}'",
                        "'{$relation['caller_foreign_key']}'",
                        "'{$relation['pivot_table']}'",
                        "'{$relation['called_primary_key']}'",
                        "'{$relation['called_foreign_key']}'",
                    ]
                ),
                Relationships::HAS_MANY => $this->mountSingleRelationString(
                    strtolower(StrTool::pluralize($relation['called_model'])),
                    Relationships::HAS_MANY->value,
                    [
                        "$class_name::class",
                        "'{$relation['caller_primary_key']}'",
                        "'{$relation['caller_foreign_key']}'",
                    ]
                ),
                default => throw new RelationNotFound($relation['relation_type']),
            };
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
