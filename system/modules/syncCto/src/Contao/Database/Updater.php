<?php

/**
 * Contao Open Source CMS
 *
 * @copyright  MEN AT WORK 2014
 * @package    syncCto
 * @license    GNU/LGPL
 * @filesource
 */

namespace SyncCto\Contao\Database;

use Exception;
use SyncCto\Core\Environment\IEnvironment;

class Updater extends \Database\Installer
{

    /**
     * The env. with all objects.
     *
     * @var IEnvironment
     */
    protected $objEnvironment;

    /**
     * The current database,
     *
     * @var \Database
     */
    protected $objDatabase;

    /**
     * List with allowed update actions.
     *
     * @var array
     */
    protected $arrAllowedAction;

    /**
     * List with errors
     *
     * @var array
     */
    protected $arrError = array();

    /**
     * @param IEnvironment $objEnvironment The environment.
     */
    public function __construct($objEnvironment)
    {
        parent::__construct();

        $this->objEnvironment   = $objEnvironment;
        $this->objDatabase      = $this->objEnvironment->getSystemDatabase();
        $this->arrAllowedAction = $this->objEnvironment->getSyncCtoConfig()->getSettingDatabaseUpdate();
    }

    /**
     * Run auto update
     *
     * @throws Exception
     *
     * @return boolean Return true on success or when there are no data.
     */
    public function runAutoUpdate()
    {
        $sql_command = $this->compileCommands();

        if ( empty($sql_command) )
        {
            return true;
        }

        // Remove not allowed actions
        foreach ( $sql_command as $strAction => $strOperation )
        {
            if ( !in_array($strAction, $this->arrAllowedAction) )
            {
                unset($sql_command[$strAction]);
            }
        }

        if ( empty($sql_command) )
        {
            return true;
        }

        // Execute all
        foreach ( $this->arrAllowedAction as $strAction )
        {
            if ( !isset($sql_command[$strAction]) )
            {
                continue;
            }

            foreach ( $sql_command[$strAction] as $strOperation )
            {
                try
                {
                    $this->objDatabase->query($strOperation);
                }
                catch ( Exception $exc )
                {
                    $this->arrError[$strAction][] = array(
                        'operation' => $strOperation,
                        'error'     => $exc->getMessage(),
                        'trace'     => $exc->getTraceAsString()
                    );
                }
            }
        }

        if ( empty($this->arrError) )
        {
            return true;
        }
        else
        {
            $strError = '';

            foreach ( $this->arrError as $key => $value )
            {
                $strError .= sprintf("%i. %s. | ", $key + 1, $value['error']);
            }

            throw new Exception('There was an error on updating the database: ' . $strError);
        }
    }

}
