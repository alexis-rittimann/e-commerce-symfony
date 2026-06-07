<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $products = [
            [
                'name' => 'Huile d\'olive vierge extra',
                'shortDescription' => 'Vierge extra · 50 cl',
                'fullDescription' => 'Première pression à froid, olives du Sud récoltées à maturité. Fruité vert, notes d\'herbe et d\'amande, finale poivrée. Variétés Aglandau & Bouteillan, sans additif. Parfaite à cru : salades, légumes grillés, poisson, burrata.',
                'price' => '14.00',
                'picture' => 'huile-olive.webp',
            ],
            [
                'name' => 'Huile de colza',
                'shortDescription' => 'Pressée à froid · 50 cl',
                'fullDescription' => 'Colza pressé à froid, riche en oméga-3. Goût subtil de noisette, robe dorée. Idéale pour les assaisonnements du quotidien et les cuissons douces. Cultivée et pressée en France, sans solvant ni raffinage.',
                'price' => '11.00',
                'picture' => 'huile-colza.webp',
            ],
            [
                'name' => 'Huile de noix',
                'shortDescription' => 'Grillée à froid · 25 cl',
                'fullDescription' => 'Noix grillées doucement puis pressées à froid pour préserver leurs arômes. Goût intense et boisé, parfait sur les salades d\'endives, les pâtes ou les fromages. Une huile de caractère, à utiliser crue.',
                'price' => '13.00',
                'picture' => 'huile-noix.webp',
            ],
            [
                'name' => 'Le trio découverte',
                'shortDescription' => 'Coffret · 3×25 cl',
                'fullDescription' => 'Le coffret pour tout goûter : olive vierge extra, colza et noix, chacune en format 25 cl. Présenté dans un écrin en carton recyclé. Le cadeau idéal pour les amateurs de bonnes huiles, ou pour découvrir toute la gamme OLEA.',
                'price' => '36.00',
                'picture' => '', // pas encore de photo → placeholder

            ],
        ];

        foreach ($products as $data) {
            $product = new Product();
            $product->setName($data['name']);
            $product->setShortDescription($data['shortDescription']);
            $product->setFullDescription($data['fullDescription']);
            $product->setPrice($data['price']);
            $product->setPicture($data['picture']);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
