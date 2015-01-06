<?php
/**
 * adido
 *
 * adido build script
 *
 * @package adido
 * @subpackage build
 */
$mtime = microtime();
$mtime = explode(' ', $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

/* define package */
define('PKG_NAME', 'mysqlCaching');
define('PKG_ABBR', strtolower(PKG_NAME));
define('PKG_VERSION', '1.0.4');
define('PKG_RELEASE', 'pl');

/* define sources */
$root = dirname(dirname(__FILE__)).'/';
define('MODX_ROOT', $root);
$sources = array(
    'root' => $root,
    'build' => $root . '_build/',
    'data' => $root . '_build/data/',
    'events' => $root . '_build/events/',
    'resolvers' => $root . '_build/resolvers/',
    'plugins' => $root.'core/components/'.PKG_ABBR.'/elements/plugins/',
    'lexicon' => $root . 'core/components/'.PKG_ABBR.'/lexicon/',
    'docs' => $root.'core/components/'.PKG_ABBR.'/docs/',
    'chunks' => $root.'core/components/'.PKG_ABBR.'/elements/chunks/',
    'pages' => $root.'core/components/'.PKG_ABBR.'/elements/pages/',
    'source_assets' => $root.'assets/components/'.PKG_ABBR,
    'source_core' => $root.'core/components/'.PKG_ABBR,
    'source_cache' => $root.'core/xpdo/cache/',
);
unset($root);

/* override with your own defines here (see build.config.sample.php) */
require_once $sources['build'] . '/build.config.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
require_once $sources['build'] . '/includes/functions.php';

$modx= new modX();
$modx->initialize('mgr');
echo '<pre>'; /* used for nice formatting of log messages */
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');

$modx->loadClass('transport.modPackageBuilder','',false, true);
$builder = new modPackageBuilder($modx);
$builder->createPackage(PKG_ABBR,PKG_VERSION,PKG_RELEASE);
$builder->registerNamespace(PKG_ABBR,false,true,'{core_path}components/'.PKG_ABBR.'/');
$modx->log(modX::LOG_LEVEL_INFO,'Created Transport Package and Namespace.');

/* create category */
$category= $modx->newObject('modCategory');
$category->set('id',1);
$category->set('category',PKG_NAME);

/* create category vehicle */
$attr = array(
    xPDOTransport::UNIQUE_KEY => 'category',
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::RELATED_OBJECTS => true
);

$vehicle = $builder->createVehicle($category,$attr);

$vehicle->resolve('file',array(
    'source' => $sources['source_core'],
    'target' => "return MODX_CORE_PATH . 'components/';",
));

$vehicle->resolve('php',array(
	'source' => $sources['resolvers'] . 'resolve.tables.php',
));
$modx->log(modX::LOG_LEVEL_INFO,'Packaged in menu.');

$vehicle->resolve('php',array(
	'source' => $sources['resolvers'] . 'resolve.cache.php',
));
$modx->log(modX::LOG_LEVEL_INFO,'Packaged in cache.');

$builder->putVehicle($vehicle);

/* now pack in the license file, readme and setup options */
$builder->setPackageAttributes(array(
    'license' => file_get_contents($sources['docs'] . 'license.txt'),
    'readme' => file_get_contents($sources['docs'] . 'readme.txt'),
    'changelog' => file_get_contents($sources['docs'] . 'changelog.txt'),
    //'setup-options' => array(
        //'source' => $sources['build'].'setup.options.php',
    //),
));
$modx->log(modX::LOG_LEVEL_INFO,'Added package attributes and setup options.');

/* zip up package */
$modx->log(modX::LOG_LEVEL_INFO,'Packing up transport package zip...');
$builder->pack();

$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

$modx->log(modX::LOG_LEVEL_INFO,"\n<br />Package Built (".MODX_BASE_PATH.").<br />\nExecution time: {$totalTime}\n");

exit ();
