<?php

declare(strict_types=1);

namespace App\Core\User\UserInterface\Cli;

use App\Core\User\Application\Command\CreateUser\CreateUserCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(
    name: 'app:user:create',
    description: 'Dodawanie nowego użytkownika'
)]
class CreateUser extends Command
{
    public function __construct(
        private readonly MessageBusInterface $bus,
        private readonly ValidatorInterface $validator,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = $input->getArgument('email');

        $constraints = array(
            new Email(),
            new NotBlank()
        );

        $errors = $this->validator->validate($email, $constraints);

        if (count($errors) > 0) {
            $io->error('Niepoprawny adres email');

            return Command::FAILURE;
        }

        $this->bus->dispatch(new CreateUserCommand($email));

        $io->success('Użytkownik został dodany');

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED);
    }
}
