<?php

namespace App\DataFixtures;

use App\Entity\PaymentType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PaymentTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $paymentType = new PaymentType();
        $paymentType->setName('Loan');
        $manager->persist($paymentType);

        $paymentType = new PaymentType();
        $paymentType->setName('Rent');
        $manager->persist($paymentType);

        $paymentType = new PaymentType();
        $paymentType->setName('Bill');
        $manager->persist($paymentType);

        $paymentType = new PaymentType();
        $paymentType->setName('Tax');
        $manager->persist($paymentType);

        $paymentType = new PaymentType();
        $paymentType->setName('Various');
        $manager->persist($paymentType);

        $manager->flush();
    }
}
