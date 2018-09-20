<?php

namespace App\Application\Service\Stat;

use App\Application\Service\StatService;

class SentQuotesCounterService extends StatService
{
    public function execute($request): int
    {
        $response = $this->doExecute($request);

        return $response;
    }

    public function doExecute(SentQuoteRequestsRequest $request): int
    {
        $from = $request->getDateFrom();
        $to = $request->getDateTo();

        $requests = $this->statRepository->sentQuotes(
            new \DateTimeImmutable($from),
            new \DateTimeImmutable($to)
        );

        return count($requests);
    }
}
