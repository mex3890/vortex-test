<?php

namespace App\Commands;

use App\SchemaEngine\AutoRelation\DiscoverRelations;
use App\SchemaEngine\SchemaHelper;
use Core\Cosmo\Cosmo;
use Core\Helpers\StrTool;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'DB:relationships',
)]
class DatabaseRelations extends Command
{
    private Cosmo $cosmo;

    public function __construct()
    {
        $this->cosmo = new Cosmo();
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->cosmo->start($output);
        $this->cosmo->title('DB', 'Relations');

        if (!SchemaHelper::checkIfDatabaseIsSetAndExists($this->cosmo, $this)) {
            return Command::FAILURE;
        }

        $with_pivot_model = $input->getOption('pivot-model');

        $relations = (new DiscoverRelations())->setRelations();

        $table = $this->mountCosmoTable($output, $relations);
        $table->render();

        $this->cosmo->finish();
        $this->cosmo->commandSuccess('DB display');

        return Command::SUCCESS;
    }

    protected function configure()
    {
        $this->setHelp('Display table of database relationships')
            ->addOption('pivot-model', 'p')
            ->addOption('test', 't');
    }

    private function mountCosmoTable(OutputInterface $output, array $relations): Table
    {
        $table = new Table($output);

        $tableStyle = new TableStyle();
        $tableStyle
            ->setHorizontalBorderChars('<fg=white>-</>')
            ->setVerticalBorderChars('<fg=white>|</>')
            ->setDefaultCrossingChar('<fg=white>+</>');
        $table->setStyle($tableStyle);

        $table->setHeaders([
            '<fg=green;options=bold>CALLER MODEL</>',
            '<fg=green;options=bold>CALLED MODEL</>',
            '<fg=green;options=bold>RELATION TYPE</>',
            '<fg=green;options=bold>CALLER PK</>',
            '<fg=green;options=bold>CALLER FK</>',
            '<fg=green;options=bold>PIVOT TABLE</>',
            '<fg=green;options=bold>CALLED PK</>',
            '<fg=green;options=bold>CALLED FK</>',
        ]);

        $rows = $this->mountTableRows($relations);

        $table->setRows($rows);

        return $table;
    }

    private function mountTableRows(array $relations): array
    {
        $rows = [];

        foreach ($relations['relationships'] as $model_name => $relations) {
            foreach ($relations as $relation) {
                $rows[] = $this->mountColumnRow($relation, $model_name);
            }
        }

        return $rows;
    }

    private function mountColumnRow(array $relation, string $model_name): array
    {
        return [
            '<fg=bright-blue;options=bold>' . StrTool::firstLetterUppercase($model_name) . '</>',
            isset($relation['called_model'])
                ? '<fg=white;options=bold>' . StrTool::firstLetterUppercase($relation['called_model']) . '</>'
                : '<fg=gray;options=bold>unset</>',
            isset($relation['relation_type'])
                ? '<fg=yellow;options=bold>' . $relation['relation_type']->value . '</>'
                : '<fg=gray;options=bold>unset</>',
            isset($relation['caller_primary_key'])
                ? '<fg=yellow;options=bold>' . $relation['caller_primary_key'] . '</>'
                : '<fg=gray;options=bold>unset</>',
            isset($relation['caller_foreign_key'])
                ? '<fg=yellow;options=bold>' . $relation['caller_foreign_key'] .'</>'
                : '<fg=gray;options=bold>unset</>',
            isset($relation['pivot_table'])
                ? '<fg=yellow;options=bold>' . $relation['pivot_table'] . '</>'
                : '<fg=gray;options=bold>unset</>',
            isset($relation['called_primary_key'])
                ? '<fg=yellow;options=bold>' . $relation['called_primary_key'] . '</>'
                : '<fg=gray;options=bold>unset</>',
            isset($relation['called_foreign_key'])
                ? '<fg=yellow;options=bold>' . $relation['called_foreign_key'] . '</>'
                : '<fg=gray;options=bold>unset</>',
        ];
    }
}
