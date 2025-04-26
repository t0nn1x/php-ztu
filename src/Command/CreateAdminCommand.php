<?php

namespace App\Command;

use App\Service\UserService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Creates an admin user'
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private UserService $userService,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('email', null, InputOption::VALUE_REQUIRED, 'Admin email')
            ->addOption('password', null, InputOption::VALUE_REQUIRED, 'Admin password')
            ->addOption('firstName', null, InputOption::VALUE_REQUIRED, 'Admin first name')
            ->addOption('lastName', null, InputOption::VALUE_REQUIRED, 'Admin last name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = $input->getOption('email') ?? $io->ask('Email');
        $password = $input->getOption('password') ?? $io->askHidden('Password');
        $firstName = $input->getOption('firstName') ?? $io->ask('First Name');
        $lastName = $input->getOption('lastName') ?? $io->ask('Last Name');

        try {
            $admin = $this->userService->createAdmin($email, $password, $firstName, $lastName);
            $io->success(sprintf('Admin user created with email: %s', $admin->getEmail()));

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }
    }
} 
