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

use SyncCto\Core\Environment\IEnvironment;
use SyncCto\Core\ExecuteHooks;
use SyncCto\Core\FactoryEnvironment;

class Hooks
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
     * Execute some operations at last step
     */
    public function executeFinalOperations()
    {
        $objHooks = new ExecuteHooks($this->objEnvironment);

        return $objHooks->executeFinalOperations();
    }
}
