<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(['min' => 5, 'minMessage' => 'Заголовок слишком короткий', 'max' => 50,
    'maxMessage' => 'Заголовок слишком длинный'])]
    private $title;

    #[ORM\Column(type: 'datetime')]
    private $addDate;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\Length(['min' => 10, 'minMessage' => 'Описание слишком короткое.'])]
    private $text;

    #[ORM\Column(type: 'integer')]
    private $viewCount = 0;

    #[Assert\All([
        new Assert\NotBlank(['message'=>'Вы ничего не загрузили']),
        new Assert\Image([
            'mimeTypes' => ['image/png','image/jpeg','image/jpg','image/bmp'],
            'mimeTypesMessage' => 'Вы загрузили фотографию в некорректном расширении',
        ]),
        new Assert\File([
            'maxSize' => '8Mi',
            'maxSizeMessage' => 'Файл {{ name }} слишком большой. Максимально допустимый размер файла {{ limit }} {{ suffix }}'
        ])
    ])]
    #[ORM\Column(type: 'array', nullable: true)]
    private array $pictures = [];

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Comment::class)]
    private $comments;

    #[ORM\Column(type: 'boolean')]
    private $isModerated = false;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    private $authorName;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Image([
        'mimeTypes' => ['image/png','image/jpeg','image/jpg','image/bmp'],
        'mimeTypesMessage' => 'Вы загрузили фотографию в некорректном расширении',
        ])]
    #[Assert\File([
        'maxSize' => '8Mi',
        'maxSizeMessage' => 'Файл {{ name }} слишком большой. Максимально допустимый размер файла {{ limit }} {{ suffix }}'])]
    private $avatar;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAddDate(): ?\DateTimeInterface
    {
        return $this->addDate;
    }

    public function setAddDate(\DateTimeInterface $addDate): self
    {
        $this->addDate = $addDate;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $description): self
    {
        $this->text = $description;

        return $this;
    }

    public function getIsModerated(): ?bool
    {
        return $this->isModerated;
    }

    public function setIsModerated(bool $isModerated): self
    {
        $this->isModerated = $isModerated;

        return $this;
    }

    public function getViewCount(): ?int
    {
        return $this->viewCount;
    }

    public function setViewCount(int $viewCount): self
    {
        $this->viewCount = $viewCount;

        return $this;
    }

    public function getPictures(): ?array
    {
        return $this->pictures;
    }

    public function setPictures(?array $pictures): self
    {
        $this->pictures = $pictures;

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
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }
}
