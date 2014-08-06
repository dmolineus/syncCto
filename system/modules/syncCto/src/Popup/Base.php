<?php

/**
 * Contao Open Source CMS
 *
 * @copyright  MEN AT WORK 2013
 * @package    syncCto
 * @license    GNU/LGPL
 * @filesource
 */

namespace SyncCto\Popup;

/**
 * Class Base for popups.
 *
 * @package SyncCto\Popup
 */
abstract class Base
{
    /**
     * Name of the template.
     *
     * @var string
     */
    protected $strTemplate = '';

    /**
     * Name of the template.
     *
     * @var string
     */
    protected $strPopupTemplate = '';

    /**
     * Template object.
     *
     * @var \BackendTemplate
     */
    protected $objTemplate;

    /**
     * Template object.
     *
     * @var \BackendTemplate
     */
    protected $objPopupTemplate;

    /**
     * The helper class we need always.
     *
     * @var \SyncCtoHelper
     */
    protected $objSyncCtoHelper;

    /**
     * A list with all settings.
     *
     * @var array
     */
    protected $arrSyncSettings;

    /**
     * Array with information from the client.
     *
     * @var array
     */
    protected $arrClientInformation;

    /**
     * Array with all error.
     *
     * @var array
     */
    protected $arrErrors = array();

    /**
     * Initialize the object
     */
    public function __construct()
    {
        // Auth the user.
        \BackendUser::getInstance()->authenticate();

        // Set language from get or user
        if (\Input::get('language') != '')
        {
            $GLOBALS['TL_LANGUAGE'] = \Input::get('language');
        }
        else
        {
            $GLOBALS['TL_LANGUAGE'] = \BackendUser::getInstance()->language;
        }

        // Load language files.
        \System::loadLanguageFile('default');

        // Init some basic vars.
        $this->objSyncCtoHelper = \SyncCtoHelper::getInstance();

        // Call the function to init the get params.
        $this->initGetParams();
    }

    /**
     * @param string $strTemplate
     */
    public function setTemplate($strTemplate)
    {
        $this->strTemplate = $strTemplate;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->strTemplate;
    }

    /**
     * @param string $strPopupTemplate
     */
    public function setPopupTemplate($strPopupTemplate)
    {
        $this->strPopupTemplate = $strPopupTemplate;
    }

    /**
     * @return string
     */
    public function getPopupTemplate()
    {
        return $this->strPopupTemplate;
    }

    /**
     * Add a error to the list.
     *
     * @param string $strError
     */
    public function addError($strError)
    {
        $this->arrErrors[] = $strError;
    }

    abstract public function run();

    abstract protected function initGetParams();

    /**
     * Load the SyncSettings back in the session.
     */
    protected function loadSyncSettings($intClientID)
    {
        $this->arrSyncSettings = \Session::getInstance()->get('syncCto_SyncSettings_' . $intClientID);

        if (!is_array($this->arrSyncSettings))
        {
            $this->arrSyncSettings = array();
        }
    }

    /**
     * Save the SyncSettings back in the session.
     */
    protected function saveSyncSettings($intClientID)
    {
        if (!is_array($this->arrSyncSettings))
        {
            $this->arrSyncSettings = array();
        }

        \Session::getInstance()->set('syncCto_SyncSettings_' . $intClientID, $this->arrSyncSettings);
    }

    /**
     * Load information from client
     */
    protected function loadClientInformation($intClientID)
    {
        $this->arrClientInformation = \Session::getInstance()->get('syncCto_ClientInformation_' . $intClientID);

        if (!is_array($this->arrClientInformation))
        {
            $this->arrClientInformation = array();
        }
    }

    /**
     * Close popup and go through next syncCto step
     */
    public function showClose()
    {
        $this->objTemplate            = new \BackendTemplate($this->strTemplate);
        $this->objTemplate->headline  = $GLOBALS['TL_LANG']['MSC']['backBT'];
        $this->objTemplate->close     = true;
        $this->objTemplate->error     = false;
        $this->objTemplate->errorMsgs = array();
    }

    /**
     * Show errors
     */
    public function showError()
    {
        $this->objTemplate            = new \BackendTemplate($this->strTemplate);
        $this->objTemplate->headline  = $GLOBALS['TL_LANG']['MSC']['error'];
        $this->objTemplate->text      = $GLOBALS['TL_LANG']['ERR']['general'];
        $this->objTemplate->close     = false;
        $this->objTemplate->error     = true;
        $this->objTemplate->errorMsgs = $this->arrErrors;
    }

    /**
     * Output templates
     */
    public function output($strHeadline, $arrTemplateData = array(), $arrPopupData = array())
    {
        // Clear all we want a clear array for this windows.
        $GLOBALS['TL_CSS']        = array();
        $GLOBALS['TL_JAVASCRIPT'] = array();

        // Set stylesheets
        $GLOBALS['TL_CSS'][] = 'system/themes/' . \Backend::getTheme() . '/basic.css';
        $GLOBALS['TL_CSS'][] = 'system/modules/syncCto/assets/css/compare.css';

        // Set javascript
        $GLOBALS['TL_JAVASCRIPT'][] = 'assets/mootools/core/' . MOOTOOLS . '/mootools-core.js';
        $GLOBALS['TL_JAVASCRIPT'][] = 'assets/mootools/core/' . MOOTOOLS . '/mootools-more.js';
        $GLOBALS['TL_JAVASCRIPT'][] = 'assets/mootools/mootao/Mootao.js';
        $GLOBALS['TL_JAVASCRIPT'][] = 'assets/contao/js/core.js';
        $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/syncCto/assets/js/compare.js';
        $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/syncCto/assets/js/htmltable.js';

        // Set wrapper template information
        $this->objPopupTemplate           = new \BackendTemplate($this->strPopupTemplate);
        $this->objPopupTemplate->theme    = \Backend::getTheme();
        $this->objPopupTemplate->base     = \Environment::get('base');
        $this->objPopupTemplate->language = $GLOBALS['TL_LANGUAGE'];
        $this->objPopupTemplate->title    = $GLOBALS['TL_CONFIG']['websiteTitle'];
        $this->objPopupTemplate->charset  = $GLOBALS['TL_CONFIG']['characterSet'];
        $this->objPopupTemplate->headline = $strHeadline;

        // Add template data.
        if (!empty($arrTemplateData))
        {
            $this->objTemplate->setData($arrTemplateData);
        }

        // Add template data.
        if (!empty($arrPopupData))
        {
            $this->objPopupTemplate->setData($arrPopupData);
        }

        // Output template
        $this->objPopupTemplate->content = $this->objTemplate->parse();
        $this->objPopupTemplate->output();
    }

} 