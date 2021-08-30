<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Products;

class ProductsController extends AbstractController
{
    /**
     * @Route("/products", name="products")
     */
    public function afficherPlats(): Response
    {
        $repoProd = $this->getDoctrine()->getRepository(Products::class);
        $lesProds = $repoProd->findAll();
        
        return $this->render('products/products_index.html.twig', [
            'products'=> $lesProds,
        ]);
    }

    /**
     * afficher un produit
     * @Route("/product/{id}", name="affiche_product")
     */
    public function afficher(Products $prod) {
        return $this->render("products/one_product.html.twig",[
            'product' => $prod
        ]);
    }
}
