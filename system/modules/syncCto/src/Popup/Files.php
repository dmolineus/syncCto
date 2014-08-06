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
use SyncCto\Enum;

/**
 * Class SyncCtoPopupFiles
 */
class SyncCtoPopupFiles extends Base
{
    /**
     * The id of the client.
     *
     * @var int
     */
    protected $intClientID;

    /**
     * The current step to running.
     *
     * @var string
     */
    protected $mixStep;

    /**
     * A list with all files.
     *
     * @var array
     */
    protected $arrListFile;

    /**
     * A list for the compare files.
     *
     * @var array
     */
    protected $arrListCompare;

    // defines

    /**
     * Step Mode: Normal
     */
    const STEP_SHOW_FILES = 'Sf';

    /**
     * Step Mode: Close
     */
    const STEP_CLOSE_FILES = 'cl';

    /**
     * Step Mode: Error
     */
    const STEP_ERROR_FILES = 'er';

    /**
     * Initialize the object
     */
    public function __construct()
    {
        parent::__construct();

        // Load information
        $this->loadClientInformation($this->intClientID);

        // Setup the parent vars.
        $this->setTemplate('be_syncCto_files');
        $this->setPopupTemplate('be_syncCto_popup');
    }

    /**
     * Initialize get parameter
     */
    protected function initGetParams()
    {
        // Get Client id
        if (strlen(\Input::get("id")) != 0)
        {
            $this->intClientID = intval(\Input::get("id"));
        }
        else
        {
            $this->mixStep = self::STEP_ERROR_FILES;
            return;
        }

        // Get next step
        if (strlen(\Input::get("step")) != 0)
        {
            $this->mixStep = \Input::get("step");
        }
        else
        {
            $this->mixStep = self::STEP_SHOW_FILES;
        }
    }

    /**
     * Load the template list and go through the steps
     */
    public function run()
    {
        if ($this->mixStep == self::STEP_SHOW_FILES)
        {
            $this->loadTempLists();
            $this->showFiles();
            $this->saveTempLists();

            unset($_POST);
        }

        if ($this->mixStep == self::STEP_CLOSE_FILES)
        {
            $this->showClose();
        }

        if ($this->mixStep == self::STEP_ERROR_FILES)
        {
            $this->showError();
        }

        // Output template
        $strHeadline     = basename(utf8_convert_encoding($this->strFile, $GLOBALS['TL_CONFIG']['characterSet']));
        $arrTemplateData = array
        (
            'id'   => $this->intClientID,
            'step' => $this->mixStep
        );

        $this->output($strHeadline, $arrTemplateData);
    }

