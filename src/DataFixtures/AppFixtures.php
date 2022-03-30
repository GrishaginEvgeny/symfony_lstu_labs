<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use App\Entity\Post;
use App\Entity\Comment;


class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $categories = ['Sport', 'Politic', 'IT'];
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setName('BobForTest'.$i);
            $user->setEmail('test@email.for');
            $password = $this->hasher->hashPassword($user, 'test');
            $user->setPassword($password);
            $user->setToken('3421$!$!@$!dsadas'.$i);
            $user->setIsAdmin(false);
            $user->setBlogName('test-blog-name'.$i);
            $user->setBlogCaption('test-blog-name'.$i.'-caption');
            $user->setBlogCategory($categories[rand(0,2)]);
            $manager->persist($user);

            $date = new \DateTime('@'.strtotime('now'));
            $post = new Post();
            $post->setTitle('TestPost'.$i);
            $post->setAddDate($date);
            $post->setDescription('Test Description'.$i);
            $post->setViewCount(1);
            $post->setUser($user);
            $manager->persist($post);

            $comment = new Comment();
            $comment->setAddDate($date);
            $comment->setText('Test Text'.$i);    
            $comment->setIsModerated(true);
            $comment->setPicture('daasdassd'.$i);
            $comment->setUser($user);
            $comment->setPost($post);
            $manager->persist($comment);
        }

        $manager->flush();
    }
}
