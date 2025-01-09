<?php

namespace App\Controller\Admin;

use App\Entity\ContentType;
use App\Entity\Page\Page;
use App\Form\Page\PageType;
use App\Repository\ContentTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/page')]
class PageController extends AbstractController
{
    public function __construct(
        private TranslatorInterface $translator
    ) {
    }

    #[Route('/', name: 'app_admin_page_index', methods: ['GET'])]
    public function index(): Response
    {
        $createForm = $this->createFormBuilder()
            ->add("title", options: [
                'help' => 'Enter page title.'
            ])
            ->add("contentType", EntityType::class, [
                'class' => ContentType::class,
                'choice_label' => 'name',
                'autocomplete' => true,
                'placeholder' => 'Select content type'
            ])
            ->setAction($this->generateUrl("app_admin_page_new"))
            ->getForm();
        return $this->render('admin/page/index.html.twig', [
            'title' => $this->translator->trans('Pages'),
            'form' => $createForm,
        ]);
    }

    #[Route('/new', name: 'app_admin_page_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        ContentTypeRepository $contentTypeRepository,
    ): Response {
        if ($this->isCsrfTokenValid('new', $request->request->get('_token'))) {
            $page = new Page();
            $page->setTitle($request->get('form')['title']);
            $page->setContentType(
                $contentTypeRepository->find($request->get('form')['contentType'])
            );
            $entityManager->persist($page);
            $entityManager->flush();
            $this->addFlash('success', $this->translator->trans('page.created_successfully'));
            return $this->redirectToRoute('app_admin_page_edit', [
                'id' => $page->getId(),
            ], Response::HTTP_SEE_OTHER);
        }
    }

    #[Route('/{id}/edit/{tab}', name: 'app_admin_page_edit', methods: ['GET', 'POST'], defaults: ['tab' => 'general'])]
    public function edit(
        Request $request,
        Page $page,
        EntityManagerInterface $entityManager,
        ?string $tab = 'general'
    ): Response {
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', $this->translator->trans('page.updated_successfully'));
            return $this->redirectToRoute('app_admin_page_edit', [
                'id' => $page->getId(),
                'tab' => $tab,
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/page/edit.html.twig', [
            'title' => $this->translator->trans('Edit Page {title}', ['title' => $page->getTitle()]),
            'page' => $page,
            'form' => $form,
            'tab' => $tab,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_page_delete', methods: ['POST'])]
    public function delete(Request $request, Page $page, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $page->getId(), $request->request->get('_token'))) {
            $entityManager->remove($page);
            $entityManager->flush();
            $this->addFlash('success', $this->translator->trans('page.deleted_successfully'));
        }

        return $this->redirectToRoute('app_admin_page_index', [], Response::HTTP_SEE_OTHER);
    }
}
