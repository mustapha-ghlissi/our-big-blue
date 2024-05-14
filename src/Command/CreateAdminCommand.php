<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Create an admin account.',
)]
class CreateAdminCommand extends Command
{
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question');
        $emailQuestion = new Question('Enter an email or a username [admin]: ', 'admin');
        $email = $helper->ask($input, $output, $emailQuestion);
        $passQuestion = new Question('Enter a password [admin]: ', 'admin');
        $password = $helper->ask($input, $output, $passQuestion);

        $user = new User();
        $user->setRoles(['ROLE_ADMIN'])
            ->setEmail($email)
            ->setPassword($password)
        ;

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success('Admin account created successfully.');

        return Command::SUCCESS;
    }
}
