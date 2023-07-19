<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function (string $extKey) {

        $tempCols = [

            'tx_bedefender_auth_code' => [
                'config' => [
                    'type' => 'passthrough',
                ],
            ],

            'tx_bedefender_auth_code_tstamp' => [
                'config' => [
                    'type' => 'passthrough',
                ],
            ],

            'tx_bedefender_auth_code_use_tstamp' => [
                'config' => [
                    'type' => 'passthrough',
                ],
            ],
        ];

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('be_users', $tempCols);
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('be_users', 'tx_bedefender_auth_code, tx_bedefender_auth_code_tstamp, tx_bedefender_auth_code_use_tstamp');

    },
    'be_defender'
);

