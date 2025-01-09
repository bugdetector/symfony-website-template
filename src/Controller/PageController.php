<?php

namespace App\Controller;

use App\Entity\Page\Page;
use App\Repository\Page\PageRepository;
use App\Theme\WebsiteTheme\DefaultLayoutController;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends DefaultLayoutController
{
    #[Route('/', name: 'home', priority: -100)]
    public function home(PageRepository $pageRepository): Response
    {
        $page = $pageRepository->findOneBy([
            "slug" => "home"
        ]);
        if(!$page){
            throw $this->createNotFoundException();
        }
        if (!($page->isPublished())) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }
        $this->initMetatags($page);
        return $this->render('page/index.html.twig', [
            'title' => $page->getTitle(),
            'page' => $page,
        ]);
    }

    #[Route('/{contentTypeSlug}/{slug}', name: 'app_page_view', priority: -100)]
    public function page(
        #[MapEntity(mapping: ['contentTypeSlug' => 'contentType.slug', 'slug' => 'slug'])] Page $page,
    ): Response {
        if (!($page->isPublished())) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }
        if ($page->getContentType()->getSlug() === 'static-page') {
            throw $this->createNotFoundException('Page not found');
        }
        $this->initMetatags($page);
        return $this->render('page/index.html.twig', [
            'title' => $page->getTitle(),
            'page' => $page,
        ]);
    }

    #[Route('/{slug}', name: 'app_page_static_view', priority: -100)]
    public function staticPages(
        Page $page,
    ): Response {
        if (!($page->isPublished())) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }
        if ($page->getContentType()->getSlug() !== 'static-page') {
            throw $this->createNotFoundException('Page not found');
        }
        $this->initMetatags($page);
        return $this->render('page/index.html.twig', [
            'title' => $page->getTitle(),
            'page' => $page,
        ]);
    }
}
