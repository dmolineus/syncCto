<?php

/**
 * Contao Open Source CMS
 *
 * @copyright  MEN AT WORK 2014
 * @package    syncCto
 * @license    GNU/LGPL
 * @filesource
 */

namespace SyncCto\RPC\Client;


use SyncCto\Contao\Maintenance\Automator;
use SyncCto\Contao\Maintenance\Maintenance as ContaoMaintenance;
use SyncCto\Core\Environment\IEnvironment;
use SyncCto\Core\FactoryEnvironment;

class Maintenance
{
    /**
     * @var IEnvironment
     */
    protected $objEnvironment = null;

    /**
     * Init the environment.
     */
    public function __construct()
    {
        $this->objEnvironment = FactoryEnvironment::getEnvironment();
    }

    /**
     * Call the automater class and purge the internal cache.
     */
    public function automatorPurgeInternalCache()
    {
        $objAutomater = new Automator($this->objEnvironment);

        return $objAutomater->purgeInternalCache();
    }

    /**
     * Call the automater class and create the internal cache.
     */
    public function automatorCreateInternalCache()
    {
        $objAutomater = new Automator($this->objEnvironment);

        return $objAutomater->createInternalCache();
    }

    /**
     * Call the maintenace.
     */
    public function maintenance($arrSettings)
    {
        $objMaintenance = new ContaoMaintenance($this->objEnvironment);

        return $objMaintenance->runMaintenance($arrSettings);
    }

    /**
     * Call the maintenace.
     */
    public function maintenancePurgeTemp()
    {
        $objMaintenance = new ContaoMaintenance($this->objEnvironment);

        return $objMaintenance->purgeTemp();
    }
}
