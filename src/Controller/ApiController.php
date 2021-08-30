<?php

namespace App\Controller;

use App\Entity\Products;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    private $encoders;
    private $normalizers;
    private $serializer;
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->encoders = [new JsonEncoder()];
        $this->normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($this->normalizers,$this->encoders);
        $this->em = $em;
        
    }
    /**
     * Modifier un produit
     * @Route("/modier/product/{id}", name = "modifier", methods={"PUT"})
     */
    public function modifier(Products $product, Request $request) {
        $datas = json_decode($request->getContent());
        $code = 200;
        if (!$product) {
            $product = new Products();
            $code = 201;
        }
        $product->setName($datas->name);
        $product->setDescription($datas->description);
        $product->setPrice($datas->price);
        $product->setImage($datas->image);
        $manager = $this->getDoctrine->getManager();
        $manager->persist($product);
        $manager->flush();


    }
    /**
     * ajout d'un produit
     * @Route("/ajouter", name= "ajout", methods={"POST"})
     */
    public function addProd(Request $request) {
        $datas = json_decode($request->getContent());
        //if ($request->isXmlHttpRequest)
        $product = new Products();
        $product->setName($datas->name);
        $product->setDescription($datas->description);
        $product->setPrice($datas->price);
        $product->setImage($datas->image);
        $manager = $this->getDoctrine->getManager();

        $manager->persist($product);
        $manager->flush();
        return new Response('OK',201);
    }
    /**
     * @Route("/voir/{id}",name="voir_product")
     */
    public function afficher($id) {
        /* 
        * Avec une injection de dépendance: public function afficher(Products $product) {}
        */
        $product = $this->em->getRepository(Products::class)->findOneBy(array('id' => $id));
        $response = $this->serializer->serialize($product,'json');
        $response = new Response($response);
        $response->headers->set('Content-Type','application/json');
        return $response;
    }
    /**
     * @Route("/a_api", name="api", methods={"GET"})
     */
    public function index(EntityManagerInterface $em)
    {
        $products = $em->getRepository(Products::class)->apiFindAll();
        
        $normalisers = [new ObjectNormalizer()];
        $encoders = [new JsonEncoder()];
        $serializer = new Serializer($normalisers, $encoders);
        $res = $serializer->serialize($products,'json');
        /* Dans le cas d'une erreur du genre: circular references has been located ....
        $res = $serializer->serialize($rpoducts,'json',[
            'circular_reference_handler' => function($object) {
            return $object->getId();
            ]
        })
        */
        $response = new Response($res);
        $response->headers->set('Content-Type','application/json');
        return $res;


    }
}
