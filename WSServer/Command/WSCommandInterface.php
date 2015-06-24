<?php

namespace DKociuba\WSFrameworkBundle\WSServer\Command;

use DKociuba\WSFrameworkBundle\WSServer\Message;
/**
 *
 * @author DKociuba
 */
interface WSCommandInterface {

    const ON_MESSAGE_TYPE = 1;
    const ON_CLOSE_TYPE = 2;
    
    public function getCommandName();

    public function validateParameters(array $parameters);
    
    public function run(Message $message);
    
    public function getType();
}
