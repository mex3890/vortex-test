<?php

namespace App\Commands;

use App\SchemaEngine\AutoRelation\DiscoverRelations;
use App\SchemaEngine\AutoRelation\ModelManager;
use App\SchemaEngine\SchemaHelper;
use Core\Cosmo\Cosmo;
use Core\Database\Query\ChangeTableBuilder;
use Core\Database\Schema;
use Core\Helpers\FileDirManager;
use Core\Helpers\StrTool;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'db:scan',
)]
class DatabaseScan extends Command
{
    private Cosmo $cosmo;
    private array $models;
    private array $database_skeleton;

    public function __construct()
    {
        $this->cosmo = new Cosmo();
        parent::__construct();
    }

    // TODO: Add option to display table with relations etc
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->cosmo->start($output);
        $this->cosmo->title('DB', 'scan');

        if (!SchemaHelper::checkIfDatabaseIsSetAndExists($this->cosmo, $this)) {
            return Command::FAILURE;
        }

        $with_pivot_model = $input->getOption('pivot-model');

        $relations = (new DiscoverRelations())->setRelations();

        ModelManager::mount($relations['relationships'], $relations['models'], $this->cosmo);

        $this->cosmo->finish();
        $this->cosmo->commandSuccess('DB scan');

        return Command::SUCCESS;
    }

    protected function configure()
    {
        $this->setHelp('Create a new Controller.')
            ->addOption('pivot-model', 'p')
            ->addOption('test', 't');
    }
}
