<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::new()->create([
            'email' => 'johny.silverhand@afterlife.ai',
            'roles' => ['ROLE_ADMIN'],
        ]);

        UserFactory::new()->createMany(5, function(int $i) {
            return [
                'email' => "user{$i}@example.com",
            ];
        });

        $manager->flush();
    }
}
