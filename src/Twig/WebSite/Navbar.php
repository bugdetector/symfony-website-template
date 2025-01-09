<?php

namespace App\Twig\WebSite;

use App\Repository\Views\NavbarRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'website-navbar', template: "themes/website-theme/components/navbar.twig")]
class Navbar
{
    public function __construct(
        private NavbarRepository $navbarRepository
    ) {
    }

    public function hierarchy()
    {
        return $this->navbarRepository->findBy(['parent' => null]);
    }
}
