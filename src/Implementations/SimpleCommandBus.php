<?php

declare(strict_types=1);

namespace MaxKaemmerer\Commands\Implementations;


use MaxKaemmerer\Commands\Command;
use MaxKaemmerer\Commands\CommandBus;
use MaxKaemmerer\Commands\CommandHandler;
use MaxKaemmerer\Commands\Exception\CommandHandlerNotFound;

final class SimpleCommandBus implements CommandBus
{

    /**
     * @var CommandHandler[]
     */
    private $handlers = [];

    /**
     * @param Command $command
     * Dispatches the command, calling its corresponding handlers CommandHandler::handle($command) method.
     * Throws CommandHandlerNotFound if no matching handler is registered.
     * @throws CommandHandlerNotFound
     */
    public function dispatch(Command $command): void
    {
        if (!array_key_exists($command->name(), $this->handlers)) {
            throw CommandHandlerNotFound::fromCommandName($command->name());
        }

        /** @var CommandHandler $commandHandler */
        $commandHandler = $this->handlers[$command->name()];

        $commandHandler($command);
    }

    /**
     * @param CommandHandler $handler
     * Registers a command handler using its CommandHandler::handles() method to determine which command is handled by it.
     * Only one handler can be registered per command. If multiple handlers are registered for the same command the last one ist used.
     */
    public function registerHandler(CommandHandler $handler): void
    {
        $this->handlers[$handler->handles()] = $handler;
    }
}