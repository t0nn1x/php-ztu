<?php

namespace App\Service;

use App\Entity\Invoice;
use App\Entity\Payment;
use App\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;

class PaymentService extends AbstractEntityService
{
    private PaymentRepository $paymentRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        PaymentRepository $paymentRepository
    ) {
        parent::__construct($entityManager);
        $this->paymentRepository = $paymentRepository;
    }

    protected function getEntityClass(): string
    {
        return Payment::class;
    }

    /**
     * Find payments by invoice
     */
    public function findByInvoice(Invoice $invoice): array
    {
        return $this->paymentRepository->findBy(['invoice' => $invoice], ['createdAt' => 'DESC']);
    }

    /**
     * Find payments by status
     */
    public function findByStatus(string $status): array
    {
        return $this->paymentRepository->findBy(['status' => $status], ['createdAt' => 'DESC']);
    }

    /**
     * Find payments by payment method
     */
    public function findByPaymentMethod(string $paymentMethod): array
    {
        return $this->paymentRepository->findBy(['paymentMethod' => $paymentMethod], ['createdAt' => 'DESC']);
    }

    /**
     * Process payment
     */
    public function processPayment(Payment $payment): void
    {
        // Here you would integrate with your payment processor
        // For now, we'll just mark it as processed
        $payment->setPaymentStatus('processed');
        $this->save($payment);
    }
} 
