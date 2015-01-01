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

class Helper
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
     * Parse size
     *
     * @see http://us2.php.net/manual/en/function.ini-get.php#example-501
     *
     * @param string $size
     *
     * @return int|string
     */
    public static function parseSize($size)
    {
        $size = trim($size);
        $last = strtolower($size[strlen($size) - 1]);

        switch ( $last )
        {
            // The 'G' modifier is available since PHP 5.1.0
            case 'g':
                $size *= 1024;
            case 'm':
                $size *= 1024;
            case 'k':
                $size *= 1024;

            default:
                break;
        }

        return $size;
    }

    /**
     * Shorten a string to a certain number of characters
     *
     * Shortens a string to a given number of characters preserving words
     * (therefore it might be a bit shorter or longer than the number of
     * characters specified). Stips all tags.
     *
     * @param string
     * @param integer
     * @param string
     *
     * @return string
     */
    public static function substrCenter($strString, $intNumberOfChars, $strEllipsis = ' […] ')
    {
        $strString = preg_replace('/[\t\n\r]+/', ' ', $strString);
        $strString = strip_tags($strString);

        if ( utf8_strlen($strString) <= $intNumberOfChars )
        {
            return $strString;
        }

        $intCharCount   = 0;
        $arrWords       = array();
        $arrChunks      = preg_split('/\s+/', $strString);
        $blnAddEllipsis = false;

        //first part
        foreach ( $arrChunks as $chunkKey => $strChunk )
        {
            $intCharCount += utf8_strlen(\String::decodeEntities($strChunk));

            if ( $intCharCount++ <= $intNumberOfChars / 2 )
            {
                // if we add the whole word remove it from list
                unset($arrChunks[$chunkKey]);

                $arrWords[] = $strChunk;
                continue;
            }

            // If the first word is longer than $intNumberOfChars already, shorten it
            // with utf8_substr() so the method does not return an empty string.
            if ( empty($arrWords) )
            {
                $arrWords[] = utf8_substr($strChunk, 0, $intNumberOfChars / 2);
            }

            if ( $strEllipsis !== false )
            {
                $blnAddEllipsis = true;
            }

            break;
        }

        // Backwards compatibility
        if ( $strEllipsis === true )
        {
            $strEllipsis = ' […] ';
        }

        $intCharCount = 0;
        $arrWordsPt2  = array();

        // Second path
        foreach ( array_reverse($arrChunks) as $strChunk )
        {
            $intCharCount += utf8_strlen(\String::decodeEntities($strChunk));

            if ( $intCharCount++ <= $intNumberOfChars / 2 )
            {
                $arrWordsPt2[] = $strChunk;
                continue;
            }

            // If the first word is longer than $intNumberOfChars already, shorten it
            // with utf8_substr() so the method does not return an empty string.
            if ( empty($arrWordsPt2) )
            {
                $arrWordsPt2[] = utf8_substr($strChunk, utf8_strlen($strChunk) - ($intNumberOfChars / 2), utf8_strlen($strChunk));
            }
            break;
        }

        return implode(' ', $arrWords) . ($blnAddEllipsis ? $strEllipsis : '') . implode(' ', array_reverse($arrWordsPt2));
    }

    /**
     * Standardize path for folder
     * No TL_ROOT, No starting /
     *
     * @return string the normalized path
     */
    public static function standardizePath()
    {
        $arrPath = func_get_args();

        if ( empty($arrPath) )
        {
            return '';
        }

        $arrReturn = array();

        foreach ( $arrPath as $itPath )
        {
            $itPath = str_replace('\\', '/', $itPath);
            $itPath = preg_replace('?^' . str_replace('\\', '\\\\', TL_ROOT) . '?i', '', $itPath);
            $itPath = explode('/', $itPath);

            foreach ( $itPath as $itFolder )
            {
                if ( $itFolder === '' || $itFolder === null || $itFolder == "." || $itFolder == ".." )
                {
                    continue;
                }

                $arrReturn[] = $itFolder;
            }
        }

        return implode('/', $arrReturn);
    }
} 