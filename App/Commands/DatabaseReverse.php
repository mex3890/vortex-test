<?php

namespace App\Commands;

use App\SchemaEngine\Reverse\ReverseEngine;
use App\SchemaEngine\SchemaMapper;
use App\SchemaEngine\Table;
use Core\Cosmo\Cosmo;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'db:reverse',
)]
class DatabaseReverse extends Command
{
    private Cosmo $cosmo;
    private array $final_table_order = [];
    private array $tables_dependencies = [];
    private array $pivot_tables = [];

    public function __construct()
    {
        $this->cosmo = new Cosmo();
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $reverse = new ReverseEngine(new SchemaMapper());

        return Command::SUCCESS;
    }
}