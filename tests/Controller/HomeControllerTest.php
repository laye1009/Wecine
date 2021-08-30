<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConferenceControllerTest extends WebTestCase
{
    /**
    * test si la requête est passée et la réponse contient la clé results dans le controller
    * 
    */
    public function testIndex()
    {
        $url = 'https://api.themoviedb.org/3/discover/movie?language=fr&sort_by=popularity.desc&include_adult=false&api_key=ee52528a3d2bfff0312880daeaee21b3';
        $client = static::createClient();
        $response = $client->request('GET', $url);
        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('results', $response);
    }
}