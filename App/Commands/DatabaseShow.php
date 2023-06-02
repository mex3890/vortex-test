<?php

namespace App\Commands;

use App\SchemaEngine\Column;
use App\SchemaEngine\SchemaMapper;
use App\SchemaEngine\Table as SchemaTable;
use Core\Cosmo\Cosmo;
use Core\Database\Query;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'db:display',
)]
class DatabaseShow extends Command
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
        $this->cosmo->title('DB', 'Display');

        if (!isset($_ENV['DB_DATABASE']) || $_ENV['DB_DATABASE'] === '') {
                $this->cosmo->finish();
                $this->cosmo->failMessage("Database name not found in .env", $this);
                $this->cosmo->commandFail('DB display');

                return Command::FAILURE;
        }

        $exist_database = Query::select('information_schema.schemata', 'schema_name ')
            ->where('schema_name', $_ENV['DB_DATABASE'])->get()->count();

        if ($exist_database !== 1) {
            $this->cosmo->finish();
            $this->cosmo->failMessage('Database with name "' . $_ENV['DB_DATABASE'] . '" not found', $this);
            $this->cosmo->commandFail('DB display');

            return Command::FAILURE;
        }

        $table = $this->mountCosmoTable($output, new SchemaMapper());
        $table->render();

        $this->cosmo->finish();
        $this->cosmo->commandSuccess('DB display');

        return Command::SUCCESS;
    }

    private function mountCosmoTable(OutputInterface $output, SchemaMapper $schema): Table
    {
        $table = new Table($output);

        $tableStyle = new TableStyle();
        $tableStyle
            ->setHorizontalBorderChars('<fg=white>-</>')
            ->setVerticalBorderChars('<fg=white>|</>')
            ->setDefaultCrossingChar('<fg=white>+</>');
        $table->setStyle($tableStyle);

        $table->setHeaders([
            '<fg=green;options=bold>TABLE</>',
            '<fg=green;options=bold>COLUMN</>',
            '<fg=green;options=bold>TYPE</>',
            '<fg=green;options=bold>DEFAULT</>',
            '<fg=green;options=bold>NULLABLE</>',
            '<fg=green;options=bold>PK</>',
            '<fg=green;options=bold>FK</>',
            '<fg=green;options=bold>UNIQUE</>',
            '<fg=green;options=bold>AUTO INCR.</>',
            '<fg=green;options=bold>CAS. ON DEL.</>',
            '<fg=green;options=bold>CAS. ON UPD.</>',
            '<fg=green;options=bold>MAX LEN.</>',
            '<fg=green;options=bold>OPTIONS</>',
        ]);

        $rows = $this->mountTableRows($schema);

        $table->setRows($rows);

        return $table;
    }

    private function mountTableRows(SchemaMapper $schema): array
    {
        $rows = [];

        /** @var SchemaTable $table */
        foreach ($schema->tables as $table) {
            foreach ($table->columns as $column) {
                $rows[] = $this->mountColumnRow($column, $table);
            }
        }

        return $rows;
    }

    private function mountColumnRow(Column $column, SchemaTable $table): array
    {
        return [
            '<fg=bright-blue;options=bold>' . $table->name . '</>',
            '<fg=white;options=bold>' . $column->name . '</>',
            '<fg=white;options=bold>' . $column->type . '</>',
            $column->default
                ? '<fg=white;options=bold>' . $column->default . '</>'
                : '<fg=gray;options=bold>unset</>',
            $column->nullable
                ? '<fg=yellow;options=bold>true</>'
                : '<fg=gray;options=bold>false</>',
            $column->primary_key
                ? '<fg=yellow;options=bold>true</>'
                : '<fg=gray;options=bold>false</>',
            $column->foreign_key
                ? '<fg=yellow;options=bold>true</>'
                : '<fg=gray;options=bold>false</>',
            $column->unique
                ? '<fg=yellow;options=bold>true</>'
                : '<fg=gray;options=bold>false</>',
            $column->auto_increment
                ? '<fg=yellow;options=bold>true</>'
                : '<fg=gray;options=bold>false</>',
            $column->cascade_on_delete
                ? '<fg=yellow;options=bold>true</>'
                : '<fg=gray;options=bold>false</>',
            $column->cascade_on_update
                ? '<fg=yellow;options=bold>true</>'
                : '<fg=gray;options=bold>false</>',
            $column->max_length && $column->type !== 'set' && $column->type !== 'enum'
                ? '<fg=white;options=bold>' . $column->max_length . '</>'
                : '<fg=gray;options=bold>unset</>',
            $column->options
                ? '<fg=white;options=bold>' . $column->options . '</>'
                : '<fg=gray;options=bold>unset</>',
        ];
    }
}