    protected function showFiles()
    {
        // Delete functionality
        if (array_key_exists("delete", $_POST))
        {
            foreach ($_POST as $key => $value)
            {
                if (isset($this->arrListCompare['core'][$value]))
                {
                    unset($this->arrListCompare['core'][$value]);
                }
                if (isset($this->arrListCompare['files'][$value]))
                {
                    unset($this->arrListCompare['files'][$value]);
                }
            }
        }
        // Close functionality
        else
        {
            if (array_key_exists("transfer", $_POST))
            {
                $this->mixStep = self::STEP_CLOSE_FILES;
                return;
            }
        }

        // Check if filelist is empty and close
        if (count($this->arrListCompare['core']) == 0 && count($this->arrListCompare['files']) == 0)
        {
            $this->mixStep = self::STEP_CLOSE_FILES;
            return;
        }

        // Counter
        $intCountMissing       = 0;
        $intCountNeed          = 0;
        $intCountIgnored       = 0;
        $intCountDelete        = 0;
        $intCountDbafsConflict = 0;

        $intTotalSizeNew    = 0;
        $intTotalSizeDel    = 0;
        $intTotalSizeChange = 0;

        // Lists
        $arrNormalFiles = array();
        $arrBigFiles    = array();

        // Build list
        foreach ($this->arrListCompare as $strType => $arrLists)
        {
            foreach ($arrLists as $key => $value)
            {
                switch ($value['state'])
                {
                    case Enum::FILESTATE_TOO_BIG_MISSING:
                    case Enum::FILESTATE_MISSING:
                        $intCountMissing++;
                        $intTotalSizeNew += $value["size"];
                        break;

                    case Enum::FILESTATE_TOO_BIG_NEED:
                    case Enum::FILESTATE_NEED:
                        $intCountNeed++;
                        $intTotalSizeChange += $value["size"];
                        break;

                    case Enum::FILESTATE_TOO_BIG_DELETE :
                    case Enum::FILESTATE_DELETE:
                    case Enum::FILESTATE_FOLDER_DELETE:
                        $intCountDelete++;
                        $intTotalSizeDel += $value["size"];
                        break;

                    case Enum::FILESTATE_BOMBASTIC_BIG:
                        $intCountIgnored++;
                        break;
                }

                // Check for dbafs conflict.
                if ($value["state"] == Enum::FILESTATE_DBAFS_CONFLICT || isset($value["dbafs_state"]) || isset($value["dbafs_tail_state"]))
                {
                    $intCountDbafsConflict++;
                    $value['dbafs_conflict'] = true;
                }

                if (in_array($value["state"],
                    array(
                        Enum::FILESTATE_TOO_BIG_DELETE,
                        Enum::FILESTATE_TOO_BIG_MISSING,
                        Enum::FILESTATE_TOO_BIG_NEED,
                        Enum::FILESTATE_TOO_BIG_SAME,
                        Enum::FILESTATE_BOMBASTIC_BIG
                    )
                )
                )
                {
                    $arrBigFiles[$key] = $value;
                }
                else
                {
                    if ($value["split"] == 1)
                    {
                        $arrBigFiles[$key] = $value;
                    }
                    elseif ($value["size"] > $this->arrClientInformation["upload_sizeLimit"])
                    {
                        $arrBigFiles[$key] = $value;
                    }
                    else
                    {
                        $arrNormalFiles[$key] = $value;
                    }
                }
            }
        }

        uasort($arrBigFiles, array($this, 'sort'));
        uasort($arrNormalFiles, array($this, 'sort'));

        // Language array for filestate
        $arrLanguageTags                                  = array();
        $arrLanguageTags[Enum::FILESTATE_MISSING]         = $GLOBALS['TL_LANG']['MSC']['create'];
        $arrLanguageTags[Enum::FILESTATE_NEED]            = $GLOBALS['TL_LANG']['MSC']['overrideSelected'];
        $arrLanguageTags[Enum::FILESTATE_DELETE]          = $GLOBALS['TL_LANG']['MSC']['delete'];
        $arrLanguageTags[Enum::FILESTATE_FOLDER_DELETE]   = $GLOBALS['TL_LANG']['MSC']['delete'];
        $arrLanguageTags[Enum::FILESTATE_TOO_BIG_MISSING] = $GLOBALS['TL_LANG']['MSC']['skipped'];
        $arrLanguageTags[Enum::FILESTATE_TOO_BIG_NEED]    = $GLOBALS['TL_LANG']['MSC']['skipped'];
        $arrLanguageTags[Enum::FILESTATE_TOO_BIG_DELETE]  = $GLOBALS['TL_LANG']['MSC']['skipped'];
        $arrLanguageTags[Enum::FILESTATE_BOMBASTIC_BIG]   = $GLOBALS['TL_LANG']['MSC']['ignored'];
        $arrLanguageTags[Enum::FILESTATE_DBAFS_CONFLICT]  = $GLOBALS['TL_LANG']['MSC']['dbafs_conflict'];

        // Set template
        $this->objTemplate                  = new BackendTemplate('be_syncCto_files');
        $this->objTemplate->maxLength       = 55;
        $this->objTemplate->arrLangStates   = $arrLanguageTags;
        $this->objTemplate->normalFilelist  = $arrNormalFiles;
        $this->objTemplate->bigFilelist     = $arrBigFiles;
        $this->objTemplate->totalsizeNew    = $intTotalSizeNew;
        $this->objTemplate->totalsizeDel    = $intTotalSizeDel;
        $this->objTemplate->totalsizeChange = $intTotalSizeChange;
        $this->objTemplate->compare_complex = false;
        $this->objTemplate->close           = false;
        $this->objTemplate->error           = false;
    }

    /**
     * Load temporary filelist
     */
    protected function loadTempLists()
    {
        $objFileList = new File($this->objSyncCtoHelper->standardizePath($GLOBALS['SYC_PATH']['tmp'], "syncfilelist-ID-" . $this->intClientID . ".txt"));
        $strContent  = $objFileList->getContent();
        if (strlen($strContent) == 0)
        {
            $this->arrListFile = array();
        }
        else
        {
            $this->arrListFile = unserialize($strContent);
        }
        $objFileList->close();

        $objCompareList = new File($this->objSyncCtoHelper->standardizePath($GLOBALS['SYC_PATH']['tmp'], "synccomparelist-ID-" . $this->intClientID . ".txt"));
        $strContent     = $objCompareList->getContent();
        if (strlen($strContent) == 0)
        {
            $this->arrListCompare = array();
        }
        else
        {
            $this->arrListCompare = unserialize($strContent);
        }

        $objCompareList->close();
    }

    /**
     * Save temporary filelist
     */
    protected function saveTempLists()
    {
        $objFileList = new File($this->objSyncCtoHelper->standardizePath($GLOBALS['SYC_PATH']['tmp'], "syncfilelist-ID-" . $this->intClientID . ".txt"));
        $objFileList->write(serialize($this->arrListFile));
        $objFileList->close();

        $objCompareList = new File($this->objSyncCtoHelper->standardizePath($GLOBALS['SYC_PATH']['tmp'], "synccomparelist-ID-" . $this->intClientID . ".txt"));
        $objCompareList->write(serialize($this->arrListCompare));
        $objCompareList->close();
    }

    /**
     * Sort function
     *
     * @param array $a
     *
     * @param array $b
     *
     * @return int
     */
    public function sort($a, $b)
    {
        if ($a["state"] == $b["state"])
        {
            return 0;
        }

        return ($a["state"] < $b["state"]) ? -1 : 1;
    }

}

/**
 * Instantiate controller
 */
$objPopup = new SyncCtoPopupFiles();
$objPopup->run();
