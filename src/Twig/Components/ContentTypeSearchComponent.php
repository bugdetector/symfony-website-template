<?php

namespace App\Twig\Components;

use App\Entity\ContentType;
use Doctrine\ORM\QueryBuilder;
use Override;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent(template: '@base_theme/partials/_datatable.html.twig')]
final class ContentTypeSearchComponent extends DatatableComponent
{
    #[LiveProp(writable: true, url: true)]
    public string $sort = 'ct.createdAt';
    #[LiveProp(writable: true, url: true)]
    public string $direction = 'DESC';

    #[LiveProp(writable: false)]
    public ?string $rowTemplateFile = "admin/contentType/_row.html.twig";

    #[Override]
    public function getQueryBuilder(): QueryBuilder
    {
        return $this->entityManager->getRepository(ContentType::class)->createQueryBuilder('ct');
    }

    #[Override]
    public function getTableBuildData(): array
    {
        return [
            'headers' => [
                [
                    'label' => $this->translator->trans("Actions")
                ],
                'ct.id' => [
                    'label' => $this->translator->trans("Id"),
                    'sortable' => true,
                ],
                'ct.name' => [
                    'label' => $this->translator->trans("Name"),
                    'sortable' => true,
                ],
                [
                    'label' => $this->translator->trans('Slug'),
                ],
                'ct.createdAt' => [
                    'label' => $this->translator->trans('created_at'),
                    'sortable' => true
                ],
                'ct.updatedAt' => [
                    'label' => $this->translator->trans('updated_at'),
                    'sortable' => true
                ]
            ],
            "quick_filters" => [
                "ct.name" => []
            ],
            "filters" => [
                'ct.name' => [
                    'options' => [
                        'label' => 'Name',
                    ],
                ],
            ]
        ];
    }

    #[LiveListener('contentTypeSaved')]
    public function onContentTypeSaved()
    {
    }
}
