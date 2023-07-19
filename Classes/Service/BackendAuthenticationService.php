<?php
namespace Madj2k\BeDefender\Service;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use Psr\Log\LoggerAwareInterface;
use TYPO3\CMS\Core\Authentication\AuthenticationService;
use TYPO3\CMS\Core\Crypto\Random;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Service\AbstractService;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Authentication\AbstractUserAuthentication;
use TYPO3\CMS\Core\Utility\HttpUtility;

/**
 * Class BackendUser
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel
 * @package Madj2k_BeDefender
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class BackendAuthenticationService extends AuthenticationService implements LoggerAwareInterface, SingletonInterface
{

    /**
     * Checks if service is available
     *
     * @return bool TRUE if service is available
     */
    public function init() :bool
    {
        return parent::init();
    }


    /**
     * Initializes authentication for this service.
     *
     * @param string $mode Subtype of the service which is used to call the service.
     * @param array $loginData Submitted login form data
     * @param array $authInfo Information array. Holds submitted form data etc.
     * @param \TYPO3\CMS\Core\Authentication\AbstractUserAuthentication $pObj Parent object
     * @return void
     */
    public function initAuth($mode, $loginData, $authInfo, $pObj): void
    {

        parent::initAuth($mode, $loginData, $authInfo, $pObj);

        // add authCode!
        if (GeneralUtility::_GP('auth_code')) {
            $this->login['auth_code'] = GeneralUtility::_GP('auth_code');
        }
    }


    /**
     * Process the submitted login data
     *
     * @param array $loginData Credentials that are submitted and potentially modified by other services
     * @param string $passwordTransmissionStrategy Keyword of how the password has been hashed or encrypted before submission
     * @return bool
     */
    public function processLoginData(array &$loginData, $passwordTransmissionStrategy): bool
    {
        return parent::processLoginData($loginData, $passwordTransmissionStrategy);
    }


    /**
     * Find a user (eg. look up the user record in database when a login is sent)
     *
     * @return mixed User array or FALSE
     */
    public function getUser()
    {
        return parent::getUser();
    }


    /**
     * Authenticate a user: Check submitted user credentials against stored hashed password,
     * check domain lock if configured.
     *
     * Returns one of the following status codes:
     *  >= 200: User authenticated successfully. No more checking is needed by other auth services.
     *  >= 100: User not authenticated; this service is not responsible. Other auth services will be asked.
     *  > 0:    User authenticated successfully. Other auth services will still be asked.
     *  <= 0:   Authentication failed, no more checking needed by other auth services.
     *
     * @param array $user User data
     * @return int Authentication status code, one of 0, 100, 200
     * @throws \TYPO3\CMS\Core\Crypto\PasswordHashing\InvalidPasswordHashException
     */
    public function authUser(array $user): int
    {

        // if no authCode is set, we are not responsible!
        // BUT since we want to enforce the usage of an authCode we return 0 instead of 100 if empty
        if (! $this->login['auth_code']){
            $this->logger->warning('No authCode given.');
            return 0;
        }

        // if authCode is given and it is valid --> do regular login
        if (
            ($user['tx_bedefender_auth_code'])
            && ($user['tx_bedefender_auth_code_use_tstamp'] == 0)
            && ($user['tx_bedefender_auth_code'] == $this->login['auth_code'])
        ) {

            // update tstamp of authCode so that can only be used once
            $this->updateAuthCodeTstampInDatabase(
                $this->db_user['table'],
                (int)$user['uid'],
            );

            // now got to normal login stuff
            return parent::authUser($user);

        // if authCode is not valid, cancel login!
        } else {

            $this->logger->warning('AuthCode invalid.');
            return 0;
        }
    }


    /**
     * Updates the txBedefenderAuthCodeUseTstamp so that a code can only be used once
     *
     * @param string $table Database table of this user, usually 'be_users' or 'fe_users'
     * @param int $uid uid of user record that will be updated
     */
    protected function updateAuthCodeTstampInDatabase(string $table, int $uid): void
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($table);
        $connection->update(
            $table,
            ['tx_bedefender_auth_code_use_tstamp' => time()],
            ['uid' => $uid]
        );
        $this->logger->notice('Automatic password update for user record in ' . $table . ' with uid ' . $uid);
    }
}
