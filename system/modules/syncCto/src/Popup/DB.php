<?php

/**
 * Contao Open Source CMS
 *
 * @copyright  MEN AT WORK 2013
 * @package    syncCto
 * @license    GNU/LGPL
 * @filesource
 */

/**
 * Initialize the system
 */
$dir = dirname(isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : __FILE__);

while ($dir && $dir != '.' && $dir != '/' && !is_file($dir . '/system/initialize.php'))
{
    $dir = dirname($dir);
}

if (!is_file($dir . '/system/initialize.php'))
{
    header("HTTP/1.0 500 Internal Server Error");
    header('Content-Type: text/html; charset=utf-8');
    echo '<h1>500 Internal Server Error</h1>';
    echo '<p>Could not find initialize.php!</p>';
    exit(1);
}

define('TL_MODE', 'BE');
require($dir . '/system/initialize.php');

use SyncCto\Popup\Base;

/**
 * Class SyncCtoPopup
 */
class SyncCtoPopupDB extends Base
{
    /**
     * The id of the client.
     *
     * @var int
     */
    protected $intClientID;

    /**
     * The mode syncTo or syncFrom.
     *
     * @var string
     */
    protected $strMode;

    /**
     * The current step to running.
     *
     * @var string
     */
    protected $mixStep;

    /**
     * Step Mode: Normal
     */
    const STEP_NORMAL_DB = 'nd';

    /**
     * Step Mode: Close
     */
    const STEP_CLOSE_DB = 'cl';

    /**
     * Step Mode: Error
     */
    const STEP_ERROR_DB = 'er';

    /**
     * Initialize the object
     */
    public function __construct()
    {
        parent::__construct();

        // Load language
        \System::loadLanguageFile('modules');
        \System::loadLanguageFile('tl_syncCto_database');

        // Setup the parent vars.
        $this->setTemplate('be_syncCto_database');
        $this->setPopupTemplate('be_syncCto_popup');
    }

    /**
     * Initialize get parameter
     */
    protected function initGetParams()
    {
        // Get Client id
        if (strlen(\Input::get('id')) != 0)
        {
            $this->intClientID = intval(\Input::get('id'));
        }
        else
        {
            $this->mixStep = self::STEP_ERROR_DB;
            return;
        }

        // Get next step
        if (strlen(\Input::get('step')) != 0)
        {
            $this->mixStep = \Input::get('step');
        }
        else
        {
            $this->mixStep = self::STEP_NORMAL_DB;
        }

        // Get direction
        if (strlen(\Input::get('direction')) != 0)
        {
            $this->strMode = \Input::get('direction');
        }
    }

    /**
     * Load the template list and go through the steps
     */
    public function run()
    {
        // Run the steps.
        if ($this->mixStep == self::STEP_NORMAL_DB)
        {
            // Set client for communication
            try
            {
                $this->loadSyncSettings($this->intClientID);
                $this->showNormalDatabase();
                $this->saveSyncSettings($this->intClientID);

                unset($_POST);
            }
            catch (Exception $exc)
            {
                $this->addError($exc->getMessage());
                $this->mixStep = self::STEP_ERROR_DB;
            }
        }
        else if ($this->mixStep == self::STEP_CLOSE_DB)
        {
            $this->showClose();
        }
        else if ($this->mixStep == self::STEP_ERROR_DB)
        {
            $this->showError();
        }

        // Output template
        $strHeadline     = basename(utf8_convert_encoding($this->strFile, $GLOBALS['TL_CONFIG']['characterSet']));
        $arrTemplateData = array
        (
            'id'        => $this->intClientID,
            'step'      => $this->mixStep,
            'direction' => $this->strMode,
        );

        $this->output($strHeadline, $arrTemplateData);
    }

