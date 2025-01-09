<?php

namespace App\Twig\WebSite;

use App\Entity\ContactForm as EntityContactForm;
use App\Form\ContactFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent(name: 'website-contact-form', template: "themes/website-theme/components/contact-form.twig")]
class ContactForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    public ?EntityContactForm $initialFormData = null;

    public function __construct(
        private FormFactoryInterface $formFactory,
        private UrlGeneratorInterface $router
    ) {
    }

    protected function instantiateForm(): FormInterface
    {
        $form = $this->createForm(ContactFormType::class, $this->initialFormData, [
            'attr' => [
                'data-action' => 'live#action:prevent',
                'data-live-action-param' => 'save'
            ]
        ]);
        return $form;
    }

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->submitForm();

        $contactForm = $this->getForm()->getData();
        $entityManager->persist($contactForm);
        $entityManager->flush();

        $this->initialFormData = new EntityContactForm();
        $this->addFlash('success', 'Mesajınızı aldık, teşekkür ederiz.');
        $this->formValues = [];

        $email = (new TemplatedEmail())
            ->to(...explode(';', $this->getParameter('report.emails')))
            ->subject('İletişim Formu')
            ->htmlTemplate('contact_form/email.html.twig')
            ->context([
                'contactForm' => $contactForm
            ]);
        $mailer->send($email);
    }
}
