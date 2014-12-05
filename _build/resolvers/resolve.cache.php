<?php
/**
 * mysqlcaching
 *
 * Resolve creating db tables
 *
 * @package mysqlcaching
 * @subpackage build
 */
$modx =& $object->xpdo;
$modx->log(MODX::LOG_LEVEL_INFO, 'Move cache class from ');
if ($object->xpdo) {
	$newPath = $modx->getOption('core_path').'/xpdo/cache/xpdomysqlcache.class.php';
	$modx->log(MODX::LOG_LEVEL_INFO, 'Move cache class from '.$newPath);
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modelPath = $modx->getOption('mysqlcaching.core_path',null,$modx->getOption('core_path').'components/mysqlcaching/').'model/';
			$oldPath = $modelPath.'xpdomysqlcache.class.php';
		    $modx->log(MODX::LOG_LEVEL_INFO, 'Move cache class from ('.$oldPath.') to ('.$newPath.')');
		    if(!rename($oldPath, $newPath)) {
				$modx->log(MODX::LOG_LEVEL_ERROR, 'Could not move cache class from ('.$oldPath.') to ('.$newPath.')');
			}

            break;
        case xPDOTransport::ACTION_UNINSTALL:
			unlink($newPath);
            break;
    }
}
return true;
