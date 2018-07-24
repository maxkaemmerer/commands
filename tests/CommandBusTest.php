<?php

declare(strict_types=1);

namespace MaxKaemmerer\Commands\Tests\Unit;


use MaxKaemmerer\Commands\Command;
use MaxKaemmerer\Commands\CommandBus;
use MaxKaemmerer\Commands\CommandHandler;
use MaxKaemmerer\Commands\Exception\CommandHandlerNotFound;
use MaxKaemmerer\Commands\Implementations\SimpleCommandBus;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

class CommandBusTest extends TestCase
{
    private const COMMAND_NAME = 'abcMyCommand';

    /** @var CommandBus */
    private $commandBus;

    /** @var CommandHandler|ObjectProphecy */
    private $commandHandler;

    /** @var Command|ObjectProphecy */
    private $command;

    /** @var CommandHandler|ObjectProphecy */
    private $otherCommandHandler;

    public function setUp()
    {
        $this->commandBus = new SimpleCommandBus();
    }

    /**
     * @test
     **/
    public function should_implement_CommandBus(): void
    {
        self::assertInstanceOf(CommandBus::class, $this->commandBus);
    }

    /**
     * @test
     **/
    public function should_register_handler_and_handler_should_be_called(): void
    {
        $this->givenAMatchingCommandHandlerIsRegistered();

        $this->thenTheCommandHandlerShouldBeCalled();

        $this->whenACommandIsDispatched();
    }

    /**
     * @test
     **/
    public function should_throw_CommandHandlerNotFound_if_no_matching_CommandHandler_exists(): void
    {

        // Given no matching CommandHandlers are registered

        $this->thenACommandHandlerNotFoundExceptionShouldBeThrown();

        $this->whenACommandIsDispatched();
    }

    /**
     * @test
     **/
    public function should_overwrite_existing_CommandHandler_when_a_new_one_handling_the_same_command_is_registered(
    ): void
    {
        $this->givenAMatchingCommandHandlerIsRegistered();

        $this->givenAnotherMatchingCommandHandlerIsRegistered();

        $this->thenTheMostRecentlyRegisteredCommandHandlerShouldBeCalled();

        $this->whenACommandIsDispatched();
    }

    private function givenAMatchingCommandHandlerIsRegistered(): void
    {
        $this->commandHandler = $this->prophesize(CommandHandler::class);
        $this->commandHandler->handles()->willReturn(self::COMMAND_NAME);
        $this->commandBus->registerHandler($this->commandHandler->reveal());
    }

    private function thenTheCommandHandlerShouldBeCalled(): void
    {
        $this->commandHandler->__invoke(Argument::type(Command::class))->shouldBeCalled();
    }

    private function whenACommandIsDispatched(): void
    {
        $this->command = $this->prophesize(Command::class);
        $this->command->name()->willReturn(self::COMMAND_NAME);
        $this->commandBus->dispatch($this->command->reveal());
    }

    private function thenACommandHandlerNotFoundExceptionShouldBeThrown(): void
    {
        $this->expectException(CommandHandlerNotFound::class);
        $this->expectExceptionMessage(sprintf(CommandHandlerNotFound::MESSAGE, self::COMMAND_NAME));
        $this->expectExceptionCode(500);
    }

    private function givenAnotherMatchingCommandHandlerIsRegistered(): void
    {
        $this->otherCommandHandler = $this->prophesize(CommandHandler::class);
        $this->otherCommandHandler->handles()->willReturn(self::COMMAND_NAME);
        $this->commandBus->registerHandler($this->otherCommandHandler->reveal());
    }

    private function thenTheMostRecentlyRegisteredCommandHandlerShouldBeCalled(): void
    {
        $this->otherCommandHandler->__invoke(Argument::type(Command::class))->shouldBeCalled();
    }
}
