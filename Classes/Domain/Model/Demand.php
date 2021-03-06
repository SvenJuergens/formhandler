<?php
namespace Typoheads\Formhandler\Domain\Model;

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

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Demand object for log data
 *
 * @author Reinhard Führicht <rf@typoheads.at>
 */
class Demand extends AbstractEntity
{

    /**
     * @var int
     */
    protected $crdate = 0;

    /**
     * @var string
     */
    protected $ip = '';

    /**
     * @var string
     */
    protected $params = '';

    /**
     * @var bool
     */
    protected $isSpam = 0;

    /**
     * Calculated start timestamp
     *
     * @var string
     */
    protected $startTimestamp;

    /**
     * Calculated end timestamp
     *
     * @var string
     */
    protected $endTimestamp;

    public function getCrdate()
    {
        return $this->crdate;
    }

    public function setCrdate($crdate)
    {
        $this->crdate = (int)$crdate;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function getIsSpam()
    {
        return $this->isSpam;
    }

    public function setIsSpam($isSpam)
    {
        $this->isSpam = $isSpam;
    }

    /**
     * Get calculated start timestamp from query constraints
     *
     * @return string
     */
    public function getStartTimestamp()
    {
        return $this->startTimestamp;
    }

    /**
     * Set calculated start timestamp from query constraints
     *
     * @param string $timestamp
     */
    public function setStartTimestamp($timestamp)
    {
        $this->startTimestamp = $timestamp;
    }

    /**
     * Get calculated end timestamp from query constraints
     *
     * @return string
     */
    public function getEndTimestamp()
    {
        return $this->endTimestamp;
    }

    /**
     * Set calculated end timestamp from query constraints
     *
     * @param string $timestamp
     */
    public function setEndTimestamp($timestamp)
    {
        $this->endTimestamp = $timestamp;
    }
}
