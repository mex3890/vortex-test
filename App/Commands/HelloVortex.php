<?php

namespace App\Commands;

use Core\Cosmo\Cosmo;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'HelloVortex',
)]
class HelloVortex extends Command
{
    private Cosmo $cosmo;

    public function __construct()
    {
        $this->cosmo = new Cosmo();
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        echo "\n";
        echo <<<HTML
__     __         _                 _____                                            _
\ \   / /__  _ __| |_ _____  __    |  ___| __ __ _ _ __ ___   _____      _____  _ __| | __
 \ \ / / _ \| '__| __/ _ \ \/ /    | |_ | '__/ _` | '_ ` _ \ / _ \ \ /\ / / _ \| '__| |/ /
  \ V / (_) | |  | ||  __/>  <     |  _|| | | (_| | | | | | |  __/\ V  V / (_) | |  |   <
   \_/ \___/|_|  \___\___/_/\_\    |_|  |_|  \__,_|_| |_| |_|\___| \_/\_/ \___/|_|  |_|\_\

Bem-vindo ao Vortex framework.
O Vortex foi desenvolvido para facilitar e agilizar a criação de aplicações web utilizando PHP,
repositório no Github disponível para dúvidas e reportar bugs,
obrigado por usar o Vortex!
HTML;
        echo "\n";
        return Command::SUCCESS;
    }

    protected function configure()
    {
    }
}
