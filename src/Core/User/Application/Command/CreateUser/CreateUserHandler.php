<?php

declare(strict_types=1);

namespace App\Core\User\Application\Command\CreateUser;

use App\Common\Mailer\SMPTMailer;
use App\Core\User\Domain\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateUserHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly SMPTMailer $mailer,
    ) {
    }

    public function __invoke(CreateUserCommand $command): void
    {
        $this->entityManager->persist(new User(
            $command->email,
        ));

        $this->entityManager->flush();

        $this->mailer->send(
            $command->email,
            'Rejestracja konta',
            'Zarejestrowano konto w systemie. Aktywacja konta trwa do 24h',
        );
    }
}
