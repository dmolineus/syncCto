<?php

/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 04.01.2015
 * Time: 00:28
 */

namespace SyncCto\Contao\API;


class Core
{
    /**
     * Add a log entry to the database
     *
     * @param string $strText     The log message
     * @param string $strFunction The function name
     * @param string $strCategory The category name
     */
    public static function addLog($strText, $strFunction, $strCategory)
    {
        \System::log($strText, $strFunction, $strCategory);
    }

    /**
     * Add an error message
     *
     * @param string $strMessage The error message
     */
    public static function addErrorMessage($strMessage)
    {
        \Message::addError($strMessage);
    }

    /**
     * Add a confirmation message
     *
     * @param string $strMessage The confirmation
     */
    public static function addConfirmationMessage($strMessage)
    {
        \Message::addConfirmation($strMessage);
    }

    /**
     * Add a new message
     *
     * @param string $strMessage The new message
     */
    public static function addNewMessage($strMessage)
    {
        \Message::addNew($strMessage);
    }

    /**
     * Load a set of language files
     *
     * @param string  $strName     The table name
     * @param boolean $strLanguage An optional language code
     * @param boolean $blnNoCache  If true, the cache will be bypassed
     */
    public static function loadLanguageFile($strName, $strLanguage = null, $blnNoCache = false)
    {
        \System::loadLanguageFile($strName, $strLanguage, $blnNoCache);
    }

    /**
     * Redirect to another page
     *
     * @param string  $strLocation The target URL
     * @param integer $intStatus   The HTTP status code (defaults to 303)
     */
    public static function redirect($strLocation, $intStatus = 303)
    {
        \Controller::redirect($strLocation, $intStatus);
    }

    /**
     * Add a CSS to the Contao CSS array.
     *
     * @param string $strPath Path to the css file.
     * @param string $strName Name of the path.
     */
    public static function addCss($strPath, $strName = null)
    {
        // Check uf we know this file.
        if ( !file_exists(TL_ROOT . '/' . $strPath) )
        {
            return;
        }

        // Add files to the array.
        if ( $strName == null )
        {
            $GLOBALS['TL_CSS'][] = $strPath;
        }
        else
        {
            $GLOBALS['TL_CSS'][$strName] = $strPath;
        }
    }
} 