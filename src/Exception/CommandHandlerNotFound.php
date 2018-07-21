<?php

declare(strict_types=1);

namespace MaxKaemmerer\Commands\Exception;


class CommandHandlerNotFound extends CommandException
{
    /**
     * @param string $commandName
     * @return CommandHandlerNotFound
     */
    public const MESSAGE = 'No CommandHandler found for command "%s".';

    public static function fromCommandName(string $commandName): CommandHandlerNotFound
    {
        return new self(
            sprintf(
                self::MESSAGE, $commandName
            ), 500
        );
    }
}