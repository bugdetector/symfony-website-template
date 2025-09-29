<?php

namespace App\Controller\Admin;

use App\Entity\ContactForm;
use App\Form\ContactFormAdminType;
use App\Repository\ContactFormRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/contact_form')]
class ContactFormController extends AbstractController
{
    public function __construct(
        private TranslatorInterface $translator
    ) {
    }

    #[Route('/', name: 'app_admin_contact_form_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/contact_form/index.html.twig', [
            'title' => $this->translator->trans('Contact Forms')
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_contact_form_edit', methods: ['GET'])]
    public function edit(ContactForm $contactForm): Response
    {
        return $this->render('admin/contact_form/edit.html.twig', [
            'contact_form' => $contactForm,
            'title' => $this->translator->trans('Edit Contact {name}', ['{name}' => $contactForm->getName()]),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_contact_form_delete', methods: ['POST'])]
    public function delete(Request $request, ContactForm $contactForm, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $contactForm->getId(), $request->request->get('_token'))) {
            $entityManager->remove($contactForm);
            $entityManager->flush();
            $this->addFlash(
                'success',
                $this->translator->trans('Contact form deleted successfully')
            );
        }

        return $this->redirectToRoute('app_admin_contact_form_index', [], Response::HTTP_SEE_OTHER);
    }
}
