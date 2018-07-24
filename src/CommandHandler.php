<?php

declare(strict_types=1);

namespace MaxKaemmerer\Commands;


interface CommandHandler
{

    /**
     * @param Command $command
     */
    public function __invoke(Command $command): void;

    /**
     * @return string
     * The name of the command this handler handles. Command::name()
     */
    public function handles(): string;
}