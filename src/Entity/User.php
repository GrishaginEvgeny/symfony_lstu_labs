<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Comment;
use App\Entity\Post;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Пользователь с там e-mail уже существует', errorPath: 'email')]
#[UniqueEntity(fields: ['name'], message: 'Пользователь с там именем уже существует', errorPath: 'name')]
#[UniqueEntity(fields: ['blog_name'], message: 'Пользователь с таким именем блога уже сущестувет', errorPath: 'blog_name')]
#[ApiResource(
    collectionOperations: ["get"],
    normalizationContext: ['groups' => ['user']],
    itemOperations: ['get'],
)]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(['message'=>'Вы не ввели имя'])]
    #[Assert\Length(['min' => 2, 'minMessage' => 'Имя слишком короткое','max' => 50,
    'maxMessage' => 'Имя слишком длинное'])]
    #[Assert\Regex([
        'pattern' => '/^(?!_)(?!.*_$)(?!.*__)[a-zA-Z0-9_]+$/',
        'match' => true,
        'message' => 'Имя может содержать только латиницу цифры и нижнее подчёркивание.
        Также имя не может начиться и заканчиваться нижним подчёркиванием, и содержать два 
        нижних подчёркивания подряд.',
        ])]
    #[Groups("user")]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Email(['message' => 'Вы ввели некорректный e-mail'])]
    #[Assert\NotBlank(['message'=>'Вы не ввели почту'])]
    #[Groups("user")]
    private string $email;


    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(['message'=>'Вы не ввели пароль'])]
    #[Assert\Length(['min' => 5, 'minMessage' => 'Пароль слишком короткий','max' => 4000])]
    private string $password;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(['min' => 5, 'minMessage' => 'Имя слишком короткое', 'max' => 50,
    'maxMessage' => 'Имя слишком длинное'])]
    #[Assert\NotBlank(['message'=>'Вы не ввели имя'])]
    #[Assert\Regex([
        'pattern' => '/^(?!_)(?!.*_$)(?!.*__)[a-zA-Z0-9_]/',
        'match' => true,
        'message' => 'Имя может содержать только латиницу цифры и нижнее подчёркивание.
        Также имя не может начиться и заканчиваться нижним подчёркиванием, и содержать два 
        нижних подчёркивания подряд.',
    ])]
    #[Groups("user")]
    private string $blog_name;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Comment::class)]
    #[Groups("user")]
    private $comments;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Post::class, orphanRemoval: true)]
    #[Groups("user")]
    private $posts;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\Length(['min' => 10, 'minMessage' => 'Описание слишком короткое.'])]
    #[Assert\NotBlank(['message'=>'Вы не ввели имя'])]
    #[Groups("user")]
    private string $blog_caption;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Image([
        'mimeTypes' => ['image/png','image/jpeg','image/jpg','image/bmp'],
        'mimeTypesMessage' => 'Вы загрузили фотографию в некорректном расширении',
        ])]
    private ?string $blog_picture;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Image([
        'mimeTypes' => ['image/png','image/jpeg','image/jpg','image/bmp'],
        'mimeTypesMessage' => 'Вы загрузили фотографию в некорректном расширении',
        ])]
    private ?string $user_avatar;

    #[ORM\Column(type:"string", unique:true, nullable:true)]
    private $apiToken;


    #[ORM\Column(type: "json")]
    private array $roles = [];

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'blog')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups("user")]
    private $category;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->getName();
    }

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(string $apiToken): self
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function eraseCredentials()
    {

    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getBlogName(): ?string
    {
        return $this->blog_name;
    }

    public function setBlogName(string $blog_name): self
    {
        $this->blog_name = $blog_name;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }

    public function getBlogCaption(): ?string
    {
        return $this->blog_caption;
    }

    public function setBlogCaption(?string $blog_caption): self
    {
        $this->blog_caption = $blog_caption;

        return $this;
    }

    public function getBlogPicture(): ?string
    {
        return $this->blog_picture;
    }

    public function setBlogPicture(?string $blog_picture): self
    {
        $this->blog_picture = $blog_picture;

        return $this;
    }

    public function getUserAvatar(): ?string
    {
        return $this->user_avatar;
    }

    public function setUserAvatar(?string $user_avatar): self
    {
        $this->user_avatar = $user_avatar;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

}
