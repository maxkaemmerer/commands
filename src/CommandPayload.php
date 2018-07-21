<?php

declare(strict_types=1);

namespace MaxKaemmerer\Commands;


use MaxKaemmerer\Commands\Exception\PayloadItemNotFound;

interface CommandPayload
{
    /**
     * @param string $key
     * @return mixed
     * Fetches an item from the payload by its key. Throws a PayloadItemNotFound Exception if no item with this key is found.
     * @throws PayloadItemNotFound
     */
    public function get(string $key);

    /**
     * @return array
     */
    public function toArray(): array;
}