<?php

namespace App\Entity\Page;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

#[ORM\Entity]
#[ORM\Table(name: 'accordion_item_translations')]
class AccordionItemTranslation extends AbstractPersonalTranslation
{
    #[ORM\ManyToOne(targetEntity: AccordionItem::class, inversedBy: "translations")]
    #[ORM\JoinColumn(name: "object_id", referencedColumnName: "id", onDelete: "CASCADE")]
    protected $object;
}
