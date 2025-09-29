<?php

namespace App\Theme\WebsiteTheme;

use App\Entity\Page\Page;
use App\Theme\ThemeHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class DefaultLayoutController extends AbstractController
{
    public function __construct(
        public ThemeHelper $theme,
        protected TranslatorInterface $translator
    ) {
        $this->theme = $theme;
        $this->init();
    }

    public function init()
    {
        $this->theme->setLayoutFile('@website-theme/layout/_default.html.twig');
        $this->theme->setThemeDirectory('@website-theme');
        $this->initJsFiles();
        $this->initCssFiles();
    }

    public function initJsFiles()
    {
    }

    public function initCssFiles()
    {
    }

    public function initMetatags(?Page $page = null)
    {
        /** @var Request */
        $request = $this->container->get('request_stack')->getCurrentRequest();

        $appName = $request->server->get('APP_NAME');
        $this->theme->addMetaTag('og:title', [
            'property' => 'og:title',
            'content' => $appName
        ]);
        $this->theme->addMetaTag('og:site_name', [
            'property' => 'og:site_name',
            'content' => $appName
        ]);
        $this->theme->addMetaTag('og:url', [
            'property' => 'og:url',
            'content' => $request->getUri()
        ]);
        if ($page) {
            $this->theme->addMetaTag('og:title', [
                'property' => 'og:title',
                'content' => $page->getTitle()
            ]);
            $this->theme->addMetaTag('description', [
                'property' => 'description',
                'content' => $page->getShortDescription()
            ]);
            $this->theme->addMetaTag('og:description', [
                'property' => 'og:description',
                'content' => $page->getShortDescription()
            ]);
            $this->theme->addMetaTag('og:image', [
                'property' => 'og:image',
                'content' => $request->getSchemeAndHttpHost() . (
                    $page->getCoverImage()?->__toString() ?: '/website-theme/assets/img/placeholder.png'
                )
            ]);
        }
    }
}
