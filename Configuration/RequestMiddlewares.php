<?php

use OliverHader\AjaxFeauthTest\Middlewares\AjaxDispatcher;

defined('TYPO3') or die();

return [
    'frontend' => [
        'oliver-hader/ajax-feauth-test/ajax-dispatcher' => [
            'target' => AjaxDispatcher::class,
            'after' => [
                'typo3/cms-core/request-token-middleware',
                'typo3/cms-frontend/authentication',
            ],
            'before' => [
                'typo3/cms-frontend/tsfe',
                'typo3/cms-redirects/redirecthandler',
                'typo3/cms-frontend/base-redirect-resolver',
            ],
        ]
    ],
];
