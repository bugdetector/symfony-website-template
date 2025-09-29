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
use App\Entity\Category\Category;
use App\Entity\Page\Page;
use Doctrine\ORM\QueryBuilder;
use Override;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent(template: '@base_theme/partials/_datatable.html.twig')]
final class PagesSearchComponent extends DatatableComponent
{
    #[LiveProp(writable: true, url: true)]
    public string $sort = 'p.createdAt';
    #[LiveProp(writable: true, url: true)]
    public string $direction = 'DESC';

    #[LiveProp(writable: false)]
    public ?string $rowTemplateFile = "admin/page/_row.html.twig";

    #[Override]
    public function getQueryBuilder(): QueryBuilder
    {
        /** @var  */
        $query = $this->entityManager->getRepository(Page::class)->createQueryBuilder('p')
        ->join('p.contentType', 'c');
        return $query;
    }

    #[Override]
    public function getTableBuildData(): array
    {
        return [
            'headers' => [
                [
                    'label' => $this->translator->trans("Actions")
                ],
                'p.id' => [
                    'label' => $this->translator->trans("Id"),
                    'sortable' => true,
                ],
                'p.title' => [
                    'label' => $this->translator->trans("Title"),
                    'sortable' => true,
                ],
                'c.name' => [
                    'label' => $this->translator->trans("Content Type"),
                    'sortable' => true,
                ],
                [
                    'label' => $this->translator->trans('Category'),
                ],
                [
                    'label' => $this->translator->trans('Slug'),
                ],
                [
                    'label' => $this->translator->trans('Published'),
                ],
                'p.createdAt' => [
                    'label' => $this->translator->trans('created_at'),
                    'sortable' => true
                ],
                'p.updatedAt' => [
                    'label' => $this->translator->trans('updated_at'),
                    'sortable' => true
                ]
            ],
            "quick_filters" => [
                "p.title" => [],
                'c.name' => [],
            ],
            "filters" => [
                'c.id' => [
                    'type' => EntityType::class,
                    'comparison' => '=',
                    'options' => [
                        'label' => 'Content Type',
                        'class' => ContentType::class,
                        'choice_label' => 'name',
                        'placeholder' => $this->translator->trans('All'),
                    ],
                ],
                'p.title' => [
                    'options' => [
                        'label' => 'Title',
                    ],
                ],
                'p.contentType' => [
                    'type' => EntityType::class,
                    'comparison' => '=',
                    'options' => [
                        'label' => 'Content Type',
                        'class' => ContentType::class,
                        'choice_label' => function (ContentType $contentType) {
                            return $this->translator->trans($contentType->getName());
                        },
                        'placeholder' => 'Select content type',
                    ],
                ],
                'p.category' => [
                    'type' => EntityType::class,
                    'comparison' => '=',
                    'options' => [
                        'label' => 'Category',
                        'class' => Category::class,
                        'choice_label' => 'name',
                    ],
                ],
                'p.published' => [
                    'type' => ChoiceType::class,
                    'options' => [
                        'label' => 'Published',
                        'placeholder' => $this->translator->trans('All'),
                        'choices' => [
                            'Published' => 1,
                            'Unpublished' => 0,
                        ],
                    ],
                ],
                'p.createdAtStart' => [
                    'type' => DateType::class,
                    'column' => 'f.createdAt',
                    'comparison' => '>=',
                    'options' => [
                        'label' => 'Created At (From)',
                    ]
                ],
                'p.createdAtEnd' => [
                    'type' => DateType::class,
                    'column' => 'f.createdAt',
                    'comparison' => '<=',
                    'valueProcessor' => function ($value) {
                        return $value . " 23:59:59";
                    },
                    'options' => [
                        'label' => 'Created At (To)',
                    ]
                ],
            ]
        ];
    }
}