    /**
     * Show database server and client compare list
     */
    public function showNormalDatabase()
    {
        // Delete functionality.
        if (array_key_exists("delete", $_POST))
        {
            // Make a array from 'serverTables' and 'serverDeleteTables'
            $arrRemoveTables = array();

            if (is_array(\Input::post('serverTables')) && count(\Input::post('serverTables')) != 0)
            {
                $arrRemoveTables = \Input::post('serverTables');
            }

            if (is_array(\Input::post('serverDeleteTables')) && count(\Input::post('serverDeleteTables')) != 0)
            {
                $arrRemoveTables = array_merge($arrRemoveTables, \Input::post('serverDeleteTables'));
            }

            // Remove tables from the list.
            foreach ($arrRemoveTables as $value)
            {
                if (isset($this->arrSyncSettings['syncCto_CompareTables']['recommended']) && array_key_exists($value, $this->arrSyncSettings['syncCto_CompareTables']['recommended']))
                {
                    unset($this->arrSyncSettings['syncCto_CompareTables']['recommended'][$value]);
                }
                else
                {
                    if (isset($this->arrSyncSettings['syncCto_CompareTables']['nonRecommended']) && array_key_exists($value, $this->arrSyncSettings['syncCto_CompareTables']['nonRecommended']))
                    {
                        unset($this->arrSyncSettings['syncCto_CompareTables']['nonRecommended'][$value]);
                    }
                }
            }
        }
        // Close functionality.
        else
        {
            if (array_key_exists("transfer", $_POST))
            {
                foreach ($this->arrSyncSettings['syncCto_CompareTables'] as $arrType)
                {
                    foreach ($arrType as $keyTable => $valueTable)
                    {
                        if ($valueTable['del'] == true)
                        {
                            $this->arrSyncSettings['syncCto_SyncDeleteTables'][] = $keyTable;
                        }
                        else
                        {
                            $this->arrSyncSettings['syncCto_SyncTables'][] = $keyTable;
                        }
                    }
                }

                unset($this->arrSyncSettings['syncCto_CompareTables']);

                $this->mixStep = self::STEP_CLOSE_DB;
                return;
            }
        }

        // If no table is found skip the view
        if (count($this->arrSyncSettings['syncCto_CompareTables']['recommended']) == 0
            && count($this->arrSyncSettings['syncCto_CompareTables']['nonRecommended']) == 0
        )
        {
            unset($this->arrSyncSettings['syncCto_CompareTables']);
            $this->arrSyncSettings['syncCto_SyncDeleteTables'] = array();
            $this->arrSyncSettings['syncCto_SyncTables']       = array();

            $this->mixStep = self::STEP_CLOSE_DB;
            return;
        }

        // Make a look up
        foreach ((array)$this->arrSyncSettings['syncCto_CompareTables']['recommended'] as $strKey => $arrValueA)
        {
            $arrTransServer = $this->lookUpName($arrValueA['server']['name']);
            $arrTransClient = $this->lookUpName($arrValueA['client']['name']);

            $this->arrSyncSettings['syncCto_CompareTables']['recommended'][$strKey]['server']['tname'] = $arrTransServer['tname'];
            $this->arrSyncSettings['syncCto_CompareTables']['recommended'][$strKey]['server']['iname'] = $arrTransServer['iname'];
            $this->arrSyncSettings['syncCto_CompareTables']['recommended'][$strKey]['client']['tname'] = $arrTransClient['tname'];
            $this->arrSyncSettings['syncCto_CompareTables']['recommended'][$strKey]['client']['iname'] = $arrTransClient['iname'];
        }

        foreach ((array)$this->arrSyncSettings['syncCto_CompareTables']['nonRecommended'] as $strKey => $arrValueA)
        {
            $arrTransServer = $this->lookUpName($arrValueA['server']['name']);
            $arrTransClient = $this->lookUpName($arrValueA['client']['name']);

            $this->arrSyncSettings['syncCto_CompareTables']['nonRecommended'][$strKey]['server']['tname'] = $arrTransServer['tname'];
            $this->arrSyncSettings['syncCto_CompareTables']['nonRecommended'][$strKey]['server']['iname'] = $arrTransServer['iname'];
            $this->arrSyncSettings['syncCto_CompareTables']['nonRecommended'][$strKey]['client']['tname'] = $arrTransClient['tname'];
            $this->arrSyncSettings['syncCto_CompareTables']['nonRecommended'][$strKey]['client']['iname'] = $arrTransClient['iname'];
        }

        $this->objTemplate                 = new BackendTemplate("be_syncCto_database");
        $this->objTemplate->headline       = $GLOBALS['TL_LANG']['MSC']['comparelist'];
        $this->objTemplate->arrCompareList = $this->arrSyncSettings['syncCto_CompareTables'];
        $this->objTemplate->close          = false;
        $this->objTemplate->error          = false;

        $objExtern = \Database::getInstance()
            ->prepare('SELECT address, path FROM tl_synccto_clients WHERE id=?')
            ->execute($this->intClientID);

        $this->objTemplate->clientPath = $objExtern->address . $objExtern->path;
        $this->objTemplate->serverPath = \Environment::get('base');
    }

