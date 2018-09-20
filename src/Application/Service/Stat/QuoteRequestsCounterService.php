<?php

namespace App\Application\Service\Stat;

use App\Application\Service\StatService;

class QuoteRequestsCounterService extends StatService
{
    public function execute($request): int
    {
        $res = $this->doExecute($request);

        return $res;
    }

    public function doExecute(SentQuoteRequestsRequest $request): int
    {
        $from = $request->getDateFrom();
        $to = $request->getDateTo();

        $requests = $this->statRepository->quoteRequestsReceived(
            new \DateTimeImmutable($from),
            new \DateTimeImmutable($to)
        );

        return count($requests);
    }
}
