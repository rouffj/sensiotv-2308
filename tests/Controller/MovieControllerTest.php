<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MovieControllerTest extends WebTestCase
{
    public function testRegisterForm(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'SensioTV+');

        $crawler = $client->clickLink('Register');
        $this->assertEquals('/register', $client->getRequest()->getPathInfo());
        $this->assertEquals('Create your account.', $crawler->filter('h1')->text());

        $form = $crawler->selectButton('Create your SensioTV account')->form();
        $crawler = $client->submit($form, [
            'user[firstName]' => 'Joseph'
        ]);

        $this->assertEquals(5, $crawler->filter('.form-error-message')->count(), '5 errors are expected.');
    }
}
