<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 01.01.2015
 * Time: 21:27
 */

namespace SyncCto;


use SyncCto\Core\Environment\IEnvironment;

class ClassCaller
{
    /**
     * @var null|IEnvironment
     */
    protected $objEnvironment = null;

    /**
     * Init the environment and call the parent.
     *
     * @param $objEnvironment
     */
    public function __construct($objEnvironment)
    {
        $this->objEnvironment = $objEnvironment;
    }

    /**
     * Try to init the class;
     *
     * @param $strName
     *
     * @return null
     */
    public function getClass($strName)
    {
        if ( !class_exists($strName) )
        {
            return null;
        }

        if ( method_exists($strName, 'getInstance') )
        {
            return $strName::getInstance();
        }
        else
        {
            return new $strName();
        }
    }

    /**
     *  Call the class and function.
     *
     * @param string     $strName     Name of the class.
     *
     * @param string     $strFunction Name of the function to be called.
     *
     * @param null|array $arrParams   A list with parameters.
     *
     * @return null/mixed Null on error or the result of the function.
     */
    public function callFunction($strName, $strFunction, $arrParams = null)
    {
        // Get the object.
        $objClass = $this->getClass($strName);

        // Check if we have one.
        if ( $objClass == null )
        {
            throw new \RuntimeException('Could not find the class ' . $strName);
        }

        // Call call call.
        if ( $arrParams == null )
        {
            return call_user_func(array($objClass, $strFunction));
        }
        else
        {
            return call_user_func_array(array($objClass, $strFunction), $arrParams);
        }
    }

    /**
     * Call the class and function. Used a array for callback.
     *
     * @param array      $arrCallback The Callback array.
     *
     * @param null|array $arrParams   A list with parameters.
     *
     * @return null/mixed Null on error or the result of the function.
     */
    public function callClassAsArray($arrCallback, $arrParams = null)
    {
        return $this->callFunction($arrCallback[0], $arrCallback[1], $arrParams);
    }

} 