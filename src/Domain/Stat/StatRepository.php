<?php

namespace App\Domain\Stat;

interface StatRepository
{
    /**
     * Ritorna tutte le richieste di preventivo provenienti dai principali portali e siti in un dato periodo
     *
     * @param \DateTimeImmutable $from
     * @param \DateTimeImmutable $to
     * @param int|null $userId
     * @return array
     */
    public function quoteRequestsReceived(\DateTimeImmutable $from, \DateTimeImmutable $to, ?int $userId = null): array;

    /**
     * Ritorna le conversazioni con preventivi inviate in risposta a determinate richieste
     *
     * @param array $conversationIDs
     * @param int|null $userId
     * @return array
     */
    public function repliedRequests(array $conversationIDs, ?int $userId = null): array;

    /**
     * Ritorna tutte le mail inviate con preventivi in un determinato periodo
     *
     * @param \DateTimeImmutable $from
     * @param \DateTimeImmutable $to
     * @param int|null $userId
     * @return array
     */
    public function sentQuotes(\DateTimeImmutable $from, \DateTimeImmutable $to, ?int $userId = null): array;

    /**
     * Ritorna tutte le conversazioni che contengono preventivi inviate in un determinato periodo
     *
     * @param \DateTimeImmutable $from
     * @param \DateTimeImmutable $to
     * @param int|null $userId
     * @return array
     */
    public function conversationsWithSentQuotes(\DateTimeImmutable $from, \DateTimeImmutable $to, ?int $userId = null): array;

//    public function sentQuotesDetails(\DateTimeImmutable $from, \DateTimeImmutable $to, int $proposals = 1, ?int $userId = null): array;
}
