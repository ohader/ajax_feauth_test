<?php

namespace OliverHader\AjaxFeauthTest\Middlewares;

use OliverHader\AjaxFeauthTest\Controller\AjaxController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AjaxDispatcher implements MiddlewareInterface
{
    use AjaxUriTrait;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $action = $this->resolvePrefixedPayload($request);
        $isPostRequest = $request->getMethod() === 'POST';

        if ($isPostRequest && $action === 'preflight') {
            return GeneralUtility::makeInstance(AjaxController::class)->preflightAction($request);
        }
        // Actually, authentication is still done by TYPO3.
        // This package just shows a "post-auth" status.
        $response = $handler->handle($request);
        if ($isPostRequest && $action === 'auth') {
            $authResponse = GeneralUtility::makeInstance(AjaxController::class)->authAction($request);
            foreach ($response->getHeader('Set-Cookie') as $cookie) {
                $authResponse = $authResponse->withHeader('Set-Cookie', $cookie);
            }
            return $authResponse;
        }
        return $response;
    }
}
