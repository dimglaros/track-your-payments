<?php

namespace App\MessageHandler;

use App\Entity\Payment;
use App\Message\PaymentCreation;
use App\Repository\PaymentTypeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class PaymentCreationHandler implements MessageHandlerInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var PaymentTypeRepository
     */
    private $paymentTypeRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        UserRepository $userRepository,
        PaymentTypeRepository $paymentTypeRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->userRepository = $userRepository;
        $this->paymentTypeRepository = $paymentTypeRepository;
        $this->entityManager = $entityManager;
    }

    public function __invoke(PaymentCreation $message)
    {
        $payment = new Payment();
        $payment->setUser($this->userRepository->find($message->getUserId()));
        $payment->setType($this->paymentTypeRepository->find(
            $message->getPaymentTypeId()
        ));
        $payment->setDescription($message->getDescription());
        $payment->setAmount($message->getAmount());
        $this->entityManager->persist($payment);
        $this->entityManager->flush();
    }
}
