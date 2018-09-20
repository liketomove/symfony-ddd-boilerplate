<?php

namespace App\Application\Service;

use App\Domain\Stat\StatRepository;

abstract class StatService
{
    protected $statRepository;

    public function __construct(StatRepository $statRepository)
    {
        $this->statRepository = $statRepository;
    }

    abstract public function execute($request);

    protected function extractConversationsId(array $array): array
    {
        $conversationIds = [];

        foreach ($array as $element) {

            $conversationIds[] = $element['conversation_id'];
        }

        return $conversationIds;
    }
}
