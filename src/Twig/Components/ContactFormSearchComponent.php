<?php

namespace App\Twig\Components;

use App\Entity\ContactForm;
use Doctrine\ORM\QueryBuilder;
use Override;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent(template: '@base_theme/partials/_datatable.html.twig')]
final class ContactFormSearchComponent extends DatatableComponent
{
    #[LiveProp(writable: true, url: true)]
    public string $sort = 'cf.createdAt';
    #[LiveProp(writable: true, url: true)]
    public string $direction = 'DESC';

    #[LiveProp(writable: false)]
    public ?string $rowTemplateFile = "admin/contact_form/_row.html.twig";

    #[Override]
    public function getQueryBuilder(): QueryBuilder
    {
        return $this->entityManager->getRepository(ContactForm::class)->createQueryBuilder('cf');
    }

    #[Override]
    public function getTableBuildData(): array
    {
        return [
            'headers' => [
                [
                    'label' => $this->translator->trans("Actions")
                ],
                'cf.id' => [
                    'label' => $this->translator->trans("Id"),
                    'sortable' => true,
                ],
                'cf.name' => [
                    'label' => $this->translator->trans("Name"),
                    'sortable' => true,
                ],
                'cf.email' => [
                    'label' => $this->translator->trans('Email'),
                    'sortable' => true,
                ],
                'cf.phone' => [
                    'label' => $this->translator->trans('Phone'),
                ],
                'cf.createdAt' => [
                    'label' => $this->translator->trans('created_at'),
                    'sortable' => true
                ],
                'cf.updatedAt' => [
                    'label' => $this->translator->trans('updated_at'),
                    'sortable' => true
                ]
            ],
            "quick_filters" => [
                "cf.name" => [],
                "cf.email" => []
            ],
            "filters" => [
                'cf.name' => [
                    'options' => [
                        'label' => 'Name',
                    ],
                ],
                'cf.email' => [
                    'options' => [
                        'label' => 'Email',
                    ],
                ],
            ]
        ];
    }
}
