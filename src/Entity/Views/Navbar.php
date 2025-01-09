<?php

namespace App\Entity\Views;

use App\Repository\Views\NavbarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Tree;
use Gedmo\Mapping\Annotation\TreeLeft;
use Gedmo\Mapping\Annotation\TreeLevel;
use Gedmo\Mapping\Annotation\TreeParent;
use Gedmo\Mapping\Annotation\TreeRight;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: NavbarRepository::class)]
#[Tree(type: "nested")]
class Navbar
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url = null;

    #[TreeParent]
    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    private ?self $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class, cascade: ["persist"], orphanRemoval: true)]
    private Collection $children;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $expand_title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $expand_description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $icon_class = null;

    #[TreeLevel()]
    #[ORM\Column(nullable: true)]
    private ?int $depth = null;

    #[TreeLeft]
    #[ORM\Column(nullable: true)]
    private ?int $lft = null;

    #[TreeRight]
    #[ORM\Column(nullable: true)]
    private ?int $rgt = null;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): static
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): static
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    public function getExpandTitle(): ?string
    {
        return $this->expand_title;
    }

    public function setExpandTitle(?string $expand_title): static
    {
        $this->expand_title = $expand_title;

        return $this;
    }

    public function getExpandDescription(): ?string
    {
        return $this->expand_description;
    }

    public function setExpandDescription(?string $expand_description): static
    {
        $this->expand_description = $expand_description;

        return $this;
    }

    public function getIconClass(): ?string
    {
        return $this->icon_class;
    }

    public function setIconClass(?string $icon_class): static
    {
        $this->icon_class = $icon_class;

        return $this;
    }

    public function getDepth(): ?int
    {
        return $this->depth;
    }

    public function setDepth(int $depth): static
    {
        $this->depth = $depth;

        return $this;
    }

    public function getLft(): ?int
    {
        return $this->lft;
    }

    public function setLft(int $lft): static
    {
        $this->lft = $lft;

        return $this;
    }

    public function getRgt(): ?int
    {
        return $this->rgt;
    }

    public function setRgt(int $rgt): static
    {
        $this->rgt = $rgt;

        return $this;
    }
}
