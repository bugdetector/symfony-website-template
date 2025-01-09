<?php

namespace App\Controller\Admin;

use App\Form\TreeFormType;
use App\Form\Views\NavbarType;
use App\Repository\Views\NavbarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/navbar')]
class NavbarController extends AbstractController
{
    public function __construct(
        private TranslatorInterface $translator
    ) {
    }

    #[Route('/', name: 'app_admin_navbar_index', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        NavbarRepository $navbarRepository
    ): Response {
        /** @var array */
        $rootItems = $navbarRepository->getRootNodes();
        $form = $this->createForm(TreeFormType::class, [
            'items' => $rootItems
        ], [
            'entry_type' => NavbarType::class
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', $this->translator->trans('Navbar items saved successfully.'));
            return $this->redirectToRoute('app_admin_navbar_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('admin/navbar/index.html.twig', [
            'title' => $this->translator->trans('Navbar Items'),
            'form' => $form->createView()
        ]);
    }
}
