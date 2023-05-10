<?php

namespace OliverHader\AjaxFeauthTest\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\SecurityAspect;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Security\RequestToken;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AjaxController
{
    public function __construct(
        private readonly Context $context,
    )
    {
    }

    public function preflightAction(ServerRequestInterface $request): ResponseInterface
    {
        $frontendUser = $request->getAttribute('frontend.user');
        $frontendUserAspect = $this->context->getAspect('frontend.user');
        $data = [
            'requestToken' => [
                'value' => $this->provideRequestToken([3]),
                'paramName' => RequestToken::PARAM_NAME,
                'headerName' => RequestToken::HEADER_NAME,
            ],
            'user' => [
                'loggedIn' => $frontendUserAspect->isLoggedIn(),
                'groups' => $frontendUserAspect->getGroupNames(),
                'username' => $frontendUser?->user['username'] ?? null,
            ],
        ];
        return GeneralUtility::makeInstance(JsonResponse::class, $data);
    }

    public function authAction(ServerRequestInterface $request): ResponseInterface
    {
        $frontendUser = $request->getAttribute('frontend.user');
        $frontendUserAspect = $this->context->getAspect('frontend.user');
        $data = [
            'user' => [
                'loggedIn' => $frontendUserAspect->isLoggedIn(),
                'groups' => $frontendUserAspect->getGroupNames(),
                'username' => $frontendUser?->user['username'] ?? null,
            ],
        ];
        return GeneralUtility::makeInstance(JsonResponse::class, $data);
    }

    private function provideRequestToken(array $storagePageIds): string
    {
        $nonce = SecurityAspect::provideIn($this->context)->provideNonce();
        return RequestToken::create('core/user-auth/fe')
            ->withMergedParams(['pid' => implode(',', $storagePageIds)])
            ->toHashSignedJwt($nonce);
    }
}
