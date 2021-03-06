<?php

declare(strict_types=1);

namespace MaxKaemmerer\Commands\Exception;


class PayloadItemNotFound extends CommandException
{
    /**
     * @param string $key
     * @return PayloadItemNotFound
     */
    public const MESSAGE = 'No item with key "%s" found in CommandPayload.';

    public static function fromKey(string $key): PayloadItemNotFound
    {
        return new self(
            sprintf(
                self::MESSAGE, $key
            ), 500
        );
    }
}