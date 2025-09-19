<?php

namespace App\Entity\Menu;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

#[ORM\Entity]
#[ORM\Table(name: 'menu_translations')]
class MenuTranslation extends AbstractPersonalTranslation
{
    #[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: "translations")]
    #[ORM\JoinColumn(name: "object_id", referencedColumnName: "id", onDelete: "CASCADE")]
    protected $object;
}
