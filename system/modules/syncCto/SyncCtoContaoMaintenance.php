<?php

/**
 * Contao Open Source CMS
 *
 * @copyright  MEN AT WORK 2014
 * @package    syncCto
 * @license    GNU/LGPL
 * @filesource
 */
class SyncCtoContaoMaintenance
{

    protected $arrClassLoader = array();
    protected $objAutomater = null;

    public function __construct()
    {
        $this->objAutomater = new SyncCtoContaoAutomator();
    }

    /**
     * Try to init the class;
     *
     * @param $strName
     *
     * @return null
     */
    protected function getClass($strName)
    {
        if (!class_exists($strName))
        {
            return null;
        }

        if (!isset($this->arrClassLoader[$strName]))
        {
            if (method_exists($strName, 'getInstance'))
            {
                $this->arrClassLoader[$strName] = $strName::getInstance();
            }
            else
            {
                $this->arrClassLoader[$strName] = new $strName();
            }
        }

        return $this->arrClassLoader[$strName];
    }

    /**
     *  Call the class and function.
     *
     * @param string $strName     Name of the class.
     *
     * @param string $strFunction Name of the function to be called.
     *
     * @return null/mixed Null on error or the result of the function.
     */
    protected function callClass($strName, $strFunction)
    {
        // Get the object.
        $objClass = $this->getClass($strName);

        // Check if we have one.
        if ($objClass == null)
        {
            return null;
        }

        // Call call call.
        return $objClass->$strFunction();
    }

    /**
     * Call the class and function. Used a array for callback.
     *
     * @param array $arrCallback The Callback array.
     *
     * @return null/mixed Null on error or the result of the function.
     */
    protected function callClassAsArray($arrCallback)
    {
        return $this->callClass($arrCallback[0], $arrCallback[1]);
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

        foreach ($arrSettings as $value)
        {
            try
            {
                switch ($value)
                {
                    // Tables
                    case "temp_tables":
                        foreach ($GLOBALS['TL_PURGE']['tables'] as $key => $config)
                        {
                            $arrCallback = $config['callback'];
                            if (is_array($arrCallback) && count($arrCallback) == 2)
                            {
                                $this->callClassAsArray($arrCallback);
                            }
                        }
                        break;

                    // Folders
                    case "temp_folders":
                        foreach ($GLOBALS['TL_PURGE']['folders'] as $key => $config)
                        {
                            $arrCallback = $config['callback'];
                            if (is_array($arrCallback) && count($arrCallback) == 2)
                            {
                                $this->callClassAsArray($arrCallback);
                            }
                        }
                        break;

                    // Custom
                    case "xml_create":
                        foreach ($GLOBALS['TL_PURGE']['custom'] as $key => $config)
                        {
                            $arrCallback = $config['callback'];
                            if (is_array($arrCallback) && count($arrCallback) == 2)
                            {
                                $this->callClassAsArray($arrCallback);
                            }
                        }
                        break;
                }
            }
            catch (Exception $exc)
            {
                $arrReturn["info_msg"][] = "Error by: $value with Msg: " . $exc->getMessage();
            }
        }

        // HOOK: take additional maintenance
        if (isset($GLOBALS['TL_HOOKS']['syncAdditionalMaintenance']) && is_array($GLOBALS['TL_HOOKS']['syncAdditionalMaintenance']))
        {
            foreach ($GLOBALS['TL_HOOKS']['syncAdditionalMaintenance'] as $callback)
            {
                try
                {
                    $this->import($callback[0]);
                    $this->$callback[0]->$callback[1]($arrSettings);
                }
                catch (Exception $exc)
                {
                    $arrReturn["info_msg"][] = "Error by: TL_HOOK $callback[0] | $callback[1] with Msg: " . $exc->getMessage();
                }
            }
        }

        if (count($arrReturn["info_msg"]) != 0)
        {
            return $arrReturn;
        }
        else
        {
            return true;
        }
    }


} 