<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setPassword($this->userPasswordEncoder->encodePassword(
            $user,
            '123abc!'
        ));
        $user->setName('Test');
        $user->setSurname('Testopoulos');
        $user->setEmail('test@test.test');
        $manager->persist($user);

        $user = new User();
        $user->setPassword($this->userPasswordEncoder->encodePassword(
            $user,
            '123abc!'
        ));
        $user->setName('Testos');
        $user->setSurname('Testidis');
        $user->setEmail('testidis@test.test');
        $manager->persist($user);

        $user = new User();
        $user->setPassword($this->userPasswordEncoder->encodePassword(
            $user,
            '123abc!'
        ));
        $user->setName('Testman');
        $user->setSurname('Testou');
        $user->setEmail('testou@test.test');
        $manager->persist($user);

        $manager->flush();
    }
}
