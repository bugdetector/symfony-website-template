<?php

namespace App\Controller\Admin;

use App\Entity\ContentType;
use App\Entity\Page\Page;
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
                'choice_label' => function (ContentType $contentType) {
                    return $this->translator->trans($contentType->getName());
                },
                'placeholder' => 'Select content type'
            ])
            ->setAction($this->generateUrl("app_admin_page_new"))
            ->getForm();
        return $this->render('admin/page/index.html.twig', [
            'title' => $this->translator->trans('Pages'),
            'form' => $createForm,
        ]);
    }

    #[Route('/new', name: 'app_admin_page_new', methods: ['POST'])]
    public function new(
        Request $request,
        ContentTypeRepository $contentTypeRepository,
        EntityManagerInterface $entityManager,
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
        return $this->redirectToRoute('app_admin_page_index');
    }

    #[Route('/{id}/edit', name: 'app_admin_page_edit', methods: ['GET'])]
    public function edit(Page $page): Response
    {
        return $this->render('admin/page/edit.html.twig', [
            'title' => $this->translator->trans('Edit Page {title}', ['title' => $page->getTitle()]),
            'page' => $page,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_page_delete', methods: ['POST'])]
    public function delete(Request $request, Page $page, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $page->getId(), $request->request->get('_token'))) {
            $entityManager->remove($page);
            $entityManager->flush();
            $this->addFlash('success', $this->translator->trans('Page deleted successfully.'));
        }

        return $this->redirectToRoute('app_admin_page_index', [], Response::HTTP_SEE_OTHER);
    }
}
