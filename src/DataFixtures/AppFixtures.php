<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Comments;
use App\Entity\Products;

use App\Entity\Customers;
use App\Entity\Categories;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordHasherInterface $encoder) {
        $this->encoder = $encoder;
    }
        
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Faker\Factory::create('fr-FR');
        $customers = array();
        $items = array(
            1=>array('Menu Classic', 'Sandwich: Burger, Salade, Tomate, Cornichon + Frites + Boisson', 8, 'm1.png', 1),
            2=>array('Menu Bacon', 'Sandwich: Burger, Fromage, Bacon, Salade, Tomate + Frites + Boisson', 9, 'm2.png', 1),
            3=>array('Menu Big', 'Sandwich: Double Burger, Fromage, Cornichon, Salade + Frites + Boisson', 10, 'm3.png', 1),
            4=>array('Menu Chicken', 'Sandwich: Poulet Frit, Tomate, Salade, Mayonnaise + Frites + Boisson', 9, 'm4.png', 1),
            5=>array('Classic', 'Sandwich: Burger, Salade, Tomate, Cornichon', 5, 'b1.png', 2),
            6=>array('Big', 'Sandwich: Double Burger, Fromage, Cornichon, Salade', 6, 'b3.png', 2),
            7=>array('Bacon', 'Sandwich: Burger, Fromage, Bacon, Salade, Tomate', 6, 'b2.png', 2),
            8=>array('Coca-Cola', 'Au choix: Petit, Moyen ou Grand', 1, 'bo1.png', 5),
            9=>array('Coca-Cola Light', 'Au choix: Petit, Moyen ou Grand', 1, 'bo2.png', 5),
            10=>array('Sprite', 'Au choix: Petit, Moyen ou Grand', 1, 'bo5.png', 5),
            11=>array('Nestea', 'Au choix: Petit, Moyen ou Grand', 1, 'bo6.png', 5),
            12=>array('Fondant au chocolat', 'Au choix: Chocolat Blanc ou au lait', 4, 'd1.png', 6),
            13=>array('Muffin', 'Au choix: Au fruits ou au chocolat', 2, 'd2.png', 6),
            14=>array('Sundae', 'Au choix: Fraise, Caramel ou Chocolat', 4, 'd5.png', 6),
            15=>array('Menu Fish', 'Sandwich: Poisson, Salade, Mayonnaise, Cornichon + Frites + Boisson', 10, 'm5.png', 1)
        );
        // gestion des produits
        $prod_array =array();
        for($i=1; $i <= count($items); $i++) {
            $product = new Products();
            $product->setName($items[$i][0]);
            $product->setDescription($items[$i][1]);
            $product->setPrice($items[$i][2]);
            $product->setCategorie($items[$i][4]);
            $product->setImage($items[$i][3]);
            $prod_array[] = $product;
            $manager->persist($product);
            
        }
        $manager->flush();

       
        for($i = 0; $i < 20; $i++) {
            $customer = new Customers();
            $customer->setName($faker->name());
            $customer->setLastname($faker->lastName());
            $customer->setEmail($faker->email());
            $customer->setPassword($this->encoder->hashPassword($customer,'password'));
            $customer->setAvatar($faker->imageUrl($width=100, $height= 100, 'cats'));
            $customer->setJoinDate($faker->dateTime());
            $customers[] = $customer;
            // les commentaires
            
            $manager->persist($customer);
            
        }
        for ($i=0; $i < 10; $i++) {
            $comment = new Comments();
            $comment->setCreatedAt($faker->dateTime());
            $comment->setAuthor($customers[mt_rand(0,count($customers)-1)]);
            
            $comment->setRating(mt_rand(1,5));
            $prod = mt_rand(0,count($prod_array)-1);
            $comment->setContent($faker->paragraph());
            $comment->setProduct($prod_array[ $prod]);
            $manager->persist($comment);

        }
        
        $categoriesArr = array('Menus','Burgers','Snacks','Salades','Boissons','Desserts');
        for($i=0; $i < count($categoriesArr); $i++) {
            $categorie = new Categories();
            $categorie->setName($categoriesArr[$i]);
            $manager->persist($categorie);
        }
        $manager->flush();
    }
}
