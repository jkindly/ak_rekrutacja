<?php

declare(strict_types=1);

namespace App\Core\User\Application\Command\CreateUser;

use App\Core\User\Domain\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateUserHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(CreateUserCommand $command): void
    {
        $this->entityManager->persist(new User(
            $command->email,
        ));

        $this->entityManager->flush();
    }
}
