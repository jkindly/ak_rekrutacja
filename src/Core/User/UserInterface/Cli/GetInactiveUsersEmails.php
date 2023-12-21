<?php

declare(strict_types=1);

namespace App\Core\User\UserInterface\Cli;

use App\Core\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:user:get-inactive-users-emails',
    description: 'Pobieranie emaili nieaktywnych użytkowników'
)]
class GetInactiveUsersEmails extends Command
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = $this->userRepository->getInactiveUsers();

        foreach ($users as $user) {
            $output->writeln($user->getEmail());
        }

        return Command::SUCCESS;
    }
}
