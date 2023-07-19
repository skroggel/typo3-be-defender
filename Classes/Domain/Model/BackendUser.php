<?php
namespace Madj2k\BeDefender\Domain\Model;

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

/**
 * Class BackendUser
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel
 * @package Madj2k_BeDefender
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class BackendUser extends \Madj2k\CoreExtended\Domain\Model\BackendUser
{
    /**
     * @var string
     */
    protected string $txBedefenderAuthCode = '';


    /**
     * @var int
     */
    protected int $txBedefenderAuthCodeTstamp = 0;


    /**
     * @var int
     */
    protected int $txBedefenderAuthCodeUseTstamp = 0;


    /**
     * Gets the txBedefenderAuthCode of the user
     *
     * @param string $txBedefenderAuthCode
     */
    public function setTxBedefenderAuthCode(string $txBedefenderAuthCode)
    {
        $this->txBedefenderAuthCode = $txBedefenderAuthCode;
    }


    /**
     * Gets the txBedefenderAuthCode of the user
     *
     * @return string
     */
    public function getTxBedefenderAuthCode(): string
    {
        return $this->txBedefenderAuthCode;
    }


    /**
     * Gets the authCodeTstamp of the user
     *
     * @param int $txBedefenderAuthCodeTstamp
     */
    public function setTxBedefenderAuthCodeTstamp(int $txBedefenderAuthCodeTstamp)
    {
        $this->txBedefenderAuthCodeTstamp = $txBedefenderAuthCodeTstamp;
    }


    /**
     * Gets the txBedefenderAuthCodeTstamp of the user
     *
     * @return int
     */
    public function getTxBedefenderAuthCodeTstamp(): int
    {
        return $this->txBedefenderAuthCodeTstamp;
    }


    /**
     * Gets the authCodeUseTstamp of the user
     *
     * @param int $txBedefenderAuthCodeUseTstamp
     */
    public function setTxBedefenderAuthCodeUseTstamp(int $txBedefenderAuthCodeUseTstamp)
    {
        $this->txBedefenderAuthCodeUseTstamp = $txBedefenderAuthCodeUseTstamp;
    }


    /**
     * Gets the txBedefenderAuthCodeUseTstamp of the user
     *
     * @return int
     */
    public function getTxBedefenderAuthCodeUseTstamp(): int
    {
        return $this->txBedefenderAuthCodeUseTstamp;
    }

}
