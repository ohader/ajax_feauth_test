<?php
defined('TYPO3') or die();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

(static function() {
    ExtensionManagementUtility::addPlugin(
        ['AJAX FeAuth Test', 'ajaxfeauthtest_main'],
        'list_type',
        'ajax_feauth_test'
    );
})();
