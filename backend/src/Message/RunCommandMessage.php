<?php

namespace App\Message;

final class RunCommandMessage
{
    /*
     * Add whatever properties & methods you need to hold the
     * data for this message class.
     */

     private $name;

     public function __construct(string $name)
     {
         $this->name = $name;
     }

    public function getCommandName(): string
    {
        return $this->name;
    }
}
