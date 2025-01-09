<?php

namespace App\EventSubscriber;

use App\Repository\Page\UrlRedirectionRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class NotFoundListener
{
    public function __construct(
        private UrlRedirectionRepository $urlRedirectionRepository,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    #[AsEventListener(KernelEvents::EXCEPTION)]
    public function onResponse(ExceptionEvent $event)
    {
        if ($event->getThrowable() instanceof NotFoundHttpException) {
            $request = $event->getRequest();
            $uri = $request->getRequestUri();
            if (
                $urlRedirection = $this->urlRedirectionRepository->findOneBy([
                    'fromUrl' => $uri
                ])
            ) {
                $page = $urlRedirection->getPage();
                $response = new RedirectResponse(
                    $page->getContentType()->getSlug() === 'static-page' ?
                    $this->urlGenerator->generate('app_page_static_view', [
                        'slug' => $page->getSlug(),
                    ]) :
                    $this->urlGenerator->generate('app_page_view', [
                        'contentTypeSlug' => $page->getContentType()->getSlug(),
                        'slug' => $page->getSlug(),
                    ])
                );
                $event->setResponse($response);
                $event->stopPropagation();
            }
        }
    }
}
