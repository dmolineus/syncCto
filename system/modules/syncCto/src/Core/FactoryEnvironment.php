<?php

/**
 * Contao Open Source CMS
 *
 * @copyright  MEN AT WORK 2014
 * @package    syncCto
 * @license    GNU/LGPL
 * @filesource
 */

namespace SyncCto\Core;


use SyncCto\Core\Environment\IEnvironment;

class FactoryEnvironment
{
    /**
     * @var IEnvironment|null
     */
    protected static $objEnvironment = null;

    /**
     * Init the environment and return it.
     *
     * @return IEnvironment
     */
    public static function getEnvironment()
    {
        if ( self::$objEnvironment == null )
        {
            self::initEnvironment();
        }

        return self::$objEnvironment;
    }

    /**
     * Call a extenr class for the build function.
     *
     * @return mixed
     */
    protected static function initEnvironment()
    {
        $strClass = $GLOBALS['SYC_CONFIG']['DefaultBuilder'];

        if ( !in_array('SyncCto\Core\Environment\IEnvironmentBuilder', class_implements($strClass)) )
        {
            throw new \RuntimeException(sprintf('The given builder (%s) for SyncCto is not from type %s', $strClass, 'SyncCto\Core\Environment\IEnvironmentBuilder'));
        }

        self::$objEnvironment = $strClass::buildEnvironment();
    }
} 