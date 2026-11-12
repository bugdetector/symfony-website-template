<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/menu')]
class MenuController extends AbstractController
{
    public function __construct(
        private TranslatorInterface $translator
    ) {
    }

    #[Route('/', name: 'app_admin_menu_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/menu/index.html.twig', [
            'title' => $this->translator->trans('Menus'),
        ]);
    }
}
