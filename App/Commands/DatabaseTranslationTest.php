<?php

namespace App\Commands;

use App\SchemaEngine\AutoRelation\DiscoverRelations;
use App\SchemaEngine\SchemaHelper;
use Core\Cosmo\Cosmo;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'DB:translation_test',
)]
class DatabaseTranslationTest extends Command
{
    private Cosmo $cosmo;

    public function __construct()
    {
        $this->cosmo = new Cosmo();
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->cosmo->start($output, true);
        $this->cosmo->title('DB', 'Test relations');

        if (!SchemaHelper::checkIfDatabaseIsSetAndExists($this->cosmo, $this)) {
            return Command::FAILURE;
        }

        $test_file_name = $input->getArgument('test_name');

        if (!str_ends_with($test_file_name, '.php')) {
            $test_file_name .= '.php';
        }

        $relations = (new DiscoverRelations())->setRelations()->getFormattedTraces();
        $expected_relations = require_once __DIR__ . "/../SchemaEngine/AutoRelation/Tests/$test_file_name";
        $relations_count = count($relations);
        $expected_relations_count = count($expected_relations);

        foreach ($relations as $index => $relation) {
            if (($expected_index = array_search($relation, $expected_relations)) !== false) {
                $this->cosmo->fileSuccessRow($relation, 'MATCH');
                unset($expected_relations[$expected_index]);
                unset($relations[$index]);
            }
        }

        if (!empty($relations)) {
            foreach ($relations as $relation) {
                $this->cosmo->fileFailRow($relation, 'NOT EXPECTED');
            }
        }

        if (!empty($expected_relations)) {
            foreach ($expected_relations as $expected_relation) {
                $this->cosmo->fileFailRow($expected_relation, 'EXPECTED');
            }
        }

        $table = new Table($output);

        $tableStyle = new TableStyle();
        $tableStyle->setHorizontalBorderChars('-')
            ->setVerticalBorderChars('<fg=white>|</>')
            ->setDefaultCrossingChar('<fg=white>+</>');
        $table->setStyle($tableStyle);

        $table->setHeaderTitle('TEST RESULTS');

        $table->setRows([
            ['<fg=green;options=bold>EXPECTED RELATIONS COUNT</>', $expected_relations_count],
            ['<fg=green;options=bold>GENERATED RELATIONS COUNT</>', $relations_count],
            ['<fg=green;options=bold>UNMAPPED EXPECTED RELATIONS COUNT</>', count($expected_relations)],
            ['<fg=green;options=bold>UNEXPECTED GENERATED RELATIONS COUNT</>', count($relations)],
            [
                '<fg=green;options=bold>PASSED</>',
                (count($relations) === 0 && count($expected_relations) === 0) ? 'TRUE' : 'FALSE'
            ],
        ]);

        $output->writeln('');
        $table->render();

        $this->cosmo->finish();
        $this->cosmo->commandSuccess('DB test relations');

        return Command::SUCCESS;
    }

    protected function configure()
    {
        $this->addArgument('test_name', InputArgument::REQUIRED, '');
    }
}
