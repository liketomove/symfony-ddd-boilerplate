<?php

namespace App\Application\Service\Stat;

use App\Application\Service\StatService;
use App\Domain\Stat\StatRepository;

class SentQuotesDetailsService extends StatService
{
    public function __construct(StatRepository $statRepository)
    {
        parent::__construct($statRepository);
    }

    public function execute($request): int
    {
        $response = $this->doExecute($request);

        return $response;
    }

    public function doExecute(SentQuoteRequestsRequest $request): int
    {
        $from = $request->getDateFrom();
        $to = $request->getDateTo();
        $proposals = $request->getProposals();
        $rooms = $request->getRooms();
        $prices = $request->getPrices();

        $requests = $this->statRepository->quoteRequestsReceived(new \DateTimeImmutable($from), new \DateTimeImmutable($to));

        $conversationsId = $this->extractConversationsId($requests);

        $sentQuoteConversations = $this->statRepository->repliedRequests($conversationsId);

//        $requests = $this->statRepository->sentQuotesDetails(
//            new \DateTimeImmutable($from),
//            new \DateTimeImmutable($to),
//            $proposals,
//            $rooms
//        );
//
//        return count($requests);
    }
}
