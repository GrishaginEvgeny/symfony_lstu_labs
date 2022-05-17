<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ApiResource(
    collectionOperations: ["get"],
    itemOperations: ['get'],
    normalizationContext: ['validation_groups' => ['comment']],
)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    #[Groups("comment")]
    private $addDate;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(['message'=>'Вы не можете оставить пустой комментарий'])]
    #[Groups("comment")]
    private $text;

    #[ORM\Column(type: 'boolean')]
    private $isModerated = false;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'comments')]
    #[Groups("comment")]
    private $user;

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'comments')]
    #[Groups("comment")]
    private $post;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'replies')]
    #[Groups("comment")]
    private $reply;

    #[ORM\OneToMany(mappedBy: 'reply', targetEntity: self::class)]
    #[Groups("comment")]
    private $replies;

    private string $reply_id;

    public function getReplyId(){
        return $this->reply_id;
    }

    public function setReplyId(?string $reply_id){
            $this->reply_id = $reply_id;
    }

    public function __construct()
    {
        $this->replies = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return (string) $this->getId();
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

    public function setText(string $text): self
    {
        $this->text = $text;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getReply(): ?self
    {
        return $this->reply;
    }

    public function setReply(?self $reply): self
    {
        $this->reply = $reply;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getReplies(): Collection
    {
        return $this->replies;
    }

    public function addReply(self $reply): self
    {
        if (!$this->replies->contains($reply)) {
            $this->replies[] = $reply;
            $reply->setReply($this);
        }

        return $this;
    }

    public function removeReply(self $reply): self
    {
        if ($this->replies->removeElement($reply)) {
            // set the owning side to null (unless already changed)
            if ($reply->getReply() === $this) {
                $reply->setReply(null);
            }
        }

        return $this;
    }
}
