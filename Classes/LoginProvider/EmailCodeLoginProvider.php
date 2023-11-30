<?php
namespace Madj2k\BeDefender\LoginProvider;

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

use Madj2k\CoreExtended\Utility\ClientUtility;
use Madj2k\CoreExtended\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Controller\LoginController;
use TYPO3\CMS\Backend\LoginProvider\LoginProviderInterface;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Class EmailCodeLoginProvider
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel
 * @package Madj2k_BeDefender
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class EmailCodeLoginProvider implements LoginProviderInterface
{
    /**
     * @param \TYPO3\CMS\Fluid\View\StandaloneView $view
     * @param \TYPO3\CMS\Core\Page\PageRenderer $pageRenderer
     * @param \TYPO3\CMS\Backend\Controller\LoginController $loginController
     */
    public function render(StandaloneView $view, PageRenderer $pageRenderer, LoginController $loginController)
    {
        $pageRenderer->loadRequireJsModule('TYPO3/CMS/Backend/UserPassLogin');
        $view->setTemplatePathAndFilename(GeneralUtility::getFileAbsFileName('EXT:be_defender/Resources/Private/Templates/Backend/LoginForm.html'));

        // set client hash
        $view->assign('hash', ClientUtility::getClientHash());

        // if https enabled, refill username and auth_code on failure
        if (GeneralUtility::getIndpEnv('TYPO3_SSL')) {
            $view->assign('presetUsername', GeneralUtility::_GP('username'));
            $view->assign('presetAuthCode', GeneralUtility::_GP('auth_code'));
        }

        // set default value if NOT production
        if (
            (\TYPO3\CMS\Core\Core\Environment::getContext()->isDevelopment())
            || (\TYPO3\CMS\Core\Core\Environment::getContext()->__toString() === 'Production/Staging')
        ){
            $view->assign('presetAuthCode', '12345');
        }
    }
}
