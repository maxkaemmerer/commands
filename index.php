<?php

use MaxKaemmerer\Commands\Command;
use MaxKaemmerer\Commands\CommandHandler;
use MaxKaemmerer\Commands\CommandPayload;
use MaxKaemmerer\Commands\Exception\CommandException;
use MaxKaemmerer\Commands\Implementations\Payload;
use MaxKaemmerer\Commands\Implementations\SimpleCommandBus;

require_once __DIR__ . '/vendor/autoload.php';

try {
    $commandBus = new SimpleCommandBus();

    $commandBus->registerHandler(new class implements CommandHandler
    {

        /**
         * @param Command $command
         */
        public function handle(Command $command): void
        {
            echo $command->payload()->get('message');
        }

        /**
         * @return string
         * The name of the command this handler handles. Command::name()
         */
        public function handles(): string
        {
            return 'commandName';
        }

    });

    $commandBus->dispatch(new class implements Command
    {

        /**
         * @return CommandPayload
         */
        public function payload(): CommandPayload
        {
            // You would of course set the Payload in the constructor of your Command implementation
            return Payload::fromArray(['message' => 'Hello World!']);
        }

        /**
         * @return string
         * The name of this command, it is recommended to use the fully qualified class name.
         */
        public function name(): string
        {
            return 'commandName';
        }

    });

} catch (CommandException $exception) {
    error_log($exception->getMessage());
}


