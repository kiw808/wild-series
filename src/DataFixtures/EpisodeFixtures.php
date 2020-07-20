<?php


namespace App\DataFixtures;


use App\Entity\Episode;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('en_UK');

        for ($i = 1; $i <= 90; $i++) {
            $episode = new Episode();
            $episode->setNumber($i);
            $episode->setTitle($faker->sentence(2));
            $episode->setSynopsis($faker->text(150));
            if ($i < 3) {
                $episode->setSeason($this->getReference('season_1'));
            }
            elseif ($i > 3 && $i <= 6) {
                $episode->setSeason($this->getReference('season_2'));
            }
            elseif ($i > 6 && $i <= 9) {
                $episode->setSeason($this->getReference('season_3'));
            }
            elseif ($i > 9 && $i <= 12) {
                $episode->setSeason($this->getReference('season_4'));
            }
            elseif ($i > 12 && $i <= 15) {
                $episode->setSeason($this->getReference('season_5'));
            }
            elseif ($i > 15 && $i <= 18) {
                $episode->setSeason($this->getReference('season_6'));
            }
            elseif ($i > 18 && $i <= 21) {
                $episode->setSeason($this->getReference('season_7'));
            }
            elseif ($i > 21 && $i <= 24) {
                $episode->setSeason($this->getReference('season_8'));
            }
            elseif ($i > 24 && $i <= 27) {
                $episode->setSeason($this->getReference('season_9'));
            }
            elseif ($i > 27 && $i <= 30) {
                $episode->setSeason($this->getReference('season_10'));
            }
            elseif ($i > 30 && $i <= 33) {
                $episode->setSeason($this->getReference('season_11'));
            }
            elseif ($i > 33 && $i <= 36) {
                $episode->setSeason($this->getReference('season_12'));
            }
            elseif ($i > 36 && $i <= 39) {
                $episode->setSeason($this->getReference('season_13'));
            }
            elseif ($i > 39 && $i <= 42) {
                $episode->setSeason($this->getReference('season_14'));
            }
            elseif ($i > 42 && $i <= 45) {
                $episode->setSeason($this->getReference('season_15'));
            }
            elseif ($i > 45 && $i <= 48) {
                $episode->setSeason($this->getReference('season_16'));
            }
            elseif ($i > 48 && $i <= 51) {
                $episode->setSeason($this->getReference('season_17'));
            }
            elseif ($i > 51 && $i <= 54) {
                $episode->setSeason($this->getReference('season_18'));
            }
            elseif ($i > 54 && $i <= 57) {
                $episode->setSeason($this->getReference('season_19'));
            }
            elseif ($i > 57 && $i <= 60) {
                $episode->setSeason($this->getReference('season_20'));
            }
            elseif ($i > 60 && $i <= 63) {
                $episode->setSeason($this->getReference('season_21'));
            }
            elseif ($i > 63 && $i <= 66) {
                $episode->setSeason($this->getReference('season_22'));
            }
            elseif ($i > 66 && $i <= 69) {
                $episode->setSeason($this->getReference('season_23'));
            }
            elseif ($i > 69 && $i <= 72) {
                $episode->setSeason($this->getReference('season_24'));
            }
            elseif ($i > 72 && $i <= 75) {
                $episode->setSeason($this->getReference('season_25'));
            }
            elseif ($i > 75 && $i <= 78) {
                $episode->setSeason($this->getReference('season_26'));
            }
            elseif ($i > 78 && $i <= 81) {
                $episode->setSeason($this->getReference('season_27'));
            }
            elseif ($i > 81 && $i <= 84) {
                $episode->setSeason($this->getReference('season_28'));
            }
            elseif ($i > 84 && $i <= 87) {
                $episode->setSeason($this->getReference('season_29'));
            }
            else {
                $episode->setSeason($this->getReference('season_30'));
            }

            $slugify = new Slugify();
            $slug = $slugify->generate('episode-' . $episode->getNumber());
            $episode->setSlug($slug);

            $manager->persist($episode);

            $this->addReference('episode_' . $i, $episode);
        }
        $manager->flush();
    }
}
