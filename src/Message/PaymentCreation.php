<?php

namespace App\Message;

class PaymentCreation
{
    /** @var string */
    private $userId;
    /** @var string */
    private $paymentTypeId;
    /** @var string */
    private $description;
    /** @var int */
    private $amount;

    public function __construct(
        string $userId,
        string $paymentTypeId,
        string $description,
        int $amount
    ) {
        $this->userId = $userId;
        $this->paymentTypeId = $paymentTypeId;
        $this->description = $description;
        $this->amount = $amount;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getPaymentTypeId(): string
    {
        return $this->paymentTypeId;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}
