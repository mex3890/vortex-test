<?php

namespace App\Commands;

use App\AutoRelation\ModelsManager;
use App\AutoRelation\SchemaMapper;
use Core\Cosmo\Cosmo;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'Test',
)]
class Test extends Command
{
    private Cosmo $cosmo;

    public function __construct()
    {
        $this->cosmo = new Cosmo();
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $schema = new SchemaMapper();
        $modelManager = new ModelsManager($schema->tables, false, false);

        $table = new Table($output);
        $tableStyle = new TableStyle();
        $tableStyle
            ->setHorizontalBorderChars('<fg=white>-</>')
            ->setVerticalBorderChars('<fg=white>|</>')
            ->setDefaultCrossingChar('<fg=white>+</>');
        $table->setStyle($tableStyle);

        $table->setHeaders([
            '<fg=green;options=bold>MODEL</>',
            '<fg=green;options=bold>RELATION TYPE</>',
            '<fg=green;options=bold>RELATED MODEL</>',
            '<fg=green;options=bold>TABLE</>',
            '<fg=green;options=bold>FOREIGN KEY</>',
            '<fg=green;options=bold>REFERENCED COLUMN</>',
            '<fg=green;options=bold>REFERENCED TABLE</>',
            '<fg=green;options=bold>PIVOT TABLE</>'
        ]);

        $rows = [];

        foreach ($modelManager->getModels() as $model_name => $model) {
            if (!isset($model['relations'])) {
                $rows[] = [
                    '<fg=bright-blue;options=bold>' . $model_name . '</>',
                    '<fg=gray;options=bold>Undefined</>',
                    '<fg=gray;options=bold>Undefined</>',
                    $model['table'],
                    '<fg=gray;options=bold>Undefined</>',
                    '<fg=gray;options=bold>Undefined</>',
                    '<fg=gray;options=bold>Undefined</>',
                    '<fg=gray;options=bold>Undefined</>',
                ];

                continue;
            }

            foreach ($model['relations'] as $relation_name => $relations) {
                foreach ($relations as $relation) {
                    $rows[] = [
                        '<fg=bright-blue;options=bold>' . $model_name . '</>',
                        $relation_name,
                        '<fg=bright-blue;options=bold>' . $relation['class'] . '</>',
                        $model['table'],
                        $relation['foreign_key'],
                        $relation['referenced_column'],
                        $relation['referenced_table'],
                        $relation['pivot_table'] ?? '<fg=gray;options=bold>Undefined</>',
                    ];
                }
            }
        }

        $table->setRows($rows);
        $table->render();

        return Command::SUCCESS;
    }

    protected function configure()
    {
        $this->setHelp('Create a new Controller.')
            ->addOption('pivot-model', 'p')
            ->addOption('ternary-relations', 'tr')
            ->addOption('test', 't');
    }
}
