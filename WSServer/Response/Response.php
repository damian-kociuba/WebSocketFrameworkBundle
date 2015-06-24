<?php

namespace DKociuba\WSFrameworkBundle\WSServer\Response;

/**
 * Helper to prepare response/messages to browser
 *
 * @author DKociuba
 */
abstract class Response {

    public function __toString() {
        return $this->getAsString();
    }

    public function getAsString() {
        return json_encode($this->getAsArray());
    }
    public function getAsArray() {
        return array(
            'command' => $this->getName(),
            'parameters' => $this->getData()
        );
    }
    
    public abstract function getData();

}
