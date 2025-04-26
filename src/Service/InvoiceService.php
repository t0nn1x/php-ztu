<?php

namespace App\Service;

use App\Entity\Invoice;
use App\Entity\Reservation;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;

class InvoiceService extends AbstractEntityService
{
    private InvoiceRepository $invoiceRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        InvoiceRepository $invoiceRepository
    ) {
        parent::__construct($entityManager);
        $this->invoiceRepository = $invoiceRepository;
    }

    protected function getEntityClass(): string
    {
        return Invoice::class;
    }

    /**
     * Find invoices by reservation
     */
    public function findByReservation(Reservation $reservation): array
    {
        return $this->invoiceRepository->findBy(['reservation' => $reservation], ['createdAt' => 'DESC']);
    }

    /**
     * Find invoices by status
     */
    public function findByStatus(string $status): array
    {
        return $this->invoiceRepository->findBy(['status' => $status], ['createdAt' => 'DESC']);
    }

    /**
     * Find overdue invoices
     */
    public function findOverdue(): array
    {
        return $this->invoiceRepository->findOverdue();
    }

    /**
     * Generate invoice number
     */
    public function generateInvoiceNumber(): string
    {
        return 'INV-' . date('Ymd') . '-' . uniqid();
    }
} 
