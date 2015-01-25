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

use SyncCto\Core\ClassCaller;
use SyncCto\Core\Config;
use SyncCto\Core\Helper;
use SyncCto\Contao\API\Core as CoreApi;
use SyncCto\Contao\API\Input;

class DefaultEnvironmentBuilder implements IEnvironmentBuilder
{
    /**
     * Construct
     */
    public function __construct()
    {

    }

    /**
     * @return Environment
     */
    public function buildEnvironment()
    {
        $objEnvironment = new Environment();
        $objEnvironment
            ->setBuilder($this)
            ->setContaoApi(new CoreApi())
            ->setInput(new Input())
            ->setSystemDatabase(\Database::getInstance())
            ->setSyncCtoConfig(new Config($objEnvironment))
            ->setHelper(new Helper($objEnvironment))
            ->setClassCaller(new ClassCaller($objEnvironment));

        return $objEnvironment;
    }
}