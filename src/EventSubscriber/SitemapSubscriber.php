<?php

namespace App\EventSubscriber;

use App\Repository\Page\PageRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Service\UrlContainerInterface;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Contracts\Translation\TranslatorInterface;

class SitemapSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private PageRepository $pageRepository,
        private TranslatorInterface $translator,
        private RequestStack $requestStack,
        private LocaleSwitcher $localeSwitcher,
    ) {
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            SitemapPopulateEvent::class => 'populate',
        ];
    }

    /**
     * @param SitemapPopulateEvent $event
     */
    public function populate(SitemapPopulateEvent $event): void
    {
        $currentRequest = $this->requestStack->getCurrentRequest();
        if ($currentRequest && $currentRequest->get('_locale')) {
            $this->localeSwitcher->setLocale($currentRequest->get('_locale'));
        }
        $this->registerPageUrls($event->getUrlContainer(), $event->getUrlGenerator());
    }

    /**
     * @param UrlContainerInterface $urls
     * @param UrlGeneratorInterface $router
     */
    public function registerPageUrls(UrlContainerInterface $urls, UrlGeneratorInterface $router): void
    {
        $pages = $this->pageRepository->findBy(['published' => 1]);

        foreach ($pages as $page) {
            $urls->addUrl(
                new UrlConcrete(
                    $page->getContentType()->getSlug() === 'static-page' ?
                    $router->generate(
                        'app_page_static_view',
                        [
                            '_locale' => $this->translator->getLocale(),
                            'slug' => $page->getSlug()
                        ],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    ) :
                    $router->generate(
                        'app_page_view',
                        [
                            '_locale' => $this->translator->getLocale(),
                            'contentTypeSlug' => $page->getContentType()->getSlug(),
                            'slug' => $page->getSlug()
                        ],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    ),
                    $page->getUpdatedAt()
                ),
                'page'
            );
        }
    }
}
