<?php
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Session\Manager;
use Phalcon\Session\Adapter\Stream;
use Phalcon\Di\FactoryDefault; 
use Phalcon\Loader; 
use Phalcon\Mvc\View; 
use Phalcon\Mvc\Application; 
use Phalcon\Url; 
use Phalcon\Config; 
use Phalcon\Http\Response\Cookies;
use Phalcon\Escaper;
use Phalcon\Flash\Session as FlashSession;

$config = new Config([]);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

$loader = new Loader(); 

$loader->registerDirs(
    [
        APP_PATH . "/controllers/",
        APP_PATH . "/models/",
    ]
);

$loader->registerNamespaces(
    [
        'App\Forms' => APP_PATH.'/forms/',
        'App\Component' => APP_PATH.'/component/'
    ]
    );

$loader->registerClasses(
    [
        'App\Component\MyEscaper' => APP_PATH . '/component/MyEscaper.php',
    ]
);

$loader->register();


$container = new FactoryDefault(); 

$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');
        return $view;
    }
);


$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/'); 
        return $url;
    }
);

$container->set(
    'session',
    function () {
        $session = new Manager();
        $files = new Stream(
            [
                'savePath' => '/tmp',
            ]
        );

        $session
            ->setAdapter($files)
            ->start();
        return $session;
    }
);


$container->set(
    'db',
    function () {
        return new Mysql(
            [
                'host'     => 'mysql-server',
                'username' => 'root',
                'password' => 'secret',
                'dbname'   => 'store',
            ]
        );
    }
);


$container->set(
    'cookies',
    function () {
        $cookies = new Cookies();
        $cookies->useEncryption(false);
        return $cookies;
    }
);

$container->set(
    'flashSession',
    function () {
        global $container;
        $escaper = new Escaper();
        $flashSession   = new FlashSession($escaper, $container->get("session"));

        $cssClasses = [
            'error'   => 'alert alert-danger text-center',
            'success' => 'alert alert-success text-center',
            'notice'  => 'alert alert-info text-center',
            'warning' => 'alert alert-warning text-center',
        ];
        
        $flashSession->setCssClasses($cssClasses);
        return $flashSession;
    }
);


$application = new Application($container);

try {
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );
    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}
