<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Twig\WebSite;

use App\Entity\ContentType;
use App\Entity\Page\Page;
use App\Twig\Components\DatatableComponent;
use Doctrine\ORM\QueryBuilder;
use Override;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent(name: 'ContentListComponent')]
class ContentListComponent extends DatatableComponent
{
    #[LiveProp(writable: true)]
    public ?string $contentTypeSlug = null;
    #[LiveProp(writable: true, url: true)]
    public string $sort = 'p.createdAt';
    #[LiveProp(writable: true, url: true)]
    public string $direction = 'DESC';

    #[LiveProp(writable: true)]
    public ?string $title = null;

    public $paginationEnabled = true;

    #[Override]
    public function getQueryBuilder(): QueryBuilder
    {
        /** @var  */
        $query = $this->entityManager->getRepository(Page::class)
        ->createQueryBuilder('p')
        ->andWhere('p.published = 1');
        if ($this->contentTypeSlug) {
            /** @var ContentType */
            $contentType = $this->entityManager->getRepository(ContentType::class)->findOneBy([
                'slug' => $this->contentTypeSlug
            ]);
            $query->andWhere('p.contentType = :content_type_id')
            ->setParameter(':content_type_id', $contentType?->getId());
        }
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
                'c.name' => [
                    'label' => $this->translator->trans("Content Type"),
                    'sortable' => true,
                ],
                'p.title' => [
                    'label' => $this->translator->trans("Title"),
                    'sortable' => true,
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
                'p.title' => [
                    'label' => 'Name'
                ],
                'c.name' => [
                    'label' => 'Content Type',
                ]
            ]
        ];
    }
}
