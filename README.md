# maxkaemmerer/commands
[![Travis branch](https://img.shields.io/travis/maxkaemmerer/commands/master.svg?style=flat-square)](https://travis-ci.org/maxkaemmerer/commands)
[![Coveralls github](https://img.shields.io/coveralls/maxkaemmerer/commands/master.svg?style=flat-square&branch=master)](https://coveralls.io/github/maxkaemmerer/commands?branch=master)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/maxkaemmerer/commands.svg?style=flat-square)](https://packagist.org/packages/maxkaemmerer/commands)
[![Packagist](https://img.shields.io/packagist/v/maxkaemmerer/commands.svg?style=flat-square)](https://packagist.org/packages/maxkaemmerer/commands)
[![Packagist](https://img.shields.io/packagist/l/maxkaemmerer/commands.svg?style=flat-square)](https://packagist.org/packages/maxkaemmerer/commands)

## Description:

This library offers interfaces and implementations for a simple command, command-handler, command-bus structure.

This is of course not an original idea, but my preferred and fairly simple implementation.

The code is fully tested, I however do not take responsibility for use in production. 

Use at your own risk.

## Installation:

``composer require maxkaemmerer/commands``

## Usage:
Generally you don't want to register each ``CommandHandler`` by hand. You might want to use dependency injection via a container or service-manager.

(An example for the Symfony Framework would be using a ``CompilerPass``)

You would also want to inject the ``CommandBus`` itself via dependency injection wherever you need it.

Feel free to create your own implementations of ``CommandBus`` and ``Payload`` if you require something more advanced.


#### Register a Command Handler:
Register a ``CommandHandler`` to the ``CommandBus``. The ``CommandHandler``'s ``handles()`` method returns the name of the ``Command`` that it handles.

Best practice would be using the fully qualified class name of the command. ``MyCommand::class``

The ``CommandHandler::handle($command)`` method is where your actual domain logic happens.

Feel free to inject services, a container, or whatever else you need, into your ``CommandHandler``s.


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

#### Dispatch a Command:
Dispatching a ``Command`` causes the ``CommandBus`` to look for a ``CommandHandler`` who's ``CommandHandler:handles()`` method matches the ``Command``'s name, specified by ``Command:name()``, and calls it's ``CommandHandler::handle($command)`` method.

IMPORTANT: ``CommandHandler``'s and the ``CommandBus`` never return anything.

    ...
    
    // The CommandHandler was registered
    
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

#### Full Example:


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
    
Result:

    Hello World!
