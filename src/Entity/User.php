<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Entity\Post;
use App\Entity\Comment;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Bundle\MakerBundle\Doctrine\RelationOneToMany;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    private string $email;

    #[ORM\Column(type: 'string', length: 255)]
    private string $password;

    #[ORM\Column(type: 'string', length: 255)]
    private string $token;

    #[ORM\Column(type: 'boolean')]
    private bool $isAdmin = false;

    #[ORM\Column(type: 'string', length: 255)]
    private string $blog_name;

    #[ORM\OneToMany(mappedBy: 'relation', targetEntity: Comment::class)]
    private $comments;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Post::class, orphanRemoval: true)]
    private $posts;

    #[ORM\Column(type: 'text', nullable: true)]
    private string $blog_caption;

    #[ORM\Column(type: 'string', length: 255)]
    private string $blog_category;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $blog_picture;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $user_avatar;

    #[ORM\Column(type: 'string', length: 255)]
    private string $blog_token;

    #[ORM\Column(type: "json")]
    private array $roles = [];

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->posts = new ArrayCollection();
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

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getIsAdmin(): ?bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): self
    {
        $this->isAdmin = $isAdmin;

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
            $comment->setRelation($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getRelation() === $this) {
                $comment->setRelation(null);
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

    public function getBlogCategory(): ?string
    {
        return $this->blog_category;
    }

    public function setBlogCategory(string $blog_category): self
    {
        $this->blog_category = $blog_category;

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

    public function getBlogToken(): ?string
    {
        return $this->blog_token;
    }

    public function setBlogToken(string $blog_token): self
    {
        $this->blog_token = $blog_token;

        return $this;
    }
}
