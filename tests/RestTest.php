<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
class RestTest extends ApiTestCase
{
    public function getUserRepositoryForTest() : ? UserRepository{
        return static::getContainer()->get(UserRepository::class);
    }

    public function getPostRepositoryForTest() :? PostRepository
    {
        return static::getContainer()->get(PostRepository::class);
    }

    public function getCommentRepositoryForTest() :? CommentRepository
    {
        return static::getContainer()->get(CommentRepository::class);
    }
    /**
     * @throws TransportExceptionInterface
     */
    public function testToGetAllUsers(): void
    {
        static::createClient()->request('GET', '/api/users');
        $this->assertResponseStatusCodeSame(401);
        $user = $this->getUserRepositoryForTest()->findOneBy(['name' => 'BobForTest0']);
        $responseOfRequestWithToken = static::createClient()->withOptions([
            'headers' =>
                [
                    'x-auth-token' => $user->getApiToken(),
                ]
            ])->request('GET', '/api/users');
        $this->assertResponseStatusCodeSame(200);
        $responseArray = $responseOfRequestWithToken->toArray();
        $this->assertIsArray($responseArray);
        foreach ($responseArray['hydra:member'] as $arr){
            $this->assertIsArray($arr);
            $this->assertArrayHasKey('name', $arr);
            $this->assertNotNull($this->getUserRepositoryForTest()->findBy(['name'=>$arr['name']]));
        }
    }
    /**
     * @throws TransportExceptionInterface
     */
    public function testToGetOneUser(): void
    {
        static::createClient()->request('GET', '/api/users/1');
        $this->assertResponseStatusCodeSame(401);
        $user = $this->getUserRepositoryForTest()->findOneBy(['name' => 'BobForTest0']);
        $responseOfRequestWithToken = static::createClient()->withOptions([
            'headers' =>
                [
                    'x-auth-token' => $user->getApiToken(),
                ]
        ])->request('GET', '/api/users/1');
        $responseArray = $responseOfRequestWithToken->toArray();
        //$this->assertIsString($responseArray);
        $this->assertIsArray($responseArray);
        $this->assertArrayHasKey('name', $responseArray);
        $this->assertNotNull($this->getUserRepositoryForTest()->findBy(['name'=>$responseArray['name']]));
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testToGetAllComment(): void
    {
        static::createClient()->request('GET', '/api/comments');
        $this->assertResponseStatusCodeSame(401);
        $user = $this->getUserRepositoryForTest()->findOneBy(['name' => 'BobForTest0']);
        $responseOfRequestWithToken = static::createClient()->withOptions([
            'headers' =>
                [
                    'x-auth-token' => $user->getApiToken(),
                ]
        ])->request('GET', '/api/comments');
        $this->assertResponseStatusCodeSame(200);
        $responseArray = $responseOfRequestWithToken->toArray();
        $this->assertIsArray($responseArray);
        foreach ($responseArray['hydra:member'] as $arr){
            $this->assertIsArray($arr);
            $this->assertArrayHasKey('@id', $arr);
            $this->assertArrayHasKey('user',$arr);
            $this->assertArrayHasKey('post', $arr);
            $postId = explode("/",$arr['post'])[3];
            $userId = explode("/",$arr['user'])[3];
            $post = $this->getPostRepositoryForTest()->findOneBy(['id'=>$postId]);
            $user = $this->getUserRepositoryForTest()->findOneBy(['id'=>$userId]);
            $this->assertNotNull($this->getCommentRepositoryForTest()->findOneBy([
                'id'=>explode("/",$arr['@id'])[3],
                'user'=>$user,
                'post'=>$post
            ]));
        }
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testToGetOneComment(): void
    {
        static::createClient()->request('GET', '/api/comments/1');
        $this->assertResponseStatusCodeSame(401);
        $user = $this->getUserRepositoryForTest()->findOneBy(['name' => 'BobForTest0']);
        $responseOfRequestWithToken = static::createClient()->withOptions([
            'headers' =>
                [
                    'x-auth-token' => $user->getApiToken(),
                ]
        ])->request('GET', '/api/comments/1');
        $this->assertResponseStatusCodeSame(200);
        $responseArray = $responseOfRequestWithToken->toArray();
        $this->assertIsArray($responseArray);
        $this->assertArrayHasKey('@id', $responseArray);
        $this->assertArrayHasKey('user',$responseArray);
        $this->assertArrayHasKey('post', $responseArray);
        $postId = explode("/",$responseArray['post'])[3];
        $userId = explode("/",$responseArray['user'])[3];
        $post = $this->getPostRepositoryForTest()->findOneBy(['id'=>$postId]);
        $user = $this->getUserRepositoryForTest()->findOneBy(['id'=>$userId]);
        $this->assertNotNull($this->getCommentRepositoryForTest()->findOneBy([
            'id'=>explode("/",$responseArray['@id'])[3],
            'user'=>$user,
            'post'=>$post
        ]));
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testToGetAllPosts(): void
    {
        static::createClient()->request('GET', '/api/posts');
        $this->assertResponseStatusCodeSame(401);
        $user = $this->getUserRepositoryForTest()->findOneBy(['name' => 'BobForTest0']);
        $responseOfRequestWithToken = static::createClient()->withOptions([
            'headers' =>
                [
                    'x-auth-token' => $user->getApiToken(),
                ]
        ])->request('GET', '/api/posts');
        $this->assertResponseStatusCodeSame(200);
        $responseArray = $responseOfRequestWithToken->toArray();
        $this->assertIsArray($responseArray);
        foreach ($responseArray['hydra:member'] as $arr){
            $this->assertIsArray($arr);
            $this->assertArrayHasKey('@id', $arr);
            $this->assertArrayHasKey('user', $arr);
            $userId = explode("/",$arr['user'])[3];
            $user = $this->getUserRepositoryForTest()->findOneBy(['id'=>$userId]);
            $this->assertNotNull($this->getPostRepositoryForTest()->findOneBy([
                'id' => explode("/",$arr['@id'])[3],
                'user' => $user,
            ]));
        }
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testToGetOnePost(): void
    {
        static::createClient()->request('GET', '/api/posts/1');
        $this->assertResponseStatusCodeSame(401);
        $user = $this->getUserRepositoryForTest()->findOneBy(['name' => 'BobForTest0']);
        $responseOfRequestWithToken = static::createClient()->withOptions([
            'headers' =>
                [
                    'x-auth-token' => $user->getApiToken(),
                ]
        ])->request('GET', '/api/posts/1');
        $this->assertResponseStatusCodeSame(200);
        $responseArray = $responseOfRequestWithToken->toArray();
        $this->assertIsArray($responseArray);
        $this->assertArrayHasKey('@id', $responseArray);
        $this->assertArrayHasKey('user', $responseArray);
        $userId = explode("/",$responseArray['user'])[3];
        $user = $this->getUserRepositoryForTest()->findOneBy(['id'=>$userId]);
        $this->assertNotNull($this->getPostRepositoryForTest()->findOneBy([
            'id' => explode("/",$responseArray['@id'])[3],
            'user' => $user,
        ]));
    }
}
