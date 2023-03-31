<?php

namespace App\Commands;

use App\AutoRelation\ModelsManager;
use App\AutoRelation\SchemaMapper;
use Core\Cosmo\Cosmo;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
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
        $models = new ModelsManager($schema->tables, true, true);
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
