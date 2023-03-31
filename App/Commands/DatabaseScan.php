<?php

namespace App\Commands;

use App\AutoRelation\Tests\DummyTest;
use Core\Cosmo\Cosmo;
use Core\Database\JoinBuilder;
use Core\Database\Query\ChangeTableBuilder;
use Core\Database\Schema;
use Core\Helpers\FileDirManager;
use Core\Helpers\StringFormatter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'database:scan',
)]
class DatabaseScan extends Command
{
    private const COLUMNS_TABLE = 'information_schema.columns';
    private const KEY_COLUMN_USAGE_TABLE = 'information_schema.KEY_COLUMN_USAGE';
    private const CONSTRAINTS_TABLE = 'information_schema.TABLE_CONSTRAINTS';
    private const COLUMN_NAME_COLUMN = 'COLUMN_NAME';
    private const TABLE_SCHEMA_COLUMN = 'TABLE_SCHEMA';
    private const TABLE_NAME_COLUMN = 'TABLE_NAME';
    private const CONSTRAINT_NAME_COLUMN = 'CONSTRAINT_NAME';
    private const CONSTRAINT_SCHEMA_COLUMN = 'CONSTRAINT_SCHEMA';
    private const MODEL_ROOT_PATH = 'App\\Models\\';
    private const MODEL_DUMMY = 'MountModel';
    private const CONTENT_PATH = __DIR__ . '\\..\\..\\Stubs\\model.php';
    private const MODEL_TABLE_NAME = 'table_name';

    private Cosmo $cosmo;
    private array $models;
    private string $schema;
    private array $tables;
    private array $relations;
    private array $database_skeleton;
    private array $full_database_skeleton;
    private array $model_tests;

    public function __construct()
    {
        $this->cosmo = new Cosmo();
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->schema = $_ENV['DB_DATABASE'];
        $with_pivot_model = $input->getOption('pivot-model');

        $this->discoverPivotRelations($with_pivot_model);
        $this->discoverDirectRelations();
        $this->createModelsCLasses();
        if ($input->getOption('test')) {
            $this->mountDummyInserts($with_pivot_model);
        }

        dd($this->model_tests);
        return Command::SUCCESS;
    }

    private function mountDummyInserts(bool $with_pivot_model)
    {
        $pivot_tables = [];

        foreach ($this->full_database_skeleton as $table_name => $table) {
            if (isset($this->database_skeleton[$table_name]['foreign_keys']) &&
                $this->database_skeleton[$table_name]['foreign_keys'] === []
            ) {
                $pivot_tables[] = $table_name;

                continue;
            }

//            Schema::alter($table_name, function (ChangeTableBuilder $table) {
//                $table->int('dummy_column');
//                return $table;
//            });

            $insert_values = ['dummy_column' => 1];

            foreach ($table as $column) {
                $insert_values[$column['COLUMN_NAME']] = 1;
            }

            $this->model_tests[] = DummyTest::modelInsert(
                'App\\Models\\' . StringFormatter::singularize(StringFormatter::firstLetterUppercase($table_name)),
                $insert_values
            );
        }

        foreach ($pivot_tables as $pivot_table) {
//            Schema::alter($pivot_table, function (ChangeTableBuilder $table) {
//                $table->int('dummy_column');
//                return $table;
//            });

            $insert_values = ['dummy_column' => 1];

            foreach ($this->full_database_skeleton[$pivot_table] as $column) {
                $insert_values[$column['COLUMN_NAME']] = 1;
            }

            if ($with_pivot_model) {
                $this->model_tests[] = DummyTest::modelInsert(
                    'App\\Models\\' . StringFormatter::singularize(StringFormatter::firstLetterUppercase($pivot_table)),
                    $insert_values
                );
            } else {
                Schema::insert($pivot_table, $insert_values)->get();
            }
        }

//        $this->deleteDummyInserts($pivot_tables);
    }

    private function deleteDummyInserts(array $pivot_tables)
    {
        $tables = $this->tables;

        if (!empty($pivot_tables)) {
            foreach ($pivot_tables as $pivot_table) {
                foreach ($tables as $index => $table) {
                    if ($pivot_table === $table) {
                        unset($tables[$index]);
                    }
                }

                Schema::delete($pivot_table)->whereIsNotNull('dummy_column', $pivot_table)->get();
                Schema::alter($pivot_table, function (ChangeTableBuilder $table) {
                    $table->dropColumn('dummy_column');
                    return $table;
                });
            }
        }

        foreach ($tables as $table) {
            Schema::delete($table)->whereIsNotNull('dummy_column', $table);
            Schema::alter($table, function (ChangeTableBuilder $table) {
                $table->dropColumn('dummy_column');
                return $table;
            });
        }
    }

    private function createModelsCLasses()
    {
        foreach ($this->models as $model_name => $model) {
            if (empty($model['relations'])) {
                FileDirManager::createFileByTemplate(
                    $model_name . '.php',
                    self::MODEL_ROOT_PATH,
                    __DIR__ . '/../Stubs/scanned_model.php',
//                    __DIR__ . '\\..\\..\\vendor\\vortex-framework\\vortex-framework\\Core\\Stubs\\scanned_model.php',
                    [
                        self::MODEL_DUMMY => $model_name,
                        self::MODEL_TABLE_NAME => $model['table'],
                        'public $relations;' => ''
                    ]
                );

                continue;
            }

            $relation_string = $this->generateStringRelationByModel($model);

            FileDirManager::createFileByTemplate(
                $model_name . '.php',
                self::MODEL_ROOT_PATH,
                __DIR__ . '/../Stubs/scanned_model.php',
//                __DIR__ . '\\..\\..\\vendor\\vortex-framework\\vortex-framework\\Core\\Stubs\\scanned_model.php',
                [self::MODEL_DUMMY => $model_name,
                    self::MODEL_TABLE_NAME => $model['table'],
                    'public $relations;' => $relation_string
                ]
            );
        }
    }

