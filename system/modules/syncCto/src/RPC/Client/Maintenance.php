<?php

/**
 * Contao Open Source CMS
 *
 * @copyright  MEN AT WORK 2014
 * @package    syncCto
 * @license    GNU/LGPL
 * @filesource
 */

namespace SyncCto\RPC;


use SyncCto\Contao\Maintenance\Automator;
use SyncCto\Contao\Maintenance\Maintenance;
use SyncCto\Core\Environment\IEnvironment;
use SyncCto\Core\FactoryEnvironment;

class Client
{
    /**
     * @var IEnvironment
     */
    protected $objEnviroment = null;

    /**
     * Init the environment.
     */
    public function __construct()
    {
        $this->objEnviroment = FactoryEnvironment::getEnvironment();
    }

    /**
     * Call the automater class and purge the internal cache.
     */
    public function automatorPurgeInternalCache()
    {
        $objAutomater = new Automator($this->objEnviroment);

        return $objAutomater->purgeInternalCache();
    }

    /**
     * Call the automater class and create the internal cache.
     */
    public function automatorCreateInternalCache()
    {
        $objAutomater = new Automator($this->objEnviroment);

        return $objAutomater->createInternalCache();
    }

    /**
     * Call the maintenace.
     */
    public function maintenance($arrSettings)
    {
        $objMaintenance = new Maintenance($this->objEnviroment);

        return $objMaintenance->runMaintenance($arrSettings);
    }

    /**
     * Call the maintenace.
     */
    public function maintenancePurgeTemp()
    {
        $objMaintenance = new Maintenance($this->objEnviroment);

        return $objMaintenance->purgeTemp();
    }
}
