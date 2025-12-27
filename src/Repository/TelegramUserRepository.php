<?php

namespace App\Repository;

use App\Entity\TelegramUser;
use App\Interfaces\TelegramUserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TelegramUserRepository extends ServiceEntityRepository implements TelegramUserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TelegramUser::class);
    }

    public function save(TelegramUser $user): void
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findByTelegramId(string $telegramId): ?TelegramUser
    {
        return $this->findOneBy(['telegramId' => $telegramId]);
    }
}
