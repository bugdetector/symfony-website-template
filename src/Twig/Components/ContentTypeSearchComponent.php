<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Twig\Components;

use App\Entity\ContentType;
use Doctrine\ORM\QueryBuilder;
use Override;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent()]
final class ContentTypeSearchComponent extends DatatableComponent
{
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
                'ct.title' => [
                    'options' => [
                        'label' => 'Title',
                    ]
                ],
            ]
        ];
    }
}
