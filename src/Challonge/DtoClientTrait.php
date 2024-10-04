<?php

namespace Reflex\Challonge;

use CuyZ\Valinor\MapperBuilder;
use Reflex\Challonge\DTO\BaseDto;

trait DtoClientTrait
{
    protected ClientWrapper $client;

    /**
     * Kinda gross but oh well.
     * @param ClientWrapper $client
     * @param array $data
     * @return static
     */
    public static function fromResponse(ClientWrapper $client, array $data): self
    {
        return (new MapperBuilder())
            ->enableFlexibleCasting()
            // challonge doesn't lock their API, and constantly adds new fields
            ->allowSuperfluousKeys()
            // property types aren't documented anywhere
            ->allowPermissiveTypes()
            ->mapper()
            ->map(self::class, [...$data, "client" => $client]);
    }

    /**
     * @param ClientWrapper $client
     */
    public function setClient(ClientWrapper $client): void
    {
        $this->client = $client;
    }
}