    private function generateStringRelationByModel(array $model): string
    {
        $string_relations = '';
        $count = 0;

        foreach ($model['relations'] as $relation_type => $relations) {
            if ($count > 0) {
                $string_relations .= "\n\n    ";
            }

            $string_relations .= $this->makeRelationString($relations, $relation_type);
            $count++;
        }

        return $string_relations;
    }

    public function makeRelationString(array $relations, string $relation_type): string
    {
        $string = '';

        foreach ($relations as $index => $relation) {
            if ($index > 0) {
                $string .= "\n\n    ";
            }

            $string .= 'public function ' .
                $relation['relation_name'] .
                "(): SelectBuilder\n    {\n        return \$this->{$relation_type}(" .
                $relation['class'] .
                ");\n    }";
        }

        return $string;
    }


    private function discoverDirectRelations(): void
    {
        foreach ($this->database_skeleton as $table_name => $table) {
            if (!isset($table['foreign_keys'])) {
                continue;
            }

            $model = StringFormatter::firstLetterUppercase(StringFormatter::singularize($table_name));

            foreach ($table['foreign_keys'] as $foreign_key) {
                $related_model_name = StringFormatter::firstLetterUppercase(
                    StringFormatter::singularize($foreign_key['REFERENCED_TABLE_NAME']));

                if ($foreign_key['COLUMN_KEY'] === 'UNI') {
                    $this->models[$model]['relations']['belongsTo'][] = [
                        'class' => "$related_model_name::class",
                        'relation_name' => strtolower(StringFormatter::singularize($foreign_key['REFERENCED_TABLE_NAME']))
                    ];
                    $this->models[$related_model_name]['relations']['hasOne'][] = [
                        'class' => "$model::class",
                        'relation_name' => strtolower($model)
                    ];

                    continue;
                }

                $this->models[$model]['relations']['belongsTo'][] = [
                    'class' => "$related_model_name::class",
                    'relation_name' => strtolower(StringFormatter::singularize($foreign_key['REFERENCED_TABLE_NAME']))
                ];

                $this->models[$related_model_name]['relations']['hasMany'][] = [
                    'class' => "$model::class",
                    'relation_name' => strtolower(StringFormatter::pluralize($model)),
                ];
            }
        }
    }

    private function discoverPivotRelations(bool $with_pivot_model)
    {
        foreach ($this->database_skeleton as $table_name => $table) {
            if (
                !isset($table['foreign_keys']) ||
                !strpos($table_name, '_') ||
                count($table['foreign_keys']) < 2
            ) {
                continue;
            }

            $effective_keys_count = 0;

            $expected_keys = array_map(function (string $key) {
                return "{$key}_id";
            }, explode('_', $table_name));

            foreach ($table['foreign_keys'] as $foreign_key) {
                if (in_array($foreign_key['COLUMN_NAME'], $expected_keys)) {
                    $effective_keys_count++;
                }
            }

            if ($effective_keys_count === 2) {
                foreach ($table['foreign_keys'] as $foreign_key) {
                    foreach ($expected_keys as $index => $expected_key) {
                        if ($foreign_key['COLUMN_NAME'] === $expected_key) {
                            $model_name = StringFormatter::firstLetterUppercase(
                                StringFormatter::singularize($foreign_key['REFERENCED_TABLE_NAME'])
                            );

                            $related_model_name = StringFormatter::firstLetterUppercase(
                                StringFormatter::singularize(substr(
                                        $expected_keys[$index === 0 ? 1 : 0],
                                        0,
                                        -3)
                                )
                            );

                            if ($foreign_key['COLUMN_KEY'] === 'UNI') {
                                $this->models[$model_name]['relations']['hasOne'][] = [
                                    'class' => "$related_model_name::class",
                                    'pivot' => $table_name,
                                    'related_table' => StringFormatter::pluralize(strtolower($related_model_name)),
                                    'relation_name' => strtolower($related_model_name)
                                ];
                            } else {
                                $this->models[$model_name]['relations']['hasMany'][] = [
                                    'class' => "$related_model_name::class",
                                    'pivot' => $table_name,
                                    'related_table' => StringFormatter::pluralize(strtolower($related_model_name)),
                                    'relation_name' => StringFormatter::pluralize(strtolower($related_model_name)),
                                ];
                            }

                            if ($with_pivot_model) {
                                $this->models[StringFormatter::retrieveCamelCase($table_name)]['relations']['belongsTo'][] = [
                                    'class' => "$related_model_name::class",
                                    'pivot' => $table_name,
                                    'related_table' => StringFormatter::pluralize(strtolower($related_model_name)),
                                    'relation_name' => strtolower($related_model_name)
                                ];
                            }

                            foreach ($this->database_skeleton[$table_name]['foreign_keys'] as $fk_index => $fk) {
                                if ($fk['COLUMN_NAME'] === $expected_key) {
                                    unset($this->database_skeleton[$table_name]['foreign_keys'][$fk_index]);

                                    break;
                                }
                            }
                        }
                    }
                }
                if (!$with_pivot_model) {
                    unset($this->models[StringFormatter::retrieveCamelCase($table_name)]);
                }
            }
        }
    }

    protected function configure()
    {
        $this->setHelp('Create a new Controller.')
            ->addOption('pivot-model', 'p')
            ->addOption('test', 't');
    }
}
