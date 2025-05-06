<?php

namespace App\DataFixtures;

use App\Entity\Code;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class CodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $users = $manager->getRepository(User::class)->findAll();
        $faker->setDefaultTimezone(date_default_timezone_get());

        for ($i = 0; $i < 200; $i++) {
            $discount = $faker->numberBetween(5, 90);
            $prefix = $this->generatePrefix($faker);
            $dateFrom = $faker->dateTimeBetween('-' . $faker->numberBetween(0, 15) . ' days', '+15 days');

            $code = new Code();
            $code->setUser($users[array_rand($users)]);
            $code->setName(strtoupper($prefix . $this->generateSuffix($faker, $discount)));
            $code->setDiscount($discount);
            $code->setDomainName($this->generateDomain($prefix, $faker));
            $code->setValidFrom($dateFrom);
            $code->setValidUntil($faker->dateTimeBetween((clone $dateFrom), (clone $dateFrom)->modify('+8 months')));
            $code->setIsVipOnly($discount > 60 || $faker->boolean());

            $manager->persist($code);
        }

        $manager->flush();
    }

    private function generatePrefix(Generator $faker): mixed
    {
        $prefixes = array_merge(
            [
                'AMAZON',
                'OPENAI',
                'GOOGLE',
                'APPLE',
                'MICROSOFT',
                'UBER',
                'NETFLIX',
                'PROMO',
                'DEAL',
                'OPENCLASSROOMS',
                'DISCORD',
                'SLACK'
            ],
            [
                strtoupper($faker->company()),
                strtoupper($faker->firstName()),
                strtoupper($faker->word()),
            ]
        );

        return $faker->randomElement($prefixes);
    }

    private function generateSuffix(Generator $faker, int $discount): string
    {
        return $faker->randomElement([
            $faker->numberBetween(10, 99) . 'ANS',
            $discount,
            $faker->year(),
        ]);
    }

    private function generateDomain(string $prefix, Generator $faker): string
    {
        $customSuffixes = ['com', 'fr', 'net', 'io', 'org', 'shop', 'promo', 'deal'];
        $suffix = $faker->randomElement($customSuffixes);

        return strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $prefix)) . '.' . $suffix;
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
