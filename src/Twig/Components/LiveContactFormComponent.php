<?php

namespace App\Twig\Components;

use App\Entity\ContactForm;
use App\Form\ContactFormAdminType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class LiveContactFormComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ?ContactForm $initialFormData = null;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private FormFactoryInterface $formFactory,
        private RequestStack $requestStack
    ) {
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(
            ContactFormAdminType::class,
            $this->initialFormData,
            [
                'attr' => [
                    'data-action' => 'live#action:prevent',
                    'data-live-action-param' => 'save',
                    'novalidate' => true
                ]
            ]
        );
    }

    #[LiveAction]
    public function save()
    {
        $this->submitForm();

        /** @var ContactForm $contactForm */
        $contactForm = $this->getForm()->getData();

        $this->entityManager->persist($contactForm);
        $this->entityManager->flush();

        $this->addFlash('success', 'Contact form updated successfully.');
    }
}
