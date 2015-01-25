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

use SyncCto\Contao\API\Core;
use SyncCto\Contao\API\Input;
use SyncCto\Core\ClassCaller;
use SyncCto\Core\Config;
use SyncCto\Core\Helper;

class Environment implements IEnvironment
{
    /**
     * @var IEnvironmentBuilder
     */
    protected $objBuilder;

    /**
     * @var \Database
     */
    protected $objDatabase;

    /**
     * @var Core
     */
    protected $objContaoCoreApi;

    /**
     * @var Input
     */
    protected $objInput;

    /**
     * @var Config
     */
    protected $objConfig;

    /**
     * @var Config
     */
    protected $objHelper;

    /**
     * @var ClassCaller
     */
    protected $objClassCaller;

    /**
     * @param IEnvironmentBuilder $objBuilder
     *
     * @return IEnvironment
     */
    public function setBuilder($objBuilder)
    {
        $this->objBuilder = $objBuilder;

        return $this;
    }

    /**
     * Get the API for the System.
     *
     * @return Core
     */
    public function getContaoApi()
    {
        return $this->objContaoCoreApi;
    }

    /**
     * Set the API for the System.
     *
     * @return IEnvironment
     */
    public function setContaoApi($objContaoCoreApi)
    {
        $this->objContaoCoreApi = $objContaoCoreApi;

        return $this;
    }

    /**
     * Get the API for the System.
     *
     * @return Input
     */
    public function getInput()
    {
        return $this->objInput;
    }

    /**
     * Set the API for the System.
     *
     * @return IEnvironment
     */
    public function setInput($objInput)
    {
        $this->objInput = $objInput;

        return $this;
    }

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