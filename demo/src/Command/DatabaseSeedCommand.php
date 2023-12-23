<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:database:seed')]
final class DatabaseSeedCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserRepository $userRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Initialize database with test data')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $users = require __DIR__.'/../../fixtures/users.php';
        $posts = require __DIR__.'/../../fixtures/posts.php';

        foreach ($users as $user) {
            $userEntity = (new User())
                ->setName($user['name'])
                ->setEmail($user['email']);

            $this->em->persist($userEntity);
        }

        $this->em->flush();

        foreach ($posts as $post) {
            $postEntity = (new Post())
                ->setUser($this->userRepository->find($post['userId']))
                ->setSubject($post['subject'])
                ->setBody($post['body'])
                ->setDate(new \DateTime($post['date']))
            ;

            $this->em->persist($postEntity);
        }

        $this->em->flush();

        $io->success('Done.');

        return Command::SUCCESS;
    }
}
