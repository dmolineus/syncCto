<?php

/**
 * Contao Open Source CMS
 *
 * @copyright  MEN AT WORK 2014
 * @package    syncCto
 * @license    GNU/LGPL
 * @filesource
 */
namespace SyncCto\Contao\Maintenance;

use Exception;
use SyncCto\Core\Environment\IEnvironment;

class Maintenance
{
    /**
     * @var IEnvironment
     */
    protected $objEnvironment = null;

    /**
     * @var Automator
     */
    protected $objAutomater = null;

    /**
     * Init the environment.
     *
     * @param IEnvironment $objEnvironment
     */
    public function __construct($objEnvironment)
    {
        $this->objEnvironment = $objEnvironment;
        $this->objAutomater   = new Automator($objEnvironment);
    }

    /**
     * Use the contao maintenance
     *
     * @CtoCommunication Enable
     *
     * @param $arrSettings
     *
     * @return array
     */
    public function runMaintenance($arrSettings)
    {
        $arrReturn = array(
            "success"  => false,
            "info_msg" => array()
        );

        foreach ( $arrSettings as $value )
        {
            try
            {
                switch ( $value )
                {
                    // Tables
                    case "temp_tables":
                        foreach ( $GLOBALS['TL_PURGE']['tables'] as $key => $config )
                        {
                            $arrCallback = $config['callback'];
                            if ( is_array($arrCallback) && count($arrCallback) == 2 )
                            {
                                $this->objEnvironment->getClassCaller()->callClassAsArray($arrCallback);
                            }
                        }
                        break;

                    // Folders
                    case "temp_folders":
                        foreach ( $GLOBALS['TL_PURGE']['folders'] as $key => $config )
                        {
                            $arrCallback = $config['callback'];
                            if ( is_array($arrCallback) && count($arrCallback) == 2 )
                            {
                                $this->objEnvironment->getClassCaller()->callClassAsArray($arrCallback);
                            }
                        }

                        // Rebuild the internal cache.
                        $this->objAutomater->createInternalCache();

                        break;

                    // Custom
                    case "xml_create":
                        foreach ( $GLOBALS['TL_PURGE']['custom'] as $key => $config )
                        {
                            $arrCallback = $config['callback'];
                            if ( is_array($arrCallback) && count($arrCallback) == 2 )
                            {
                                $this->objEnvironment->getClassCaller()->callClassAsArray($arrCallback);
                            }
                        }
                        break;
                }
            }
            catch ( Exception $exc )
            {
                $arrReturn["info_msg"][] = "Error by: $value with Msg: " . $exc->getMessage();
            }
        }

        // HOOK: take additional maintenance
        if ( $this->objEnvironment->getSyncCtoConfig()->isHookSet('syncAdditionalMaintenance') )
        {
            foreach ( $this->objEnvironment->getSyncCtoConfig()->getCallbacksForHook('syncAdditionalMaintenance') as $callback )
            {
                try
                {
                    $this->objEnvironment->getClassCaller()->callClassAsArray($callback, $arrSettings);
                }
                catch ( Exception $exc )
                {
                    $arrReturn["info_msg"][] = "Error by: TL_HOOK $callback[0] | $callback[1] with Msg: " . $exc->getMessage();
                }
            }
        }

        if ( count($arrReturn["info_msg"]) != 0 )
        {
            return $arrReturn;
        }
        else
        {
            return true;
        }
    }

    /**
     * Clear temp folder or a folder inside of temp
     *
     * @param string $strFolder
     */
    public function purgeTemp($strFolder = null)
    {
        if ( $strFolder == null || $strFolder == "" )
        {
            $strPath = $this->objEnvironment->getHelper()->standardizePath($GLOBALS['SYC_PATH']['tmp']);
        }
        else
        {
            $strPath = $this->objEnvironment->getHelper()->standardizePath($GLOBALS['SYC_PATH']['tmp'], $strFolder);
        }

        $objFolder = new \Folder($strPath);
        $objFolder->purge();
    }
} 