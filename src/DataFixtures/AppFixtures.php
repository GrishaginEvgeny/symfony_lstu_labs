<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use App\Entity\Post;
use App\Entity\Comment;


class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $hasher;
    private CategoryRepository $categoryRepository;

    public function __construct(UserPasswordHasherInterface $hasher,  CategoryRepository $categoryRepository)
    {
        $this->hasher = $hasher;
        $this->categoryRepository = $categoryRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $categories = [
            ['name' =>'В мире', 'link' => '/world'],
            ['name' =>'Россия', 'link' => '/russia'],
            ['name' =>'Технологии', 'link' => '/tech'],
            ['name' =>'Дизайн', 'link' => '/design'],
            ['name' =>'Культура', 'link' => '/culture'],
            ['name' =>'Бизнес', 'link' => '/business'],
            ['name' =>'Политика', 'link' => '/politics'],
            ['name' =>'IT', 'link' => '/it'],
            ['name' =>'Наука', 'link' => '/science'],
            ['name' =>'Здоровье', 'link' => '/health'],
            ['name' =>'Спорт', 'link' => '/sport'],
            ['name' =>'Путешествия', 'link' => '/travel'],
        ];
        foreach ($categories as $category){
            $category_obj = new Category();
            $category_obj->setName($category['name']);
            $category_obj->setUrl($category['link']);
            $manager->persist($category_obj);
        }
        $manager->flush();
        $categories_list = $this->categoryRepository->findAll();
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setName('BobForTest'.$i);
            $user->setEmail('test@email'.$i.'.for');
            $password = $this->hasher->hashPassword($user, 'test');
            $user->setPassword($password);
            $user->setBlogName('test_blog_name'.$i);
            $user->setBlogCaption('test_blog_name'.$i.'-caption');
            $user->setBlogPicture('b-'.(($i+1)%2).'.png');
            $user->setUserAvatar('u-'.(($i+1)%2).'.jpg');
            $user->setRoles(['ROLE_USER']);
            $rand_category = $categories_list[rand(0,(count($categories_list)-1))];
            $user->setCategory($rand_category);
            $rand_category->addBlog($user);
            $manager->persist($user);

            $date = new DateTime('@'.strtotime('now'));
            $post = new Post();
            $post->setTitle('TestPost'.$i);
            $post->setAddDate($date);
            $post->setText('TestsPost'.$i.'text');
            $post->setViewCount(1);
            $post->setUser($user);
            $pic_path = 'p-'.(($i+1)%2).'.png';
            $post->setPictures([$pic_path,$pic_path,$pic_path]);
            $post->setAvatar($pic_path);
            $manager->persist($post);

            $comment = new Comment();
            $comment->setAddDate($date);
            $comment->setText('Test Text'.$i);    
            $comment->setIsModerated(true);
            $comment->setPicture('c-'.(($i+1)%2).'.jpg');
            $comment->setUser($user);
            $comment->setPost($post);
            $manager->persist($comment);
        }

        $category = new Category();
        $index = rand(0,11);
        $category->setName($categories[$index]['name']);
        $category->setUrl($categories[$index]['link']);
        $manager->persist($category);
        $user = new User();
        $user->setName('BobAdmin');
        $user->setEmail('test@admin'.'.for');
        $password = $this->hasher->hashPassword($user, 'test');
        $user->setPassword($password);
        $user->setBlogName('test_blog_admin');
        $user->setBlogCaption('test_blog_admin-caption');
        $user->setBlogPicture('b-'.(($i+1)%2).'.png');
        $user->setUserAvatar('u-'.(($i+1)%2).'.jpg');
        $user->setRoles(['ROLE_ADMIN']);
        $rand_category = $categories_list[rand(0,count($categories_list)-1)];
        $user->setCategory($rand_category);
        $rand_category->addBlog($user);
        $manager->persist($user);

        $manager->flush();
    }
}
