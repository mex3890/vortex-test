<?php

namespace App\Commands;

use App\SchemaEngine\AutoRelation\DiscoverRelations;
use App\SchemaEngine\AutoRelation\ModelManager;
use App\SchemaEngine\SchemaHelper;
use Core\Cosmo\Cosmo;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'db:translate',
)]
class DatabaseTranslate extends Command
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
        $this->cosmo->title('DB', 'translate');

        if (!SchemaHelper::checkIfDatabaseIsSetAndExists($this->cosmo, $this)) {
            return Command::FAILURE;
        }

        $with_pivot_model = $input->getOption('pivot-model');

        $relations = (new DiscoverRelations())->setRelations();

        ModelManager::mount($relations['relationships'], $relations['models'], $this->cosmo);

        $this->cosmo->finish();
        $this->cosmo->commandSuccess('DB translate');

        return Command::SUCCESS;
    }

    protected function configure()
    {
        $this->setHelp('Translate the database inside Vortex Models structure.')
            ->addOption('pivot-model', 'p')
            ->addOption('test', 't');
    }
}
