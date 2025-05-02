<?php

namespace App\Twig\Components\Forms;

use App\Entity\Page\Page;
use App\Form\Page\PageType;
use App\Repository\FileRepository;
use App\Twig\Components\FormType\LiveAsyncFileInputTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\LiveCollectionTrait;

#[AsLiveComponent]
final class LivePageForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;
    use ComponentToolsTrait;
    use LiveCollectionTrait;
    use LiveAsyncFileInputTrait;

    #[LiveProp()]
    public ?Page $initialFormData = null;

    #[LiveProp()]
    public ?string $tab = 'general';

    public function __construct(
        protected FileRepository $fileRepository,
        protected SluggerInterface $slugger,
        protected EntityManagerInterface $entityManager,
        protected ParameterBagInterface $parameterBag,
        private FormFactoryInterface $formFactory,
        private UrlGeneratorInterface $router,
        protected TranslatorInterface $translator,
    ) {
    }

    protected function instantiateForm(): FormInterface
    {
        if (!$this->initialFormData) {
            $this->initialFormData = new Page();
        }
        $form = $this->createForm(PageType::class, $this->initialFormData, [
            'attr' => [
                'data-action' => 'live#action:prevent',
                'data-live-action-param' => 'save',
                'novalidate' => true
            ]
        ]);
        return $form;
    }

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager)
    {
        $this->submitForm();
        /** @var Position */
        $object = $this->getForm()->getData();
        $entityManager->persist($object);
        $entityManager->flush();

        $this->addFlash('success', $this->translator->trans(
            $this->initialFormData ? 'page.updated_successfully' : 'page.created_successfully'
        ));
    }
}
