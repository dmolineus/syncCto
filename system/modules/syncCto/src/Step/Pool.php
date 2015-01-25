<?php

/**
 * Contao Open Source CMS
 *
 * @copyright  MEN AT WORK 2014
 * @package    syncCto
 * @license    GNU/LGPL
 * @filesource
 */

namespace SyncCto\Step;

use SyncCto\Contao\API\Input;
use SyncCto\Core\Environment\IEnvironment;

/**
 * Class for step information
 */
class Pool
{
    /**
     * @var array
     */
    protected $arrValues;

    /**
     * @var int
     */
    protected $intStepId;

    /**
     * @var int
     */
    protected $intClientId;

    /**
     * @var IEnvironment
     */
    protected $objEnvironment;

    /**
     * @var Input
     */
    protected $objInput;

    /**
     * Construct.
     *
     * @param IEnvironment $objEnvironment
     */
    public function __construct($objEnvironment)
    {
        // Load some helper.
        $this->objEnvironment = $objEnvironment;
        $this->objInput       = $objEnvironment->getInput();

        // Init some values.
        $this->arrValues = array();
    }

    /**
     * Get the full session name.
     *
     * @param int $intClientId A client ID
     *
     * @return string The current full session name.
     */
    protected function getSessionName($intClientId = null)
    {
        if ( $intClientId == null )
        {
            return sprintf('syncCto_%s_StepPool%s', $this->intClientId, $this->intStepId);
        }
        else
        {
            return sprintf('syncCto_%s_StepPool%s', $intClientId, $this->intStepId);
        }
    }

    /**
     * Load the step from the session.
     *
     * @param int $intClientID The ID of the client.
     *
     * @param int $intStep     The ID of the current step.
     */
    public function loadStepPool($intClientID, $intStep)
    {
        // Set all vars.
        $this->intStepId   = $intStep;
        $this->intClientId = $intClientID;

        // Get the step pool.
        $arrStepPool = $this->objInput->getSession($this->getSessionName());
        $arrStepPool = (empty($arrStepPool)) ? array() : $arrStepPool;

        // Set the current values.
        $this->arrValues = $arrStepPool;
    }

    /**
     * Save all current data from the step back in the session.
     */
    public function saveStepPool()
    {
        $this->objInput->setSession($this->getSessionName(), $this->arrValues);
    }

    /**
     * Reset the current step pool.
     */
    public function resetStepPool()
    {
        $this->objInput->setSession($this->getSessionName(), false);
        $this->arrValues = array();
    }

    /**
     * Reset the session data.
     *
     * @param array $arrID List with IDs
     */
    public function resetStepPoolByID($arrID)
    {
        foreach ( $arrID as $value )
        {
            $this->objInput->setSession($this->getSessionName($value), false);
        }
    }

    /**
     * Return a list with all values.
     *
     * @return array
     */
    public function getArrValues()
    {
        return $this->arrValues;
    }

    /**
     * Overwrite all data in the array.
     *
     * @param $arrValues
     */
    public function setArrValues($arrValues)
    {
        $this->arrValues = $arrValues;
    }

    /**
     * Get the current step.
     *
     * @return int
     */
    public function getIntStepID()
    {
        return $this->intStepId;
    }

    /**
     * Set the current step.
     *
     * @param int $intStepId The current step.
     */
    public function setIntStepID($intStepId)
    {
        $this->intStepId = $intStepId;
    }

    /**
     * Get data from the array.
     *
     * @param string $name The name of the param.
     *
     * @return mixed The value.
     */
    public function __get($name)
    {
        // Check if we have data.
        if ( empty($this->arrValues) )
        {
            return null;
        }

        // Check if we know the data.
        if ( array_key_exists($name, $this->arrValues) )
        {
            return $this->arrValues[$name];
        }
        else
        {
            return null;
        }
    }

    /**
     * @param string $name  Name of the param.
     *
     * @param mixed  $value The values.
     *
     * @return mixed
     */
    public function __set($name, $value)
    {
        return $this->arrValues[$name] = $value;
    }

}