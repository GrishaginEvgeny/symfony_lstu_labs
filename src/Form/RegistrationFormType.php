<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
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
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\IsFalse;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class ,[
                'label' => 'Имя пользователя',
                'help' => 'Имя должно быть более 3х симовлов'
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail пользователя',
                'help' => "Почта должна быть формата \"имя\"@\"домен\"",
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Пароль',
                'help' => "Пароль должен состоять из 5 символов или более.",
            ])
            ->add('user_avatar', FileType::class, [
                'label' => 'Фото пользователя',
                'help' => "Расширении должно быть jpg, png или bmp.",
            ])
            ->add('blog_name',TextType::class, [
                'label' => 'Название блога',
                'help' => 'Имя должно быть более 3х симовлов'
            ])
            ->add('blog_caption',TextareaType::class, [
                'label' => 'Описание блога',
                'help' => 'Описание должно занимать 10 символов или более.',
            ])
            ->add('blog_category', ChoiceType::class, [
                'label' => 'Категория блока',
                'choices' => $options['categories']
            ])
            ->add('blog_picture', FileType::class, [
                'label' => 'Фото блога',
                'help' => "Расширении должно быть jpg, png или bmp.",
            ])
            ->add('agree_checkbox', CheckboxType::class, [
                'label' => "<a href=\"#\">Принимаю условия пользовательского соглашения</a>",
                'label_html' => true,
                'mapped' => false,
                'constraints' => [new IsTrue(['message'=>'Для регистрации вы должны принять условия пользовательского
                 соглашения'])]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'categories' => [],
        ]);

        $resolver->setAllowedTypes('categories','array');
    }
}
