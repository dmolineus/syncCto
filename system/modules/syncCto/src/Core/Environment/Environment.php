<?php

/**
 * Contao Open Source CMS
 *
 * @copyright  MEN AT WORK 2014
 * @package    syncCto
 * @license    GNU/LGPL
 * @filesource
 */

namespace SyncCto\Core\Environment;

use SyncCto\ClassCaller;
use SyncCto\Core\Config;
use SyncCto\Core\Helper;

class Environment implements IEnvironment
{
    /**
     * @var \Database
     */
    protected $objDatabase = null;

    /**
     * @var Config
     */
    protected $objConfig = null;

    /**
     * @var Config
     */
    protected $objHelper = null;

    /**
     * @var ClassCaller
     */
    protected $objClassCaller = null;

    /**
     * Get the Database from the System.
     *
     * @return \Database
     */
    public function getSystemDatabase()
    {
        return $this->objDatabase;
    }

    /**
     * Set the database.
     *
     * @param \Database $objDatabase
     *
     * @return IEnvironment
     */
    public function setSystemDatabase($objDatabase)
    {
        $this->objDatabase = $objDatabase;

        return $this;
    }

    /**
     * Return the the container with all settings for syncCto.
     *
     * @return Config
     */
    public function getSyncCtoConfig()
    {
        return $this->objConfig;
    }

    /**
     * Set the config container.
     *
     * @param Config $objConfig
     *
     * @return IEnvironment
     */
    public function setSyncCtoConfig($objConfig)
    {
        $this->objConfig = $objConfig;

        return $this;
    }

    /**
     * Get the helper.
     *
     * @return Helper
     */
    public function getHelper()
    {
        return $this->objHelper;
    }

    /**
     * Set the helper.
     *
     * @param  Helper $objHelper
     *
     * @return IEnvironment
     */
    public function setHelper($objHelper)
    {
        $this->objHelper = $objHelper;

        return $this;
    }

    /**
     * Get the ClassCaller.
     *
     * @return ClassCaller
     */
    public function getClassCaller()
    {
        return $this->objClassCaller;
    }

    /**
     * Set the helper.
     *
     * @param  ClassCaller $objClassCaller
     *
     * @return IEnvironment
     */
    public function setClassCaller($objClassCaller)
    {
        $this->objClassCaller = $objClassCaller;

        return $this;
    }
}