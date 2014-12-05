<?php
/**
 * mysqlcaching
 *
 * Resolve creating db tables
 *
 * @package mysqlcaching
 * @subpackage build
 */
if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
            $modx =& $object->xpdo;
            $modelPath = $modx->getOption('mysqlcaching.core_path',null,$modx->getOption('core_path').'components/mysqlcaching/').'model/';
            $modx->addPackage('mysqlcaching',$modelPath);

            $manager = $modx->getManager();

            /* Model Classes names */
            $objects = array(
                'Cache'
            );

            foreach($objects as $object) {
	            $modx->log(MODX::LOG_LEVEL_INFO, 'Adding DB table');
                $manager->createObjectContainer($object);
            }

            break;
        case xPDOTransport::ACTION_UPGRADE:
            break;
    }
}
return true;
