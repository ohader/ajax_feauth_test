<?php
defined('TYPO3') or die();

return [
    'dependencies' => ['core'],
    'imports' => [
        '@oliver-hader/ajax-feauth-test/' => 'EXT:ajax_feauth_test/Resources/Public/JavaScript/',
    ],
];
