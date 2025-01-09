<?php

namespace App\Entity\Page;

use App\Repository\Page\UrlRedirectionRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: UrlRedirectionRepository::class)]
class UrlRedirection
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'urlRedirections')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Page $page = null;

    #[ORM\Column(length: 500)]
    private ?string $fromUrl = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function setPage(?Page $page): static
    {
        $this->page = $page;

        return $this;
    }

    public function getFromUrl(): ?string
    {
        return $this->fromUrl;
    }

    public function setFromUrl(?string $fromUrl): static
    {
        $this->fromUrl = $fromUrl;

        return $this;
    }
}
