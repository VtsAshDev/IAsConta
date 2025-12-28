<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $categories = [
            'Alimentação' => '🍔',
            'Transporte'  => '🚗',
            'Lazer'       => '🎉',
            'Saúde'       => '💊',
            'Moradia'     => '🏠',
            'Educação'    => '📚',
            'Trabalho'    => '💼',
            'Outros'      => '📦'
        ];

        foreach ($categories as $name => $icon) {
            $category = new Category();
            $category->setName($name);
            $category->setIcon($icon);

            $manager->persist($category);
        }

        $manager->flush();
    }
}
