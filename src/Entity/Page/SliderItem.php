<?php

namespace App\Entity\Page;

use App\Entity\File\File;
use App\Repository\Page\SliderItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: SliderItemRepository::class)]
#[Gedmo\TranslationEntity(class: SliderItemTranslation::class)]
class SliderItem
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 500)]
    #[Gedmo\Translatable(fallback: true)]
    private ?string $title = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?File $sliderImage = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $url = null;

    #[ORM\ManyToOne(inversedBy: 'sliderItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Page $page = null;

    #[ORM\OneToMany(mappedBy: 'object', targetEntity: SliderItemTranslation::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Collection $translations = null;

    #[Gedmo\Locale]
    private ?string $locale = null;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSliderImage(): ?File
    {
        return $this->sliderImage;
    }

    public function setSliderImage(?File $sliderImage): static
    {
        $this->sliderImage = $sliderImage;

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

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function setPage(?Page $page): static
    {
        $this->page = $page;

        return $this;
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }
}
