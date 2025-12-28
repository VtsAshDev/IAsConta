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
            'Alimentação'   => '🍔',
            'Supermercado'  => '🛒',
            'Transporte'    => '🚗',
            'Moradia'       => '🏠',
            'Contas Fixas'  => '🔌',
            'Lazer'         => '🎉',
            'Saúde'         => '💊',
            'Educação'      => '📚',
            'Assinaturas'   => '📺',
            'Cuidados'      => '✂️',
            'Compras'       => '🛍️',
            'Pets'          => '🐾',
            'Trabalho'      => '💼',
            'Investimentos' => '📈',
            'Presentes'     => '🎁',
            'Viagens'       => '✈️',
            'Dívidas'       => '💳',
            'Outros'        => '📦'
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
