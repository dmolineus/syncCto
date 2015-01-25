<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @package SyncCto
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'SyncCto',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'SyncCtoUpdater'                                     => 'system/modules/syncCto/SyncCtoUpdater.php',
	'SyncCtoModuleCheck'                                 => 'system/modules/syncCto/SyncCtoModuleCheck.php',
	'SyncCtoTableSyncTo'                                 => 'system/modules/syncCto/SyncCtoTableSyncTo.php',
	'InterfaceSyncCtoStep'                               => 'system/modules/syncCto/InterfaceSyncCtoStep.php',
	'SyncCtoPopupFiles'                                  => 'system/modules/syncCto/SyncCtoPopupFiles.php',
	'SyncCtoTableSyncFrom'                               => 'system/modules/syncCto/SyncCtoTableSyncFrom.php',
	'SyncCtoDatabase'                                    => 'system/modules/syncCto/SyncCtoDatabase.php',
	'SyncCtoRunOnceEr'                                   => 'system/modules/syncCto/SyncCtoRunOnceEr.php',
	'SyncCtoPopupDB'                                     => 'system/modules/syncCto/SyncCtoPopupDB.php',
	'SyncCtoCommunicationClient'                         => 'system/modules/syncCto/SyncCtoCommunicationClient.php',
	'SyncCtoModuleBackup'                                => 'system/modules/syncCto/SyncCtoModuleBackup.php',
	'GeneralDataSyncCto'                                 => 'system/modules/syncCto/GeneralDataSyncCto.php',
	'SyncCtoHelper'                                      => 'system/modules/syncCto/SyncCtoHelper.php',
	// Src
	'SyncCto\Step\Pool'                                  => 'system/modules/syncCto/src/Step/Pool.php',
	'SyncCto\Core\ClassCaller'                           => 'system/modules/syncCto/src/Core/ClassCaller.php',
	'SyncCto\Core\Helper'                                => 'system/modules/syncCto/src/Core/Helper.php',
	'SyncCto\Core\Environment\Environment'               => 'system/modules/syncCto/src/Core/Environment/Environment.php',
	'SyncCto\Core\Environment\DefaultEnvironmentBuilder' => 'system/modules/syncCto/src/Core/Environment/DefaultEnvironmentBuilder.php',
	'SyncCto\Core\Environment\IEnvironmentBuilder'       => 'system/modules/syncCto/src/Core/Environment/IEnvironmentBuilder.php',
	'SyncCto\Core\Environment\IEnvironment'              => 'system/modules/syncCto/src/Core/Environment/IEnvironment.php',
	'SyncCto\Core\Config'                                => 'system/modules/syncCto/src/Core/Config.php',
	'SyncCto\Core\FactoryEnvironment'                    => 'system/modules/syncCto/src/Core/FactoryEnvironment.php',
	'SyncCto\Core\ExecuteHooks'                          => 'system/modules/syncCto/src/Core/ExecuteHooks.php',
	'SyncCto\Database\MySqlTrigger'                      => 'system/modules/syncCto/src/Database/MySqlTrigger.php',
	'SyncCto\Contao\API\Core'                            => 'system/modules/syncCto/src/Contao/API/Core.php',
	'SyncCto\Contao\API\Input'                           => 'system/modules/syncCto/src/Contao/API/Input.php',
	'SyncCto\Contao\Database\Updater'                    => 'system/modules/syncCto/src/Contao/Database/Updater.php',
	'SyncCto\Contao\Maintenance\Automator'               => 'system/modules/syncCto/src/Contao/Maintenance/Automator.php',
	'SyncCto\Contao\Maintenance\Maintenance'             => 'system/modules/syncCto/src/Contao/Maintenance/Maintenance.php',
	'SyncCto\Contao\ExecuteHooks'                        => 'system/modules/syncCto/src/Contao/ExecuteHooks.php',
	'SyncCto\RPC\Client\Hooks'                           => 'system/modules/syncCto/src/RPC/Client/Hooks.php',
	'SyncCto\RPC\Client\Maintenance'                     => 'system/modules/syncCto/src/RPC/Client/Maintenance.php',
	'SyncCtoFilterIteratorFiles'                         => 'system/modules/syncCto/SyncCtoFilterIteratorFiles.php',
	'SyncCtoEnum'                                        => 'system/modules/syncCto/SyncCtoEnum.php',
	'SyncCtoStats'                                       => 'system/modules/syncCto/SyncCtoStats.php',
	'SyncCtoFilterIteratorBase'                          => 'system/modules/syncCto/SyncCtoFilterIteratorBase.php',
	'SyncCtoRPCFunctions'                                => 'system/modules/syncCto/SyncCtoRPCFunctions.php',
	'ContentData'                                        => 'system/modules/syncCto/ContentData.php',
	'SyncCtoTableSettings'                               => 'system/modules/syncCto/SyncCtoTableSettings.php',
	'SyncCtoModuleClient'                                => 'system/modules/syncCto/SyncCtoModuleClient.php',
	'SyncCtoFiles'                                       => 'system/modules/syncCto/SyncCtoFiles.php',
	'SyncCtoFilterIteratorFolder'                        => 'system/modules/syncCto/SyncCtoFilterIteratorFolder.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'be_syncCto_error'         => 'system/modules/syncCto/templates',
	'be_syncCto_backup'        => 'system/modules/syncCto/templates',
	'be_syncCto_popup_summary' => 'system/modules/syncCto/templates',
	'be_syncCto_attention'     => 'system/modules/syncCto/templates',
	'be_syncCto_legend'        => 'system/modules/syncCto/templates',
	'be_syncCto_form'          => 'system/modules/syncCto/templates',
	'be_syncCto_smallCheck'    => 'system/modules/syncCto/templates',
	'be_syncCto_check'         => 'system/modules/syncCto/templates',
	'be_syncCto_popup'         => 'system/modules/syncCto/templates',
	'be_syncCto_database'      => 'system/modules/syncCto/templates',
	'be_syncCto_files'         => 'system/modules/syncCto/templates',
	'be_syncCto_steps'         => 'system/modules/syncCto/templates',
));
