<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 04.01.2015
 * Time: 17:44
 */

namespace SyncCto\Contao\API;


class Input
{
    /**
     * @var \Session
     */
    protected $objSession;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->objSession = \Session::getInstance();
    }

    /**
     * Return a $_GET variable
     *
     * @param string  $strKey            The variable name
     * @param boolean $blnDecodeEntities If true, all entities will be decoded
     * @param boolean $blnKeepUnused     If true, the parameter will not be marked as used (see #4277)
     *
     * @return mixed The cleaned variable value
     */
    public function get($strKey, $blnDecodeEntities = false, $blnKeepUnused = false)
    {
        return \Input::get($strKey, $blnDecodeEntities, $blnKeepUnused);
    }

    /**
     * Return a $_POST variable
     *
     * @param string  $strKey            The variable name
     * @param boolean $blnDecodeEntities If true, all entities will be decoded
     *
     * @return mixed The cleaned variable value
     */
    public function post($strKey, $blnDecodeEntities = false)
    {
        return \Input::post($strKey, $blnDecodeEntities);
    }


    /**
     * Return a session variable
     *
     * @param string $strKey The variable name
     *
     * @return mixed The variable value
     */
    public function getSession($strKey)
    {
        return $this->objSession->get($strKey);
    }

    /**
     * Set a session variable
     *
     * @param string $strKey   The variable name
     * @param mixed  $varValue The variable value
     */
    public function setSession($strKey, $varValue)
    {
        $this->objSession->set($strKey, $varValue);
    }
}