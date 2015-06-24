<?php

namespace DKociuba\WSFrameworkBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

/**
 * @author dkociuba
 */
class StartServerCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('wsserver:start')
                ->setDescription('Start the WebSocket server')
                ->addArgument('port', InputArgument::REQUIRED, 'Port to use');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $ws_manager = $this->getContainer()->get('dkociuba_ws_framework.ws_manager');
        $port = $input->getArgument('port');
        $server = IoServer::factory(
                        new HttpServer(
                            new WsServer(
                                $ws_manager
                            )
                        ), $port
        );
        echo "\nServer run at port $port\n";
        $server->run();
    }

}
