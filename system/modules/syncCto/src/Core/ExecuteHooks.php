<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 16.11.2014
 * Time: 15:14
 */

namespace SyncCto\Core;


use SyncCto\Contao\API;
use SyncCto\Core\Environment\IEnvironment;

class ExecuteHooks
{
    /**
     * @var IEnvironment
     */
    protected $objEnvironment;

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
     * Execute some operations at last step
     */
    public function executeFinalOperations()
    {
        $arrReturn = array();

        $objClassCaller = $this->objEnvironment->getClassCaller();
        $objConfig      = $this->objEnvironment->getSyncCtoConfig();

        if ( $objConfig->isHookSet('syncExecuteFinalOperations') )
        {
            foreach ( $objConfig->getCallbacksForHook('syncExecuteFinalOperations') as $callback )
            {
                try
                {
                    API::addLog(sprintf('Start executing TL_HOOK %s | %s', $callback[0], $callback[1]), __CLASS__ . "::" . __FUNCTION__, TL_GENERAL);

                    $objClassCaller->callClassAsArray($callback);

                    API::addLog(sprintf('Finished executing TL_HOOK %s | %s', $callback[0], $callback[1]), __CLASS__ . "::" . __FUNCTION__, TL_GENERAL);
                }
                catch ( \Exception $exc )
                {
                    $strError = sprintf('Error by: TL_HOOK %s::%s with Msg: %s', $callback[0], $callback[1], $exc->getMessage());

                    $arrReturn [] = array(
                        'callback' => implode('|', $callback),
                        'info_msg' => $strError
                    );

                    API::addLog($strError, __CLASS__ . "::" . __FUNCTION__, TL_ERROR);
                }
            }
        }

        return $arrReturn;
    }
} 