<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function(string $extKey)
    {

        //=================================================================
        // Configure Plugin
        //=================================================================
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Madj2k.' . $extKey,
            'AuthCode',
            array(
                'AuthCode' => 'generate',
            ),
            // non-cacheable actions
            array(
                'AuthCode' => 'generate',
            )
        );

        //=================================================================
        // Add JS to Backend-Login for AJAX-Request
        //=================================================================
        if( TYPO3_MODE == "BE") {
            $renderer = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Page\PageRenderer::class);
            $renderer->addJsFile('EXT:be_defender/Resources/Public/Js/Backend.min.js',
                'text/javascript',
                false,
                false,
                '',
                true,
                '|',
                true,
                ''
            );
        }

        //=================================================================
        // ATTENTION: deactivated due to faulty mapping in TYPO3 9.5
        // Add XClasses for extending existing classes
        //=================================================================
//        // for TYPO3 12+
//        $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\Madj2k\CoreExtended\Domain\Model\BackendUser::class] = [
//            'className' => \Madj2k\BeDefender\Domain\Model\BackendUser::class
//        ];
//
//        // for TYPO3 9.5 - 11.5 only, not required for TYPO3 12
//        \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\Container\Container::class)
//            ->registerImplementation(
//                \Madj2k\CoreExtended\Domain\Model\BackendUser::class,
//                \Madj2k\BeDefender\Domain\Model\BackendUser::class
//            );

        //=================================================================
        // Add TypoScript automatically
        //=================================================================
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
            'BeDefender',
            'constants',
            '<INCLUDE_TYPOSCRIPT: source="FILE: EXT:be_defender/Configuration/TypoScript/constants.typoscript">'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
            'BeDefender',
            'setup',
            '<INCLUDE_TYPOSCRIPT: source="FILE: EXT:be_defender/Configuration/TypoScript/setup.typoscript">'
        );

        //=================================================================
        // Add Login-Services
        //=================================================================
        // Use popup window to refresh login instead of the AJAX relogin:
        //$GLOBALS['TYPO3_CONF_VARS']['BE']['showRefreshLoginPopup'] = 1;

        // override default login provider in order to override the template
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['backend']['loginProviders'][1433416747]['provider'] = \Madj2k\BeDefender\LoginProvider\EmailCodeLoginProvider::class;

        // Register service with TYPO3
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService(
            $extKey,
            'auth',
            'tx_bedefender_service',
            [
                'title' => 'Authentication with AuthCode via Email',
                'description' => 'Authentication with AuthCode via Email',
                'subtype' => 'processLoginDataBE,getUserBE,authUserBE',
                'available' => true,
                'priority' => 60,
                'quality' => 50,
                'os' => '',
                'exec' => '',
                'className' => \Madj2k\BeDefender\Service\BackendAuthenticationService::class
            ]
        );

    },
    'be_defender'
);

