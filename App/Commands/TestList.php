<?php

namespace App\Commands;

use App\Models\Test;
use Core\Cosmo\Cosmo;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'test:list',
)]
class TestList extends Command
{
    private Cosmo $cosmo;

    public function __construct()
    {
        $this->cosmo = new Cosmo();
        parent::__construct();
    }

    protected function configure()
    {
        $this->setHelp('List the last tests.')
            ->addArgument('count', InputArgument::OPTIONAL, 'Number of test rows');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->cosmo->start($output, true, true);

        $this->cosmo->title('tests', 'run');

        $this->cosmo->indexRow('test', 'run');

        $count = $input->getArgument('count') ?? 20;

        $table = new Table($output);

        $tableStyle = new TableStyle();

        $tableStyle->setHorizontalBorderChars('<fg=white>-</>')
            ->setVerticalBorderChars('<fg=white>|</>')
            ->setDefaultCrossingChar('<fg=white>+</>');
        $table->setStyle($tableStyle);

        $table->setHeaders([
                '<fg=green;options=bold>ID</>',
                '<fg=green;options=bold>NAME</>',
                '<fg=green;options=bold>FULL TIME</>',
                '<fg=green;options=bold>AVERAGE</>',
                '<fg=green;options=bold>DATE</>',
                '<fg=green;options=bold>COUNT</>']
        );

        $rows = [];

        $tests = Test::find()->orderBy(['id' => 'DESC'])->limit($count)->get();

        foreach ($tests as $key => $test) {
            $row = [
                "<fg=bright-blue;options=bold>$test->id</>",
                "<fg=white;options=bold>$test->name</>",
                "<fg=white;options=bold>$test->operation_full_time_in_milliseconds ms</>",
                "<fg=white;options=bold>$test->average_in_milliseconds ms</>",
                "<fg=white;options=bold>$test->date</>",
                "<fg=white;options=bold>$test->count</>",
            ];

            $rows[] = $row;
        }

        $table->setRows($rows);
        $table->render();

        $this->cosmo->finish();
        $this->cosmo->commandSuccess('test');
        return Command::SUCCESS;
    }
}