    /**
     * Make a lookup for a human readable table name.
     * First syncCto language
     * Second the mapping for mod language
     * Last the mod language
     *
     * @param string $strName Name of table
     *
     * @return string
     */
    public function lookUpName($strName)
    {
        $strBase = str_replace('tl_', "", $strName);

        // If empty return a array.
        if ($strName == '-')
        {
            return array(
                'tname' => '-',
                'iname' => '-'
            );
        }

        // Make a lookup in syncCto language files
        if (is_array($GLOBALS['TL_LANG']['tl_syncCto_database']) && array_key_exists($strName, $GLOBALS['TL_LANG']['tl_syncCto_database']))
        {
            if (is_array($GLOBALS['TL_LANG']['tl_syncCto_database'][$strName]))
            {
                return $this->formateLookUpName($strName, $GLOBALS['TL_LANG']['tl_syncCto_database'][$strName][0]);
            }
            else
            {
                return $this->formateLookUpName($strName, $GLOBALS['TL_LANG']['tl_syncCto_database'][$strName]);
            }
        }

        // Get MM name
        if (in_array('metamodels', \Config::getInstance()->getActiveModules()) && preg_match("/^mm_/i", $strName))
        {
            try
            {
                if (!is_null(\MetaModels\Factory::byTableName($strName)))
                {
                    $objDCABuilder     = \MetaModels\Dca\MetaModelDcaBuilder::getInstance();
                    $objMetaModels     = \MetaModels\Factory::byTableName($strName);
                    $arrDCA            = $objDCABuilder->getDca($objMetaModels->get('id'));
                    $arrBackendcaption = deserialize($arrDCA['backendcaption']);

                    $strReturn = $objMetaModels->getName();

                    foreach ((array)$arrBackendcaption as $value)
                    {
                        if ($value['langcode'] == \BackendUser::getInstance()->language)
                        {
                            $strReturn = $value['label'];
                            break;
                        }
                    }

                    return $this->formateLookUpName($strName, $strReturn);
                }
            }
            catch (Exception $exc)
            {
                // Nothing to do;
            }
        }

        // Little mapping for names        
        if (is_array($GLOBALS['SYC_CONFIG']['database_mapping']) && array_key_exists($strName, $GLOBALS['SYC_CONFIG']['database_mapping']))
        {
            $strRealSystemName = $GLOBALS['SYC_CONFIG']['database_mapping'][$strName];

            if (is_array($GLOBALS['TL_LANG']['MOD'][$strRealSystemName]))
            {
                return $this->formateLookUpName($strName, $GLOBALS['TL_LANG']['MOD'][$strRealSystemName][0]);
            }
            else
            {
                return $this->formateLookUpName($strName, $GLOBALS['TL_LANG']['MOD'][$strRealSystemName]);
            }
        }

        // Search in mod language array for a translation
        if (array_key_exists($strBase, $GLOBALS['TL_LANG']['MOD']))
        {
            if (is_array($GLOBALS['TL_LANG']['MOD'][$strBase]))
            {
                return $this->formateLookUpName($strName, $GLOBALS['TL_LANG']['MOD'][$strBase][0]);
            }
            else
            {
                return $this->formateLookUpName($strName, $GLOBALS['TL_LANG']['MOD'][$strBase]);
            }
        }

        return $this->formateLookUpName($strName, $strName);
    }

    /**
     * Return a array with tahble names
     *
     * @param string $strTableName    real name like 'tl_contetn'
     * @param string $strReadableName readable name like 'Conten Elements'
     *
     * @return array('tname' => [for table], 'iname' => [for title/info])
     */
    protected function formateLookUpName($strTableName, $strReadableName)
    {
        // Check if the function is activate
        if (\BackendUser::getInstance()->syncCto_useTranslatedNames)
        {
            return array(
                'tname' => $strReadableName,
                'iname' => $strTableName
            );
        }
        else
        {
            return array(
                'tname' => $strTableName,
                'iname' => $strReadableName
            );
        }
    }
}

/**
 * Instantiate controller
 */
$objPopup = new SyncCtoPopupDB();
$objPopup->run();