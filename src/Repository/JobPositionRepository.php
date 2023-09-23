<?php

namespace Freelance\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;

class JobPositionRepository
{
    private Connection $connection;

    public function __construct(ManagerRegistry $registry)
    {
        $this->connection = $registry->getConnection();
    }

    public function getPosition(): int
    {
        $sql = <<<SQL
SELECT id FROM feed_parser_job_position LIMIT 1;
SQL;

        return $this->connection->executeQuery($sql)->fetchOne() ?? 0;
    }

    public function setPosition(int $position): void
    {
        $sql = <<<SQL
UPDATE feed_parser_job_position set id = :position;
SQL;
        $stmt = $this->connection->prepare($sql);
        $stmt->executeStatement(['position' => $position]);
    }

}
