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
                'name' => 'Gourde isotherme en inox',
                'shortDescription' => 'Gourde réutilisable 500 ml, sans BPA.',
                'fullDescription' => 'Cette gourde en acier inoxydable double paroi garde vos boissons fraîches 24 h et chaudes 12 h. Sans BPA, étanche et conçue pour durer, elle remplace des centaines de bouteilles en plastique à usage unique.',
                'price' => '24.90',
                'picture' => 'gourde-inox.webp',
            ],
            [
                'name' => 'Tote bag en coton bio',
                'shortDescription' => 'Sac en coton biologique, solide et lavable.',
                'fullDescription' => 'Fabriqué en coton 100 % biologique certifié, ce tote bag résistant accompagne vos courses du quotidien. Lavable en machine, il se plie facilement et constitue une alternative durable aux sacs plastiques.',
                'price' => '12.50',
                'picture' => 'tote-bag-coton.webp',
            ],
            [
                'name' => 'Brosse à dents en bambou',
                'shortDescription' => 'Manche en bambou compostable, poils souples.',
                'fullDescription' => 'Le manche de cette brosse à dents est taillé dans du bambou Moso à croissance rapide, entièrement compostable. Ses poils souples assurent un brossage doux et efficace, pour une hygiène respectueuse de la planète.',
                'price' => '4.90',
                'picture' => 'brosse-bambou.webp',
            ],
            [
                'name' => 'Savon solide surgras',
                'shortDescription' => 'Savon artisanal saponifié à froid, 100 g.',
                'fullDescription' => 'Élaboré à Lyon selon la méthode de saponification à froid, ce savon surgras nourrit et protège les peaux sensibles. Sans emballage plastique et sans huile de palme, il est composé d\'ingrédients naturels d\'origine végétale.',
                'price' => '7.20',
                'picture' => 'savon-solide.webp',
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
