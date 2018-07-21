<?php

declare(strict_types=1);

namespace MaxKaemmerer\Commands\Implementations;


use MaxKaemmerer\Commands\CommandPayload;
use MaxKaemmerer\Commands\Exception\PayloadItemNotFound;

final class Payload implements CommandPayload
{

    private $payload = [];

    private function __construct()
    {
    }


    public static function fromArray(array $payload): CommandPayload
    {
        $instance = new self();
        $instance->payload = $payload;
        return $instance;
    }

    /**
     * @param string $key
     * @return mixed
     * Fetches an item from the payload by its key. Throws a PayloadItemNotFound Exception if no item with this key is found.
     * @throws PayloadItemNotFound
     */
    public function get(string $key)
    {
        if(!array_key_exists($key, $this->payload)){
            throw PayloadItemNotFound::fromKey($key);
        }

        return $this->payload[$key];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->payload;
    }
}