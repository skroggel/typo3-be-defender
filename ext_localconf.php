<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function(string $extKey)
    {

        // Use popup window to refresh login instead of the AJAX relogin:
        $GLOBALS['TYPO3_CONF_VARS']['BE']['showRefreshLoginPopup'] = 1;

        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['backend']['loginProviders'][1689643564] = [
            'provider' => \Madj2k\BeDefender\LoginProvider\EmailCodeLoginProvider::class,
            'sorting' => 25,
            'icon-class' => 'fa-key',
            'label' => 'LLL:EXT:backend/Resources/Private/Language/locallang.xlf:login.link'
        ];

    },
    'be_defender'
);

