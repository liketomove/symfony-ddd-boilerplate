<?php

namespace App\Application\Service\Stat;

use App\Application\Service\StatService;

class RepliedRequestsCounterService extends StatService
{
    public function execute($request)
    {
        $res = $this->doExecute($request);

        return $res;
    }

    public function doExecute(SentQuoteRequestsRequest $request): int
    {
        $from = $request->getDateFrom();
        $to = $request->getDateTo();

        $conversationsIdRaw = $this->statRepository->quoteRequestsReceived(
            new \DateTimeImmutable($from),
            new \DateTimeImmutable($to)
        );

        $conversationsId = $this->extractConversationsId($conversationsIdRaw);

        $quotes = $this->statRepository->repliedRequests($conversationsId);

        return count($quotes);
    }
}
