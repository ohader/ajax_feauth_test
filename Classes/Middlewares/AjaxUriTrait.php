<?php

namespace OliverHader\AjaxFeauthTest\Middlewares;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;

trait AjaxUriTrait
{
    private function resolvePrefixedPayload(ServerRequestInterface $request): ?string
    {
        $prefix = $this->resolveAjaxUriPrefix($request);
        $normalizedParams = $request->getAttribute('normalizedParams');
        $isAjaxUriPrefixed = str_starts_with($normalizedParams->getRequestUri(), $prefix);
        if (!$isAjaxUriPrefixed) {
            return null;
        }
        return substr($normalizedParams->getRequestUri(), strlen($prefix));
    }

    private function resolveAjaxUriPrefix(ServerRequestInterface $request): string
    {
        $normalizedParams = $request->getAttribute('normalizedParams');
        $siteLanguage = $request->getAttribute('siteLanguage');
        $site = $request->getAttribute('site');

        if ($site instanceof Site) {
            $siteLanguage = $siteLanguage instanceof SiteLanguage ? $siteLanguage : $site->getDefaultLanguage();
            $uri = $siteLanguage->getBase();
        } else {
            $uri = $normalizedParams->getSitePath();
        }

        return (string)$uri->withPath(rtrim($uri->getPath(), '/') . '/@api/ajax-feauth-test/');
    }
}
