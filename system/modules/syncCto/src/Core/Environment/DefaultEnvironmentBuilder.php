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

class DefaultEnvironmentBuilder implements IEnvironmentBuilder
{

    public static function buildEnvironment()
    {
        $objEnvironment = new Environment();
        $objEnvironment
            ->setSystemDatabase(\Database::getInstance())
            ->setSyncCtoConfig(new Config($objEnvironment))
            ->setHelper(new Helper($objEnvironment))
            ->setClassCaller(new ClassCaller($objEnvironment));

        return $objEnvironment;
    }
}