<?php

namespace App\Controller;

use App\Entity\Page\Page;
use App\Repository\Page\PageRepository;
use App\Theme\WebsiteTheme\DefaultLayoutController;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PageController extends DefaultLayoutController
{
    #[Route('/{_locale}', name: 'homepage', priority: -100, options: ['sitemap' => true])]
    public function home(PageRepository $pageRepository, Request $request): Response
    {
        $page = $pageRepository->findOneBy([
            "slug" => "home"
        ]);
        if (!$page) {
            throw $this->createNotFoundException();
        }
        if (!($page->isPublished())) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }
        if ($request->query->get('_locale') || $request->attributes->get('_locale') != $this->translator->getLocale()) {
            return $this->redirectToRoute('homepage', [
                '_locale' => $this->translator->getLocale(),
            ]);
        }
        $this->initMetatags($page);
        return $this->render('page/index.html.twig', [
            'title' => $page->getTitle(),
            'page' => $page,
        ]);
    }

    #[Route('/{_locale}/{contentTypeSlug}/{slug}', name: 'app_page_view', priority: -99)]
    public function page(
        #[MapEntity(expr: 'repository.findByContentTypeSlugAndSlug(contentTypeSlug, slug)')] Page $page,
        Request $request,
    ): Response {
        if (!($page->isPublished())) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }
        if ($page->getContentType()->getSlug() === 'static-page') {
            throw $this->createNotFoundException('Page not found');
        }
        if ($request->query->get('_locale')) {
            return $this->redirectToRoute('app_page_view', [
                '_locale' => $request->query->get('_locale'),
                'contentTypeSlug' => $page->getContentType()->getSlug(),
                'slug' => $page->getSlug(),
            ], 301);
        }
        $this->initMetatags($page);
        return $this->render('page/index.html.twig', [
            'title' => $page->getTitle(),
            'page' => $page,
        ]);
    }

    #[Route('/{_locale}/{slug}', name: 'app_page_static_view', priority: -99)]
    public function staticPages(
        Page $page,
        Request $request,
    ): Response {
        if (!($page->isPublished())) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }
        if ($page->getContentType()->getSlug() !== 'static-page') {
            throw $this->createNotFoundException('Page not found');
        }
        if ($request->query->get('_locale')) {
            return $this->redirectToRoute('app_page_static_view', [
                '_locale' => $request->query->get('_locale'),
                'slug' => $page->getSlug(),
            ], 301);
        }
        $this->initMetatags($page);
        return $this->render('page/index.html.twig', [
            'title' => $page->getTitle(),
            'page' => $page,
        ]);
    }

    #[Route('/{contentTypeSlug}/{slug}', name: 'app_page_view_redirect', priority: -100)]
    public function pageRedirect(
        #[MapEntity(expr: 'repository.findByContentTypeSlugAndSlug(contentTypeSlug, slug)')] Page $page,
    ): Response {
        if (!($page->isPublished())) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }
        if ($page->getContentType()->getSlug() === 'static-page') {
            throw $this->createNotFoundException('Page not found');
        }
        return $this->redirectToRoute('app_page_view', [
            '_locale' => $this->translator->getLocale(),
            'contentTypeSlug' => $page->getContentType()->getSlug(),
            'slug' => $page->getSlug(),
        ], 301);
    }

    #[Route('/{slug}', name: 'app_page_static_view_redirect', priority: -100)]
    public function staticPagesRedirect(
        Page $page,
    ): Response {
        if (!($page->isPublished())) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }
        if ($page->getContentType()->getSlug() !== 'static-page') {
            throw $this->createNotFoundException('Page not found');
        }
        return $this->redirectToRoute('app_page_static_view', [
            '_locale' => $this->translator->getLocale(),
            'slug' => $page->getSlug(),
        ], 301);
    }
}
