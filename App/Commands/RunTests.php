<?php

namespace App\Commands;

use App\Exceptions\MissingClassParameter;
use App\Tests\Conductor;
use Core\Cosmo\Cosmo;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'test:run',
    description: 'This command run the Tests in Dummy directory.'
)]
class RunTests extends Command
{
    private const TESTS_ROOT_PATH = 'App\\Tests\\Dummys\\';
    private mixed $step = null;
    private Cosmo $cosmo;

    public function __construct()
    {
        $this->cosmo = new Cosmo();

        parent::__construct();
    }

    /**
     * @throws MissingClassParameter
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->cosmo->start($output, true, true);

        $this->cosmo->title('tests', 'run');

        $this->cosmo->indexRow('test', 'run');

        $tests = require_once('App/Tests/dummy_queue.php');

        $results = [];

        if (!empty($tests)) {
            foreach ($tests as $key => $options) {
                if (!isset($options['class'])) {
                    throw new MissingClassParameter();
                }

                $conductor = new Conductor($options['class'], $options['count'] ?? 1);

                if ($options['store_in-database'] ?? true) {
                    $conductor->storeInDatabase();
                }

                if (isset($options['test_name'])) {
                    $conductor->testName($options['test_name']);
                }

                $results[] = $conductor->run();

            }

            $table = new Table($output);

            $tableStyle = new TableStyle();

            $tableStyle->setHorizontalBorderChars('<fg=white>-</>')
                ->setVerticalBorderChars('<fg=white>|</>')
                ->setDefaultCrossingChar('<fg=white>+</>');
            $table->setStyle($tableStyle);

            $table->setHeaders([
                    '<fg=green;options=bold>NAME</>',
                    '<fg=green;options=bold>FULL TIME</>',
                    '<fg=green;options=bold>AVERAGE</>',
                    '<fg=green;options=bold>DATE</>',
                    '<fg=green;options=bold>COUNT</>']
            );

            $rows = [];

            foreach ($results as $result) {
                $row = [
                    "<fg=bright-blue;options=bold>{$result['name']}</>",
                    "<fg=white;options=bold>{$result['operation_full_time_in_milliseconds']} ms</>",
                    "<fg=white;options=bold>{$result['average_in_milliseconds']} ms</>",
                    "<fg=white;options=bold>{$result['date']}</>",
                    "<fg=white;options=bold>{$result['count']}</>",
                ];

                $rows[] = $row;
            }

            $table->setRows($rows);
            $table->render();
        }

        $this->cosmo->finish();
        $this->cosmo->commandSuccess('test');
        return Command::SUCCESS;
    }
}
