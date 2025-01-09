<?php

namespace App\Entity\Page;

use App\Entity\ContentType;
use App\Entity\File\File;
use App\Repository\Page\PageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Slug;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Image;

#[ORM\Entity(repositoryClass: PageRepository::class)]
#[Broadcast(
    topics: ['pages'],
    private: true
)]
class Page
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 500)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $body = null;

    #[ORM\Column(length: 255, unique: true, nullable: true)]
    #[Slug(fields:['title'])]
    private ?string $slug = null;

    #[ORM\ManyToMany(targetEntity: File::class)]
    private Collection $attachments;

    #[ORM\Column]
    private ?bool $published = false;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ContentType $contentType = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $shortDescription = null;

    /**
     * @var Collection<int, UrlRedirection>
     */
    #[ORM\OneToMany(mappedBy: 'page', targetEntity: UrlRedirection::class, orphanRemoval: true, cascade: ['persist'])]
    #[Assert\Valid()]
    private Collection $urlRedirections;

    #[ORM\ManyToOne]
    private ?File $coverImage = null;

    /**
     * @var Collection<int, File>
     */
    #[ORM\ManyToMany(targetEntity: File::class)]
    #[ORM\JoinTable('page_gallery_items')]
    #[All(
        new Image(
            mimeTypes: ['image/png', 'image/jpg', 'image/jpeg', 'image/webp']
        )
    )]
    private Collection $galleryItems;

    /**
     * @var Collection<int, AccordionItem>
     */
    #[ORM\OneToMany(mappedBy: 'page', targetEntity: AccordionItem::class, orphanRemoval: true, cascade: ['persist'])]
    #[Assert\Valid()]
    private Collection $accordionItems;

    /**
     * @var Collection<int, SliderItem>
     */
    #[ORM\OneToMany(mappedBy: 'page', targetEntity: SliderItem::class, orphanRemoval: true, cascade: ['persist'])]
    #[Assert\Valid()]
    private Collection $sliderItems;

    /**
     * @var Collection<int, BoxItem>
     */
    #[ORM\OneToMany(
        mappedBy: 'page',
        targetEntity: BoxItem::class,
        orphanRemoval: true,
        cascade: ['persist']
    )]
    private Collection $boxItems;

    public function __construct()
    {
        $this->attachments = new ArrayCollection();
        $this->urlRedirections = new ArrayCollection();
        $this->galleryItems = new ArrayCollection();
        $this->accordionItems = new ArrayCollection();
        $this->sliderItems = new ArrayCollection();
        $this->boxItems = new ArrayCollection();
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

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): static
    {
        $this->body = $body;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, File>
     */
    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    public function addAttachment(File $attachment): static
    {
        if (!$this->attachments->contains($attachment)) {
            $this->attachments->add($attachment);
        }

        return $this;
    }

    public function removeAttachment(File $attachment): static
    {
        $this->attachments->removeElement($attachment);

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(?bool $published): static
    {
        $this->published = $published;

        return $this;
    }

    public function getContentType(): ?ContentType
    {
        return $this->contentType;
    }

    public function setContentType(?ContentType $contentType): static
    {
        $this->contentType = $contentType;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): static
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * @return Collection<int, UrlRedirection>
     */
    public function getUrlRedirections(): Collection
    {
        return $this->urlRedirections;
    }

    public function addUrlRedirection(UrlRedirection $urlRedirection): static
    {
        if (!$this->urlRedirections->contains($urlRedirection)) {
            $this->urlRedirections->add($urlRedirection);
            $urlRedirection->setPage($this);
        }

        return $this;
    }

    public function removeUrlRedirection(UrlRedirection $urlRedirection): static
    {
        if ($this->urlRedirections->removeElement($urlRedirection)) {
            // set the owning side to null (unless already changed)
            if ($urlRedirection->getPage() === $this) {
                $urlRedirection->setPage(null);
            }
        }

        return $this;
    }

    public function getCoverImage(): ?File
    {
        return $this->coverImage;
    }

    public function setCoverImage(?File $coverImage): static
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    /**
     * @return Collection<int, File>
     */
    public function getGalleryItems(): Collection
    {
        return $this->galleryItems;
    }

    public function addGalleryItem(File $galleryItem): static
    {
        if (!$this->galleryItems->contains($galleryItem)) {
            $this->galleryItems->add($galleryItem);
        }

        return $this;
    }

    public function removeGalleryItem(File $galleryItem): static
    {
        $this->galleryItems->removeElement($galleryItem);

        return $this;
    }

    /**
     * @return Collection<int, AccordionItem>
     */
    public function getAccordionItems(): Collection
    {
        return $this->accordionItems;
    }

    public function addAccordionItem(AccordionItem $accordionItem): static
    {
        if (!$this->accordionItems->contains($accordionItem)) {
            $this->accordionItems->add($accordionItem);
            $accordionItem->setPage($this);
        }

        return $this;
    }

    public function removeAccordionItem(AccordionItem $accordionItem): static
    {
        if ($this->accordionItems->removeElement($accordionItem)) {
            // set the owning side to null (unless already changed)
            if ($accordionItem->getPage() === $this) {
                $accordionItem->setPage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SliderItem>
     */
    public function getSliderItems(): Collection
    {
        return $this->sliderItems;
    }

    public function addSliderItem(SliderItem $sliderItem): static
    {
        if (!$this->sliderItems->contains($sliderItem)) {
            $this->sliderItems->add($sliderItem);
            $sliderItem->setPage($this);
        }

        return $this;
    }

    public function removeSliderItem(SliderItem $sliderItem): static
    {
        if ($this->sliderItems->removeElement($sliderItem)) {
            // set the owning side to null (unless already changed)
            if ($sliderItem->getPage() === $this) {
                $sliderItem->setPage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BoxItem>
     */
    public function getBoxItems(): Collection
    {
        return $this->boxItems;
    }

    public function addBoxItem(BoxItem $boxItem): static
    {
        if (!$this->boxItems->contains($boxItem)) {
            $this->boxItems->add($boxItem);
            $boxItem->setPage($this);
        }

        return $this;
    }

    public function removeBoxItem(BoxItem $boxItem): static
    {
        if ($this->boxItems->removeElement($boxItem)) {
            // set the owning side to null (unless already changed)
            if ($boxItem->getPage() === $this) {
                $boxItem->setPage(null);
            }
        }

        return $this;
    }
}
