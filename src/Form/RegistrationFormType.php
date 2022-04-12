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
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 1, 'minMessage' => 'Имя слишком короткое','max' => 50,
                        'maxMessage' => 'Имя слишком длинное']),
                    new Regex([
                        'pattern' => '/^(?!_)(?!.*_$)(?!.*__)[a-zA-Z0-9_]+$/',
                        'match' => true,
                        'message' => 'Имя может содержать только латиницу цифры и нижнее подчёркивание.
                        Также имя не может начиться и заканчиваться нижним подчёркиванием, и содержать два 
                        нижних подчёркивания подряд.',
                        ])
                ],
                'help' => 'Имя должно быть более 3х симовлов'
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail пользователя',
                'required' => true,
                'help' => "Почта должна быть формата \"имя\"@\"домен\"",
                'constraints' => [
                    new NotBlank(),
                    new Email(['message' => 'Вы ввели некорректный e-mail']),
                ]
            ] )
            ->add('password', PasswordType::class, [
                'label' => 'Пароль',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3, 'max' => 4000]),
                ],])
            ->add('user_avatar', FileType::class, [
                'label' => 'Фото пользователя',
                'constraints' => new Image([
                    'mimeTypes' => ['image/png','image/jpeg','image/jpg','image/bmp'],
                    'mimeTypesMessage' => 'Вы загрузили фотографию в некорректном расширении',
                    ])
                ])
            ->add('blog_name',TextType::class, [
                'label' => 'Название блога',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 5, 'minMessage' => 'Имя слишком короткое', 'max' => 50,
                        'maxMessage' => 'Имя слишком длинное']),
                    new Regex([
                        'pattern' => '/^(?!_)(?!.*_$)(?!.*__)[a-zA-Z0-9_]/',
                        'match' => true,
                        'message' => 'Имя может содержать только латиницу цифры и нижнее подчёркивание.
                        Также имя не может начиться и заканчиваться нижним подчёркиванием, и содержать два 
                        нижних подчёркивания подряд.',
                    ])
                ]])
            ->add('blog_caption',TextareaType::class, [
                'label' => 'Описание блога',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 10, 'minMessage' => 'Описание слишком короткое.']),
                ]])
            ->add('blog_category', ChoiceType::class, [
                'label' => 'Категория блока',
                'choices' => $options['categories']
                 ])
            ->add('blog_picture',FileType::class, [
                'label' => 'Фото блога','constraints' => new Image([
                    'mimeTypes' => ['image/png','image/jpeg','image/jpg','image/bmp'],
                    'mimeTypesMessage' => 'Вы загрузили фотографию в некорректном расширении',
                ])])
            ->add('agree_checkbox', CheckboxType::class, [
                'label' => "<a href=\"#\">Принимаю условия пользовательского соглашения</a>",
                'label_html' => true,
                'mapped' => false,
                'required' => false,
                'constraints' => [new IsTrue(['message'=>'Для регистрации вы должны принять условия пользовательского
                 соглашения'])]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'categories' => '',
        ]);

        $resolver->setAllowedTypes('categories','array');
    }
}
