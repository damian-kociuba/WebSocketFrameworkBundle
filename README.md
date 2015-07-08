WebSocketFrameworkBundle
======================
Application framework for symfony application using websockets

Instalation
-----------

1. Add to your composer.json:

        [...]
        "require" : {
            [...]
            "DKociuba/WSFrameworkBundle" : "dev-master"
        },
        "repositories" : [{
            "type" : "vcs",
            "url" : "https://github.com/damian-kociuba/WebSocketFrameworkBundle.git"
        }],
        [...]


2. Run

        composer update DKociuba/WSFrameworkBundle

3. Add to your app/AppKernel.php

        $bundles = array(
            [...]
            new DKociuba\WSFrameworkBundle\DKociubaWSFrameworkBundle(),
        );

Use
---

1. Add command class:

        <?php

        # src/Acme/DemoBundle/WSCommand/Demo.php

        namespace Acme\DemoBundle\WSCommand;

        use DKociuba\WSFrameworkBundle\WSServer\Message;
        use DKociuba\WSFrameworkBundle\WSServer\Command\WSCommandInterface;
        use Acme\DemoBundle\WSResponse\DemoRsp;

        class Demo implements WSCommandInterface {

            public function run(Message $message) {
                $connection = $message->getConnection();

                $response = new DemoRsp();
                $response->setContent('some text');

                $connection->send($response);
            }

            public function getCommandName() {
                return 'demo';
            }

            /**
             * @param array $parameters
             * @throws \Exception
             */
            public function validateParameters(array $parameters) {
                if (!isset($parameters['param'])) {
                    throw new \Exception('Parameter "param" is required');
                }
            }

            public function getType() {
                return WSCommandInterface::ON_MESSAGE_TYPE;
            }

        }

2. Add response class (message from server to browser)

        <?php

        # src/Acme/DemoBundle/WSResponse/DemoRsp.php

        namespace Acme\DemoBundle\WSResponse;

        use DKociuba\WSFrameworkBundle\WSServer\Response\Response;

        /**
         * Example of using Response helper
         *
         * @author DKociuba
         */
        class DemoRsp extends Response {

            private $content;

            public function getData() {
                return array(
                    'content' => $this->content,
                );
            }

            public function getName() {
                return 'DemoRsp';
            }

            public function getContent() {
                return $this->content;
            }

            public function setContent($content) {
                $this->content = $content;
            }

        }

3. Register command class in services.yml

        services:
            [...]
        acme.demo.command.demo:
            class: Acme\DemoBundle\WSCommand\Demo

        dkociuba_ws_framework.ws_manager:
            class: DKociuba\WSFrameworkBundle\WSServer\CommandManager
            arguments: 
                - 
                    - @acme.demo.command.demo

4. Run server:

        php app/console wsserver:start 8080

5. Now you can send message via websocket from JavaScript:

        var REMOTE_ADDR = "ws://localhost:8080";
        var socket = new WebSocket(REMOTE_ADDR);

        socket.onmessage = function (msg) {
            console.log(msg);

        };
        socket.onclose = function () {
            console.log('close');

        };

        socket.onopen = function () {
            console.log('Open');
        };

        function sendMsg() {
            socket.send(JSON.stringify({
                command:'demo',
                parameters: {
                    param: 1
                }
            }));
        }

        sendMsg();

In browser console you should see message from server.