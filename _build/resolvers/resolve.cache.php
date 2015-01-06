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
if ($modx) {
	$newPath = MODX_CORE_PATH.'/xpdo/cache/xpdomysqlcache.class.php';
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modelPath = $modx->getOption('mysqlcaching.core_path',null,MODX_CORE_PATH.'components/mysqlcaching/').'model/';
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
