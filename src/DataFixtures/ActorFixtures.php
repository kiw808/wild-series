<?php


namespace App\DataFixtures;


use App\Entity\Actor;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    const ACTORS = [
        'Andrew Lincoln',
        'Norman Reedus',
        'Lauren Cohan',
        'Danai Gurira',
    ];

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        // Generate actors with constant
        foreach (self::ACTORS as $key => $name) {
            $actor = new Actor();
            $actor->setName($name);
            if ($name == 'Andrew Lincoln') {
                $actor->addProgram($this->getReference('walking_0'));
                $actor->addProgram($this->getReference('walking_5'));
            }
            else {
                $actor->addProgram($this->getReference('program_0'));
            }

            $slugify = new Slugify();
            $slug = $slugify->generate($actor->getName());
            $actor->setSlug($slug);

            $manager->persist($actor);

            $this->addReference('actor_' . $key, $actor);
        }

        // Generate with faker
        $faker = Faker\Factory::create('en_UK');
        for ($i = 0; $i <= 50; $i++) {
            $actor = new Actor();
            $actor->setName($faker->firstName . ' ' . $faker->lastName);

            if ($i <= 15) {
                $actor->addProgram($this->getReference('program_1'));
            }
            elseif ($i > 10 && $i <= 20) {
                $actor->addProgram($this->getReference('program_2'));
            }
            elseif ($i > 20 && $i <= 30) {
                $actor->addProgram($this->getReference('program_3'));
            }
            elseif ($i > 30 && $i <= 40) {
                $actor->addProgram($this->getReference('program_4'));
            }
            elseif ($i > 40 && $i <= 50) {
                $actor->addProgram($this->getReference('program_5'));
            }

            $slugify = new Slugify();
            $slug = $slugify->generate($actor->getName());
            $actor->setSlug($slug);

            $manager->persist($actor);
        }
        $manager->flush();
    }
}
