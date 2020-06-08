<?php


namespace App\DataFixtures;

use App\Entity\Season;
use \Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('en_UK');

        for ($i = 1; $i <= 30; $i++) {
            $season = new Season();
            $season->setNumber($i);
            $season->setYear($faker->year);
            $season->setDescription($faker->text(100));
            if ($i <= 5) {
                $season->setProgram($this->getReference('program_0'));
            }
            elseif ($i > 5 && $i <= 10) {
                $season->setProgram($this->getReference('program_1'));
            }
            elseif ($i > 10 && $i <= 15) {
                $season->setProgram($this->getReference('program_2'));
            }
            elseif ($i > 15 && $i <= 20) {
                $season->setProgram($this->getReference('program_3'));
            }
            elseif ($i > 20 && $i <= 25) {
                $season->setProgram($this->getReference('program_4'));
            }
            else {
                $season->setProgram($this->getReference('program_5'));
            }

            $manager->persist($season);

            $this->addReference('season_' . $i, $season);
        }
        $manager->flush();
    }
}
