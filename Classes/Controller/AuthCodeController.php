<?php
namespace Madj2k\BeDefender\Controller;

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

use Madj2k\Postmaster\Mail\MailMessage;
use Madj2k\Postmaster\Utility\FrontendLocalizationUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Madj2k\BeDefender\Domain\Repository\BackendUserRepository;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

/**
 * Class AuthCodeController
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel
 * @package Madj2k_BeDefender
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class AuthCodeController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * @var \Madj2k\BeDefender\Domain\Repository\BackendUserRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected BackendUserRepository $backendUserRepository;


    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected PersistenceManager $persistenceManager;


    /**
     * action show
     *
     * call: /index.php?type=1689670530&tx_bedefender_authcode[username]=test
     *
     * @param string $username
     * @return void
     * @throws \Exception
     */
    public function generateAction(string $username): void {

        $status = 400;

        /** @var  \Madj2k\BeDefender\Domain\Model\BackendUser $backendUser */
        if (
            ($backendUser = $this->backendUserRepository->findOneByUsername($username))
            && (GeneralUtility::validEmail($backendUser->getEmail()))
        ){

            // add code to backendUser
            $code = \Madj2k\CoreExtended\Utility\GeneralUtility::getUniqueRandomNumber5);
            if (GeneralUtility::getApplicationContext()->isDevelopment()) {
                $code = '12345';
            }
            $backendUser->setTxBedefenderAuthCode($code);
            $backendUser->setTxBedefenderAuthCodeTstamp(time());
            $backendUser->setTxBedefenderAuthCodeUseTstamp(0);

            $this->backendUserRepository->update($backendUser);
            $this->persistenceManager->persistAll();

            // send email
            /** @var \Madj2k\Postmaster\Mail\MailMessage $mailMessage */
            $mailMessage = GeneralUtility::makeInstance(MailMessage::class);

            $mailMessage->setTo($backendUser, array(
                'marker' => array(
                    'authCode' => $code,
                ),
            ));

            $mailMessage->getQueueMail()->setSubject(
                FrontendLocalizationUtility::translate(
                    'email.authCode.subject',
                    'beDefender',
                    null,
                    $backendUser->getLang()
                )
            );

            $settings = $this->getSettings();

            $mailMessage->getQueueMail()->addTemplatePaths($settings['view']['templateRootPaths']);
            $mailMessage->getQueueMail()->setPlaintextTemplate('Email/AuthCode');
            $mailMessage->getQueueMail()->setHtmlTemplate('Email/AuthCode');

            $mailMessage->send();

            // set status
            $status = 200;
        }

        header('Content-Type: application/json');
        echo json_encode(['status' => $status]);
        exit();
    }


    /**
     * Returns TYPO3 settings
     *
     * @param string $which Which type of settings will be loaded
     * @return array
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    protected function getSettings(string $which = ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK): array
    {
        return \Madj2k\CoreExtended\Utility\GeneralUtility::getTypoScriptConfiguration('bedefender', $which);
    }
}
