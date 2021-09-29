<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testRegisterForm(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'SensioTV');

        $client->clickLink('Register');
        $this->assertSelectorTextContains('h1', 'Create your account');

        $registerForm = $client->getCrawler()->selectButton('Create your SensioTV account')->form();

        // When failure
        $client->submit($registerForm, [
            'user[email]' => 'badEmail',
        ]);
        $this->assertCount(6, $client->getCrawler()->filter('.form-error-icon'));
        //print_r($client->getResponse()->getContent());die;

        $client->submit($registerForm, [
            'user[firstName]' => 'joseph',
            'user[lastName]' => 'joseph',
            'user[email]' => 'joseph4@joseph.io',
            'user[phone]' => '0600000000',
            'user[password][first]' => 'test',
            'user[password][second]' => 'test',
            'user[cgu]' => true,
        ]);

        $userRepository = $client->getContainer()->get('doctrine')->getRepository(User::class);
        $user = $userRepository->findOneByEmail('joseph4@joseph.io');
        $this->assertEquals('joseph', $user->getFirstName());
    }
}
