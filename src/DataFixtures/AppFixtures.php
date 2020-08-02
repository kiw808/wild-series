<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Category;
use App\Entity\Program;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('en_EN');

        for ($i = 1; $i <= 1000; $i++) {
            $category = new Category();
            $category->setName($faker->word);
            $manager->persist($category);
            $this->addReference("category_" . $i, $category);

            $program = new Program();
            $program->setTitle($faker->sentence(4, true));
            $slugify = new Slugify();
            $program->setSlug($slugify->generate($program->getTitle()));
            $program->setSummary($faker->text(100));
            $program->setCategory($this->getReference("category_" . rand(1, $i)));
            $this->addReference("program_" . $i, $program);
            $manager->persist($program);

            for ($j = 1; $j <= 5; $j++) {
                $actor = new Actor();
                $actor->setName($faker->name);
                $slugify = new Slugify();
                $actor->setSlug($slugify->generate($actor->getName()));
                $actor->addProgram($this->getReference("program_" . $i));
                $manager->persist($actor);
            }
        }

        $manager->flush();
    }
}
