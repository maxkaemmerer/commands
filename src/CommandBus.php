<?php

declare(strict_types=1);

namespace MaxKaemmerer\Commands;


use MaxKaemmerer\Commands\Exception\CommandHandlerNotFound;

interface CommandBus
{
    /**
     * @param Command $command
     * Dispatches the command, calling its corresponding handlers CommandHandler::handle($command) method.
     * Throws CommandHandlerNotFound if no matching handler is registered.
     * @throws CommandHandlerNotFound
     */
    public function dispatch(Command $command): void;

    /**
     * @param CommandHandler $handler
     * Registers a command handler using its CommandHandler::handles() method to determine which command is handled by it.
     */
    public function registerHandler(CommandHandler $handler): void;
}