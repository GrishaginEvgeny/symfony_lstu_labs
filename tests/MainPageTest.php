<?php

namespace App\Tests;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainPageTest extends WebTestCase
{
    public function getPostRepositoryForTest() :? PostRepository
    {
        return static::getContainer()->get(PostRepository::class);
    }

    public function testMainPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleContains('Главная страница');
        $link = $crawler->selectLink('Авторизация')->link();
        $client->click($link);
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleContains('Auth');
        $link = $crawler->selectLink('Перейти к посту')->link();
        $client->click($link);
        $this->assertResponseIsSuccessful();
        $postTitle = $this->getPostRepositoryForTest()->findOneBy(['id'=>explode('/',$link->getUri())[4]])->getTitle();
        $this->assertPageTitleContains($postTitle);
    }
}
