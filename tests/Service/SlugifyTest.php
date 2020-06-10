<?php


namespace App\Tests\Service;

use App\Service\Slugify;
use PHPUnit\Framework\TestCase;

class SlugifyTest extends TestCase
{
    public function testGenerate()
    {
        $slugify = new Slugify();

        $this->assertEquals('my-awsome-tv-show', $slugify->generate('My Awsome Tv Show'));
        $this->assertEquals('ma-super-serie', $slugify->generate('Ma Super Série'));
        $this->assertEquals('my-awsome-tv-show', $slugify->generate('My ---Awsome Tv-- Show'));
        $this->assertEquals('my-awsome-tv-show', $slugify->generate('My/ --Àwsome) Tv-- Shôw?'));
        $this->assertEquals('my-awsome-tv-show-01', $slugify->generate('My/ --Àwsome) Tv-- Shôw?01'));
    }
}
