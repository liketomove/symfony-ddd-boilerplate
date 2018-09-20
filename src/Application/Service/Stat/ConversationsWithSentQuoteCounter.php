<?php

namespace App\Application\Service\Stat;

use App\Application\Service\StatService;

class ConversationsWithSentQuoteCounter extends StatService
{
    public function execute($request)
    {
        $response = $this->doExecute($request);

        return $response;
    }

    public function doExecute(SentQuoteRequestsRequest $request): int
    {
        $from = $request->getDateFrom();
        $to = $request->getDateTo();

        $requests = $this->statRepository->conversationsWithSentQuotes(
            new \DateTimeImmutable($from),
            new \DateTimeImmutable($to)
        );

        return count($requests);
    }
}
