<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class ,[
                'label' => 'Имя пользователя',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3]),
                ],
                'help' => 'Имя должно быть более 3х симовлов'
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail пользователя',
                'required' => true,
                'help' => "Почта должна быть формата \"имя\"@\"домен\"",
            ] )
            ->add('password', PasswordType::class, [
                'label' => 'Пароль',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3, 'max' => 4000]),
                ],])
            ->add('user_avatar', FileType::class, [
                'label' => 'Фото пользователя',])
            ->add('blog_name',TextType::class, [
                'label' => 'Название блока',])
            ->add('blog_caption',TextareaType::class, [
                'label' => 'Описание блога',])
            ->add('blog_category', ChoiceType::class, [
                'label' => 'Категория блока',])
            ->add('blog_token',TextType::class, [
                'label' => 'Идентификатор блога',])
            ->add('blog_picture',FileType::class, [
                'label' => 'Фото блога',])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
