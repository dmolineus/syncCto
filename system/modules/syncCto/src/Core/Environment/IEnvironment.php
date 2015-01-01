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

interface IEnvironment
{
    /**
     * Get the Database from the System.
     *
     * @return \Database
     */
    public function getSystemDatabase();

    /**
     * Set the database.
     *
     * @param \Database $objDatabase
     *
     * @return IEnvironment
     */
    public function setSystemDatabase($objDatabase);

    /**
     * Return the the container with all settings for syncCto.
     *
     * @return Config
     */
    public function getSyncCtoConfig();

    /**
     * Set the config container.
     *
     * @param Config $objConfig
     *
     * @return IEnvironment
     */
    public function setSyncCtoConfig($objConfig);

    /**
     * Get the helper.
     *
     * @return Helper
     */
    public function getHelper();

    /**
     * Set the helper.
     *
     * @param  Helper $objHelper
     *
     * @return IEnvironment
     */
    public function setHelper($objHelper);

    /**
     * Get the ClassCaller.
     *
     * @return ClassCaller
     */
    public function getClassCaller();

    /**
     * Set the helper.
     *
     * @param  ClassCaller $objClassCaller
     *
     * @return IEnvironment
     */
    public function setClassCaller($objClassCaller);
} 