<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $link = $crawler->selectLink('Авторизация')->link();
        $client->click($link);
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleContains('Auth');
        $client->submitForm('Войти', ['email' => 'wrong', 'password' => 'wrong']);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
        $client->submitForm('Войти', ['email' => 'test@email0.for', 'password' => 'test']);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertPageTitleContains('Главная страница');
    }
}
