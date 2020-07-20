<?php


namespace App\DataFixtures;


use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CategoryFixtures extends Fixture
{
    const CATEGORIES = [
        'Action',
        'Adventure',
        'Animation',
        'Fantasy',
        'Horror',
        'Science-fiction',
        'Romance',
        'Comedy',
    ];

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        /* Just one fixture
        $category = new Category();
        $category->setName('Science-fiction');
        $manager->persist($category);
        $manager->flush();
        */

        /* Multiple fixtures
        for ($i = 1; $i <= 50; $i++) {
            $category = new Category();
            $category->setName('Category name ' . $i);
            $manager->persist($category);
        }
        $manager->flush();
        */

        // Fixtures with constant
        foreach (self::CATEGORIES as $key => $categoryName) {
            $category = new Category();
            $category->setName($categoryName);

            $manager->persist($category);
            $this->addReference('category_' . $key, $category);
        }
        $manager->flush();
    }
}
