<?php

namespace App\Infrastructure\Domain\Stat\Doctrine;

use App\Domain\Stat\StatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;

class DoctrineStatRepository implements StatRepository
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Ritorna tutte le richieste di preventivo provenienti dai principali portali e siti in un dato periodo
     *
     * @param \DateTimeImmutable $from
     * @param \DateTimeImmutable $to
     * @param int|null $userId
     * @return array
     */
    public function quoteRequestsReceived(\DateTimeImmutable $from, \DateTimeImmutable $to, ?int $userId = null): array
    {
        $userCondition = !$userId ? '' : "AND u.id = $userId";

        $sql = <<<SQL
                SELECT 
                    DISTINCT c.id as conversation_id
                FROM `messages` m
                    JOIN headers h ON m.id = h.message_id
                    JOIN mailwin_master.conversations c ON m.conversation = c.id
                    JOIN mailwin_master.users u ON u.id = c.user_id
                WHERE m.subject NOT LIKE '***%'
                AND m.from_address != u.email
                AND m.subject NOT LIKE '%Re:%'
                AND m.subject NOT LIKE '%Re[2]:%'
                AND m.subject NOT LIKE '%R:%'
                AND m.subject NOT LIKE '%Fwd:%'
                AND m.subject NOT LIKE '%Fw:%'
                AND m.subject NOT LIKE '%I:%'
                AND (
                    m.subject LIKE '%rivivia%'
                    OR m.from_address = 'mail@entrainhotel.com'
                    OR m.from_address = 'mail@bambininriviera.it'
                    OR m.from_address = 'richiesta@info-alberghi.com'
                    OR m.from_address = 'mail@hotel.rimini.it'
                    OR m.from_address = 'mail@italhotels.net'
                    OR m.from_address = 'mail@adriasonline.it'
                )
                AND m.date BETWEEN '{$from->format('Y-m-d')}' AND '{$to->format('Y-m-d')}'
                {$userCondition}
SQL;


        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('conversation_id', 'conversation_id');
        $query = $this->entityManager->createNativeQuery($sql, $rsm);

        $requests = $query->getResult();

        return $requests;
    }

    /**
     * Ritorna le conversazioni con preventivi inviate in risposta a determinate richieste
     *
     * @param array $conversationIDs
     * @param int|null $userId
     * @return array
     */
    public function repliedRequests(array $conversationIDs, ?int $userId = null): array
    {
        $userCondition = !$userId ? '' : "AND u.id = $userId";
        $conversationIDs = implode(',', $conversationIDs);

        $sql = <<<SQL
                SELECT 
                  m.conversation as conversation_id
                FROM `messages` m
                  JOIN conversations c ON c.id = m.conversation
                  JOIN mailwin_master.users u ON u.id = c.user_id
                  RIGHT JOIN stay_datas sd ON m.id = sd.message_id
                  WHERE c.id IN ({$conversationIDs})
                  {$userCondition}
                GROUP BY m.conversation
                ORDER BY m.date ASC
SQL;

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('conversation_id', 'conversation_id');
        $query = $this->entityManager->createNativeQuery($sql, $rsm);

        $quotes = $query->getResult();

        return $quotes;
    }

    /**
     * Ritorna tutte le mail inviate con preventivi in un determinato periodo
     *
     * @param \DateTimeImmutable $from
     * @param \DateTimeImmutable $to
     * @param int|null $userId
     * @return array
     */
    public function sentQuotes(\DateTimeImmutable $from, \DateTimeImmutable $to, ?int $userId = null): array
    {
        $userCondition = !$userId ? '' : "AND u.id = $userId";

        $sql = <<<SQL
            SELECT
              DISTINCT m.id as message_id,
              m.conversation as conversation_id
            FROM messages m
              JOIN conversations c on m.conversation = c.id
              JOIN users u on c.user_id = u.id
              RIGHT JOIN stay_datas sd ON m.id = sd.message_id
            WHERE m.from_address = u.email
              AND m.date BETWEEN '{$from->format('Y-m-d')}' AND '{$to->format('Y-m-d')}'
              AND sd.arrival IS NOT NULL
              {$userCondition}
            ORDER BY `sd`.`arrival`  ASC
SQL;

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('message_id', 'message_id');
        $rsm->addScalarResult('conversation_id', 'conversation_id');
        $query = $this->entityManager->createNativeQuery($sql, $rsm);

        $sentQuotes = $query->getResult();

        return $sentQuotes;
    }

    /**
     * Ritorna tutte le conversazioni che contengono preventivi inviate in un determinato periodo
     *
     * @param \DateTimeImmutable $from
     * @param \DateTimeImmutable $to
     * @param int|null $userId
     * @return array
     */
    public function conversationsWithSentQuotes(\DateTimeImmutable $from, \DateTimeImmutable $to, ?int $userId = null): array
    {
        $userCondition = !$userId ? '' : "AND u.id = $userId";

        $sql = <<<SQL
            SELECT
            DISTINCT m.id as message_id, m.conversation as conversation_id
            FROM messages m
            JOIN conversations c on m.conversation = c.id
            JOIN users u on c.user_id = u.id
            RIGHT JOIN stay_datas sd ON m.id = sd.message_id
            WHERE m.from_address = u.email
            AND m.date BETWEEN '{$from->format('Y-m-d')}' AND '{$to->format('Y-m-d')}'
            AND sd.arrival IS NOT NULL
            {$userCondition}
            GROUP BY m.conversation
            ORDER BY `sd`.`arrival`  ASC
SQL;

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('message_id', 'message_id');
        $rsm->addScalarResult('conversation_id', 'conversation_id');
        $query = $this->entityManager->createNativeQuery($sql, $rsm);

        $sentQuotes = $query->getResult();

        return $sentQuotes;
    }
//
//    public function sentQuotesDetails(\DateTimeImmutable $from, \DateTimeImmutable $to, int $proposals = 1, ?int $userId = null): array
//    {
//        $userCondition = !$userId ? '' : "AND u.id = $userId";
//
//        $sql = <<<SQL
//            SELECT
//             m.id message_id,
//             count(m.id) proposals
//            FROM `messages` m
//              JOIN stay_datas sd ON sd.message_id = m.id
//              JOIN conversations c on m.conversation = c.id
//              JOIN users u on c.user_id = u.id
//              JOIN rooms r on sd.id = r.stay_data_id
//            WHERE m.date BETWEEN '{$from->format('Y-m-d')}' AND '{$to->format('Y-m-d')}'
//              AND sd.arrival IS NOT NULL
//              AND m.from_address = u.email
//              {$userCondition}
//            GROUP BY sd.message_id
//            HAVING proposals = {$proposals}
//SQL;
//
//        $rsm = new ResultSetMapping();
//        $rsm->addScalarResult('message_id', 'message_id');
//        $query = $this->entityManager->createNativeQuery($sql, $rsm);
//
//        $sentQuotes = $query->getResult();
//
//        return $sentQuotes;
//    }
}
