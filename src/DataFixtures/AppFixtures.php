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
                'name' => 'Kit d\'hygiène recyclable',
                'shortDescription' => 'Pour une salle de bain éco-friendly',
                'fullDescription' => 'Un ensemble complet d\'accessoires d\'hygiène réutilisables et recyclables pour une salle de bain zéro déchet. Des matériaux naturels et durables pour réduire vos déchets au quotidien.',
                'price' => '24.99',
                'picture' => 'kit-hygiene.webp',
            ],
            [
                'name' => 'Shot Tropical',
                'shortDescription' => 'Fruits frais, pressés à froid',
                'fullDescription' => 'Un shot vitaminé de fruits tropicaux pressés à froid, sans sucre ajouté ni conservateur. Un concentré d\'énergie naturelle pour bien démarrer la journée.',
                'price' => '4.50',
                'picture' => 'shot-tropical.webp',
            ],
            [
                'name' => 'Gourde en bois',
                'shortDescription' => '50cl, bois d\'olivier',
                'fullDescription' => 'Gourde réutilisable de 50 cl habillée de bois d\'olivier. Légère, étanche et durable, elle remplace avantageusement les bouteilles en plastique à usage unique.',
                'price' => '16.90',
                'picture' => 'gourde-bois.webp',
            ],
            [
                'name' => 'Disques Démaquillants x3',
                'shortDescription' => 'Solution efficace pour vous démaquiller en douceur',
                'fullDescription' => 'Lot de 3 disques démaquillants lavables en coton biologique. Doux pour la peau et réutilisables des centaines de fois, ils remplacent les cotons jetables.',
                'price' => '19.90',
                'picture' => '',
            ],
            [
                'name' => 'Bougie Lavande & Patchouli',
                'shortDescription' => 'Cire naturelle',
                'fullDescription' => 'Bougie parfumée à la cire végétale naturelle, aux notes apaisantes de lavande et de patchouli. Mèche en coton, sans paraffine ni additif de synthèse.',
                'price' => '32.00',
                'picture' => '',
            ],
            [
                'name' => 'Brosse à dent',
                'shortDescription' => 'Bois de hêtre rouge issu de forêts gérées durablement',
                'fullDescription' => 'Brosse à dents au manche en bois de hêtre rouge issu de forêts gérées durablement. Poils souples, c\'est une alternative compostable à la brosse en plastique.',
                'price' => '5.40',
                'picture' => '',
            ],
            [
                'name' => 'Kit couvert en bois',
                'shortDescription' => 'Revêtement Bio en olivier & sac de transport',
                'fullDescription' => 'Set de couverts nomades en bois d\'olivier avec son étui en tissu. Idéal pour les repas à emporter sans produire de déchet plastique.',
                'price' => '12.30',
                'picture' => '',
            ],
            [
                'name' => 'Nécessaire, déodorant Bio',
                'shortDescription' => '50ml déodorant à l\'eucalyptus',
                'fullDescription' => 'Déodorant bio de 50 ml à l\'eucalyptus, sans sels d\'aluminium ni alcool. Une formule naturelle et efficace qui respecte votre peau.',
                'price' => '8.50',
                'picture' => 'deodorant-bio.webp',
            ],
            [
                'name' => 'Savon Bio',
                'shortDescription' => 'Thé, Orange & Girofle',
                'fullDescription' => 'Savon biologique saponifié à froid, parfumé au thé, à l\'orange et au girofle. Surgras, il nettoie et nourrit la peau en douceur.',
                'price' => '18.90',
                'picture' => '',
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
