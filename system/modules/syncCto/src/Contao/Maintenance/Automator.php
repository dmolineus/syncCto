<?php

/**
 * Contao Open Source CMS
 *
 * @copyright  MEN AT WORK 2014
 * @package    syncCto
 * @license    GNU/LGPL
 * @filesource
 */

namespace SyncCto\Contao\Maintenance;

use SyncCto\Core\Environment\IEnvironment;

class Automator extends \Automator
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
        parent::__construct();

        $this->objEnvironment = $objEnvironment;
    }

    /**
     * Build the internal cache
     */
    public function generateInternalCache()
    {
        if ( $GLOBALS['TL_CONFIG']['bypassCache'] )
        {
            return;
        }

        // Purge
        $this->purgeInternalCache();

        // Rebuild
        $this->generateConfigCache();
        $this->generateDcaCache();
        $this->generateLanguageCache();
        $this->generateDcaExtracts();
    }

    /**
     * Purge the internal cache
     */
    public function purgeInternalCache()
    {
        if ( $GLOBALS['TL_CONFIG']['bypassCache'] )
        {
            return;
        }

        parent::purgeInternalCache();
    }

    /**
     * Build the internal cache
     */
    public function createInternalCache()
    {
        if ( $GLOBALS['TL_CONFIG']['bypassCache'] )
        {
            return;
        }

        // Rebuild
        $this->generateConfigCache();
        $this->generateDcaCache();
        $this->generateLanguageCache();
        $this->generateDcaExtracts();
    }
} 