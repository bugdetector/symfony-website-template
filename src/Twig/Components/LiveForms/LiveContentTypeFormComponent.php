<?php

namespace App\Twig\Components\LiveForms;

use App\Entity\ContentType;
use App\Form\ContentTypeType;
use App\Twig\Components\FormType\LiveAsyncFileInputTrait;
use App\Twig\Components\Traits\SortableFormComponentTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class LiveContentTypeFormComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;
    use LiveAsyncFileInputTrait;
    use SortableFormComponentTrait;

    #[LiveProp()]
    public ?ContentType $initialFormData = null;

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected TranslatorInterface $translator,
    ) {
    }

    protected function instantiateForm(): FormInterface
    {
        if (!$this->initialFormData) {
            $this->initialFormData = new ContentType();
        }
        $form = $this->createForm(ContentTypeType::class, $this->initialFormData, [
            'attr' => [
                'data-action' => 'live#action:prevent',
                'data-live-action-param' => 'save',
                'novalidate' => true
            ]
        ]);
        return $form;
    }

    #[LiveAction]
    public function save()
    {
        $this->submitForm();
        /** @var ContentType */
        $object = $this->getForm()->getData();
        $isCreate = !boolval($object->getId());
        $this->entityManager->persist($object);
        $this->entityManager->flush();

        $this->addFlash('success', $this->translator->trans(
            $isCreate ? 'Content Type created successfully.' : 'Content Type updated successfully.'
        ));

        $this->emit('contentTypeSaved');
    }

    private function getEntityClass(): string
    {
        return ContentType::class;
    }
}
