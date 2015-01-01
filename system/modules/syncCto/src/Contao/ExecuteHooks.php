<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 16.11.2014
 * Time: 15:14
 */

namespace SyncCto\Contao;


use SyncCto\Contao\Database\Updater;
use SyncCto\Core\FactoryEnvironment;

class ExecuteHooks
{
    /**
     * Init the Env if not done till now and call the Database updater.
     *
     * @throws \Exception
     */
    public function executeDatabaseUpdate()
    {
        $objEnv             = FactoryEnvironment::getEnvironment();
        $objDatabaseUpdater = new Updater($objEnv);

        return $objDatabaseUpdater->runAutoUpdate();
    }
} 