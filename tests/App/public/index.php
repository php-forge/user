<?php

declare(strict_types=1);

use Yiisoft\Yii\Runner\Http\HttpApplicationRunner;

ini_set('display_errors', "1");
error_reporting(E_ALL);

require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

$c3 = dirname(__DIR__, 3) . '/c3.php';

if (is_file($c3)) {
    require_once $c3;
}

// PHP built-in server routing.
if (PHP_SAPI === 'cli-server') {
    /** @var string */
    $requestUri = $_SERVER['REQUEST_URI'] ?? '';

    if (is_file(__DIR__ . parse_url($requestUri, PHP_URL_PATH))) {
        return false;
    }

    // Explicitly set for URLs with dot.
    $_SERVER['SCRIPT_NAME'] = '/index.php';
}

/**
 *  In the above points to where configs will be copied to. The path is relative to where is. The option is read for
 *  the root package, which is typically an application. Default is "/config/packages".
 */
define('YII_CONFIG_DIRECTORY', getenv('YII_CONFIG_DIRECTORY') ?: dirname(__DIR__, 3));

/**
 *  In the above points to where configs will be copied to. The path is relative to where is. The option is read for
 *  the root package, which is typically an application. Default is "/config/packages".
 */
define('WIDGET_CONFIG_PATH', getenv('WIDGET_CONFIG_PATH') ?: YII_CONFIG_DIRECTORY . '/config');

/**
 *  Set debug value for web application runner, for default its `true` add additionally the validation of the
 *  container-di configurations (debug mode).
 */
define('YII_DEBUG', getenv('YII_DEBUG') ?: true);

/**
 *  Set environment value for web application runner, for default its `null`.
 *
 *  @link https://github.com/yiisoft/config#environments
 */
define('YII_ENV', getenv('YII_ENV') ?: 'tests');

// Run web application runner
$runner = new HttpApplicationRunner(YII_CONFIG_DIRECTORY, YII_DEBUG, YII_ENV);
$runner->run();
