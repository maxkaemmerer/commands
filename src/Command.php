<?php

declare(strict_types=1);

namespace MaxKaemmerer\Commands;


interface Command
{

    /**
     * @return CommandPayload
     */
    public function payload(): CommandPayload;

    /**
     * @return string
     * The name of this command, it is recommended to use the fully qualified class name.
     */
    public function name(): string;
}