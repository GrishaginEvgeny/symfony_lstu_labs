<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddPostTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $link = $crawler->selectLink('Написать пост')->link();
        $client->click($link);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertPageTitleContains('Register');
        $link = $crawler->selectLink('Авторизация')->link();
        $client->click($link);
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleContains('Auth');
        $client->submitForm('Войти', ['email' => 'test@email0.for', 'password' => 'test']);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertPageTitleContains('Главная страница');
        $link1 = $crawler->selectLink('Написать пост')->link();
        $client->click($link1);
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleContains('Добавить блог');
        $client->submitForm('Добавить', [
            'post[title]' => '123',
            'post[text]' => '1'
        ]);
        $this->assertPageTitleContains('Добавить блог');
        $client->submitForm('Добавить', [
            'post[title]' => 'Тестовый пост',
            'post[text]' => 'Это тест для теста'
        ]);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertPageTitleContains('Главная страница');
    }
}
