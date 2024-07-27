<?php

namespace Dentiman\ScheduleBundle\Engine;

interface CommandRunnerInterface
{
    /**
     * @param string $commandName
     * @param array $commandArguments
     */
    public function run($commandName, $commandArguments = []);
}
