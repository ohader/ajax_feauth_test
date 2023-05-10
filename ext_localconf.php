<?php
defined('TYPO3') or die();

use OliverHader\AjaxFeauthTest\Controller\PluginController;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

(static function() {
    ExtensionManagementUtility::addTypoScriptSetup(
        sprintf(implode("\n", [
            'tt_content.list.20.ajaxfeauthtest_main = USER',
            'tt_content.list.20.ajaxfeauthtest_main.userFunc = %s->mainAction',
        ]), PluginController::class)
    );
})();
