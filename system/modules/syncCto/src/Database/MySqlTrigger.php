<?php

/**
 * Contao Open Source CMS
 *
 * @copyright  MEN AT WORK 2014
 * @package    syncCto
 * @license    GNU/LGPL
 * @filesource
 */

namespace SyncCto\Database;

use SyncCto\Core\Environment\IEnvironment;

class MySqlTrigger
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
     * @param IEnvironment $objEnvironment The environment.
     */
    public function __construct($objEnvironment)
    {
        $this->objEnvironment = $objEnvironment;
        $this->objDatabase    = $this->objEnvironment->getSystemDatabase();
    }

    /**
     * Drop triggers for table XXX.
     *
     * @param string $strTable
     */
    public function dropTrigger($strTable)
    {
        // Drop Update.
        $strQuery = "DROP TRIGGER IF EXISTS `" . $strTable . "_AfterUpdateHashRefresh`";
        $this->objDatabase->query($strQuery);

        // Drop Insert.
        $strQuery = "DROP TRIGGER IF EXISTS `" . $strTable . "_AfterInsertHashRefresh`";
        $this->objDatabase->query($strQuery);

        // Drop Delete.
        $strQuery = "DROP TRIGGER IF EXISTS `" . $strTable . "_AfterDeleteHashRefresh`";
        $this->objDatabase->query($strQuery);
    }

    /**
     * Check if a trigger is set on one of the tables.
     *
     * @param string $strTable Name of table.
     *
     * @return boolean True = we have some triggers | False = no trigger are set.
     */
    public function hasTrigger($strTable)
    {
        $arrTriggers = $this->objDatabase
            ->query('SHOW TRIGGERS')
            ->fetchEach('Trigger');

        if ( in_array($strTable . "_AfterUpdateHashRefresh", $arrTriggers) )
        {
            return true;
        }

        if ( in_array($strTable . "_AfterInsertHashRefresh", $arrTriggers) )
        {
            return true;
        }

        if ( in_array($strTable . "_AfterDeleteHashRefresh", $arrTriggers) )
        {
            return true;
        }

        return false;
    }
} 