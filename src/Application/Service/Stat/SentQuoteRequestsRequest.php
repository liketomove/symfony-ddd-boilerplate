<?php

namespace App\Application\Service\Stat;

class SentQuoteRequestsRequest
{
    private $dateFrom;
    private $dateTo;
    private $proposals;
    private $rooms;
    private $prices;

    public function __construct(string $dateFrom, string $dateTo, ?int $proposals = null, ?int $rooms = null, ?int $prices = null)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->proposals = $proposals;
        $this->rooms = $rooms;
        $this->prices = $prices;
    }

    public function getDateFrom(): string
    {
        return $this->dateFrom;
    }

    public function getDateTo(): string
    {
        return $this->dateTo;
    }

    public function getProposals(): ?int
    {
        return $this->proposals;
    }

    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    public function getPrices(): ?int
    {
        return $this->prices;
    }
}
