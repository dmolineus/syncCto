<?php

/**
 * Contao Open Source CMS
 *
 * @copyright  MEN AT WORK 2014
 * @package    syncCto
 * @license    GNU/LGPL
 * @filesource
 */

/**
 * Register the classes
 */
ClassLoader::addClasses(array
    (
        // New
        'SyncCto\Filter\Iterator\Base'   => 'system/modules/syncCto/src/Files/Iterator/Base.php',
        'SyncCto\Filter\Iterator\Files'  => 'system/modules/syncCto/src/Files/Iterator/Files.php',
        'SyncCto\Filter\Iterator\Folder' => 'system/modules/syncCto/src/Files/Iterator/Folder.php',
        'SyncCto\Popup\Base'             => 'system/modules/syncCto/src/Popup/Base.php',
        'SyncCto\Popup\DB'               => 'system/modules/syncCto/src/Popup/DB.php',
        'SyncCto\Popup\Files'            => 'system/modules/syncCto/src/Popup/Files.php',
        'SyncCto\Enum'                   => 'system/modules/syncCto/src/Enum.php',
        // Old
        'CronDbBackups'                  => 'system/modules/syncCto/cron/CronDbBackups.php',
        'CronDeleteDbBackups'            => 'system/modules/syncCto/cron/CronDeleteDbBackups.php',
        'CronFileBackups'                => 'system/modules/syncCto/cron/CronFileBackups.php',
        'CronDeleteFileBackups'          => 'system/modules/syncCto/cron/CronDeleteFileBackups.php',
        'SyncCtoAjax'                    => 'system/modules/syncCto/SyncCtoAjax.php',
        'SyncCtoCommunicationClient'     => 'system/modules/syncCto/SyncCtoCommunicationClient.php',
        'SyncCtoDatabase'                => 'system/modules/syncCto/SyncCtoDatabase.php',
        'SyncCtoUpdater'                 => 'system/modules/syncCto/SyncCtoUpdater.php',
        'SyncCtoDatabaseUpdater'         => 'system/modules/syncCto/SyncCtoDatabaseUpdater.php',
        'SyncCtoModuleBackup'            => 'system/modules/syncCto/SyncCtoModuleBackup.php',
        'SyncCtoModuleCheck'             => 'system/modules/syncCto/SyncCtoModuleCheck.php',
        'SyncCtoModuleClient'            => 'system/modules/syncCto/SyncCtoModuleClient.php',
        'InterfaceSyncCtoStep'           => 'system/modules/syncCto/InterfaceSyncCtoStep.php',
        'GeneralDataSyncCto'             => 'system/modules/syncCto/GeneralDataSyncCto.php',
        'SyncCtoHelper'                  => 'system/modules/syncCto/SyncCtoHelper.php',
        'SyncCtoRPCFunctions'            => 'system/modules/syncCto/SyncCtoRPCFunctions.php',
        'SyncCtoStats'                   => 'system/modules/syncCto/SyncCtoStats.php',
        'SyncCtoFiles'                   => 'system/modules/syncCto/SyncCtoFiles.php',
        'SyncCtoRunOnceEr'               => 'system/modules/syncCto/SyncCtoRunOnceEr.php',
        'SyncCtoTableSyncTo'             => 'system/modules/syncCto/SyncCtoTableSyncTo.php',
        'SyncCtoTableSyncFrom'           => 'system/modules/syncCto/SyncCtoTableSyncFrom.php',
        'SyncCtoTableSettings'           => 'system/modules/syncCto/SyncCtoTableSettings.php',
        'StepPool'                       => 'system/modules/syncCto/StepPool.php',
        'ContentData'                    => 'system/modules/syncCto/ContentData.php',
    )
);


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
    (
        'be_syncCto_files'      => 'system/modules/syncCto/templates',
        'be_syncCto_popup'      => 'system/modules/syncCto/templates',
        'be_syncCto_backup'     => 'system/modules/syncCto/templates',
        'be_syncCto_attention'  => 'system/modules/syncCto/templates',
        'be_syncCto_legend'     => 'system/modules/syncCto/templates',
        'be_syncCto_smallCheck' => 'system/modules/syncCto/templates',
        'be_syncCto_form'       => 'system/modules/syncCto/templates',
        'be_syncCto_error'      => 'system/modules/syncCto/templates',
        'be_syncCto_steps'      => 'system/modules/syncCto/templates',
        'be_syncCto_check'      => 'system/modules/syncCto/templates',
        'be_syncCto_database'   => 'system/modules/syncCto/templates',
    )
);
