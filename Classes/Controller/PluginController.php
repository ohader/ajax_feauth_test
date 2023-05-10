<?php

namespace OliverHader\AjaxFeauthTest\Controller;

use OliverHader\AjaxFeauthTest\Middlewares\AjaxUriTrait;
use TYPO3\CMS\Core\Page\JavaScriptModuleInstruction;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class PluginController
{
    use AjaxUriTrait;

    private ?ContentObjectRenderer $contentObjectRenderer = null;

    public function __construct(
        private readonly StandaloneView $view,
        private readonly PageRenderer $pageRenderer,
    )
    {
    }

    public function mainAction(string $content): string
    {
        $prefix = $this->resolveAjaxUriPrefix($this->contentObjectRenderer->getRequest());
        $this->pageRenderer->getJavaScriptRenderer()->addJavaScriptModuleInstruction(
            JavaScriptModuleInstruction::create('@oliver-hader/ajax-feauth-test/login.js')->instance([
                'preflightUrl' => $prefix . 'preflight',
                'authUrl' => $prefix . 'auth',
            ])
        );
        $this->view->setTemplatePathAndFilename(
            GeneralUtility::getFileAbsFileName('EXT:ajax_feauth_test/Resources/Private/Templates/Plugin.html')
        );
        return $this->view->render();
    }

    public function setContentObjectRenderer(ContentObjectRenderer $contentObjectRenderer): void
    {
        $this->contentObjectRenderer = $contentObjectRenderer;
    }
}
