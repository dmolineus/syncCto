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

class Config
{
    /**
     * @var IEnvironment
     */
    protected $objEnvironment = null;

    /**
     * @param IEnvironment $objEnvironment
     */
    public function __construct($objEnvironment)
    {
        $this->objEnvironment = $objEnvironment;
    }

    /**
     * Get a list with all setting for the database auto update.
     *
     * @return array
     */
    public function getSettingDatabaseUpdate()
    {
        return deserialize($GLOBALS['TL_CONFIG']['syncCto_auto_db_updater'], true);
    }

    /**
     * Check if is a hook set.
     *
     * @param string $strHookName Name of the hook.
     *
     * @return bool True => Something is set | False => No data found.
     */
    public function isHookSet($strHookName)
    {
        if ( isset($GLOBALS['TL_HOOKS'][$strHookName]) && is_array($GLOBALS['TL_HOOKS'][$strHookName]) )
        {
            return true;
        }

        return false;
    }

    /**
     * Get the callback array.
     *
     * @param string $strHookName Name of the hook.
     *
     * @return array A list with the callbacks.
     */
    public function getCallbacksForHook($strHookName)
    {
        return $GLOBALS['TL_HOOKS'][$strHookName];
    }
} 