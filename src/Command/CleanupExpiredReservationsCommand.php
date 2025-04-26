<?php

namespace App\Command;

use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:cleanup-expired-reservations',
    description: 'Cleans up expired reservations that were not completed',
)]
class CleanupExpiredReservationsCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Cleans up expired reservations that were not completed');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Cleaning up expired reservations');

        $reservationRepository = $this->entityManager->getRepository(Reservation::class);
        
        // Find reservations that:
        // 1. Have "confirmed" status
        // 2. Check-out date is in the past
        $now = new \DateTime();
        
        $qb = $reservationRepository->createQueryBuilder('r');
        $qb->where('r.status = :status')
           ->andWhere('r.checkOutDate < :now')
           ->setParameter('status', 'confirmed')
           ->setParameter('now', $now);
        
        $expiredReservations = $qb->getQuery()->getResult();
        
        $count = 0;
        foreach ($expiredReservations as $reservation) {
            $reservation->setStatus('canceled');
            $count++;
        }
        
        $this->entityManager->flush();
        
        $io->success(sprintf('Successfully processed %d expired reservations', $count));

        return Command::SUCCESS;
    }
} 
