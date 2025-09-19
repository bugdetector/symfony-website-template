<?php

namespace App\Twig\Components\LiveForms;

use App\Entity\Menu\Menu;
use App\Form\Menu\MenuFormType;
use App\Twig\Components\FormType\TranslatableFormTrait;
use App\Twig\Components\Traits\SortableFormComponentTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class LiveMenuFormComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;
    use SortableFormComponentTrait;
    use ComponentToolsTrait;
    use TranslatableFormTrait;

    #[LiveProp()]
    public ?Menu $initialFormData = null;

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected TranslatorInterface $translator,
        string $locale,
    ) {
        $this->defaultLocale = $locale;
        $this->locale = $locale;
    }


    protected function instantiateForm(): FormInterface
    {
        if (!$this->initialFormData) {
            $this->initialFormData = new Menu();
        } elseif ($this->locale && $this->initialFormData->getId()) {
            $this->getTranslatableListener()->setTranslatableLocale($this->locale);
            $this->entityManager->refresh($this->initialFormData);
        }
        $form = $this->createForm(MenuFormType::class, $this->initialFormData, [
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
        /** @var Menu */
        $object = $this->getForm()->getData();
        $isCreate = !boolval($object->getId());
        $this->entityManager->persist($object);
        $this->entityManager->flush();

        $this->addFlash('success', $this->translator->trans(
            $isCreate ? 'Menu created successfully.' : 'Menu updated successfully.'
        ));
        $this->dispatchBrowserEvent('app-hide-modal');
        $this->emit('reloadSortableForm');
    }

    private function getEntityClass(): string
    {
        return Menu::class;
    }
}
