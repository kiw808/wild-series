<?php


namespace App\DataFixtures;


use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

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

            $manager->persist($actor);

            $this->addReference('actor_' . $key, $actor);
        }
        $manager->flush();
    }
}
