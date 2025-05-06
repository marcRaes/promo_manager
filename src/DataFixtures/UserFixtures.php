<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $persistentEmail = $_ENV['APP_PERSISTENT_USER_EMAIL'] ?? null;
        $persistentPassword = $_ENV['APP_PERSISTENT_USER_PASSWORD'] ?? null;
        $persistentRoles = explode(',', $_ENV['APP_PERSISTENT_USER_ROLES'] ?? 'ROLE_USER');

        if ($persistentEmail && $persistentPassword) {
            $persistentUser = $this->createUser($persistentEmail, $persistentPassword, $persistentRoles);
            $manager->persist($persistentUser);
        }

        for ($i = 0; $i < 10; $i++) {
            $roles = [];

            if ($faker->boolean()) {
                $roles[] = 'ROLE_VIP';
            }

            $manager->persist(
                $this->createUser(
                    $faker->email(),
                    $faker->password(),
                    $roles
                )
            );
        }

        $manager->flush();
    }

    public function createUser(string $email, string $password, array $roles): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $password));
        $user->setRoles($roles);

        return $user;
    }
}
