<?php

namespace App\Controller\Admin;

use App\Entity\ContentType;
use App\Form\ContentTypeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/content-type')]
class ContentTypeController extends AbstractController
{
    public function __construct(
        private TranslatorInterface $translator
    ) {
    }

    #[Route('/', name: 'app_admin_contentType_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/contentType/index.html.twig', [
            'title' => $this->translator->trans('Content Types'),
        ]);
    }

    #[Route('/new', name: 'app_admin_contentType_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contentType = new ContentType();
        $form = $this->createForm(ContentTypeType::class, $contentType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contentType);
            $entityManager->flush();
            $this->addFlash('success', $this->translator->trans('contentType.created_successfully'));
            return $this->redirectToRoute('app_admin_contentType_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/contentType/new.html.twig', [
            'title' => $this->translator->trans('Add New Content Type'),
            'contentType' => $contentType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_contentType_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ContentType $contentType, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ContentTypeType::class, $contentType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', $this->translator->trans('contentType.updated_successfully'));
            return $this->redirectToRoute('app_admin_contentType_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/contentType/edit.html.twig', [
            'title' => $this->translator->trans('Edit Content Type {name}', ['name' => $contentType->getName()]),
            'contentType' => $contentType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_contentType_delete', methods: ['POST'])]
    public function delete(Request $request, ContentType $contentType, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $contentType->getId(), $request->request->get('_token'))) {
            $entityManager->remove($contentType);
            $entityManager->flush();
            $this->addFlash('success', $this->translator->trans('contentType.deleted_successfully'));
        }

        return $this->redirectToRoute('app_admin_contentType_index', [], Response::HTTP_SEE_OTHER);
    }
}
