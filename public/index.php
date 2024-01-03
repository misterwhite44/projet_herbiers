<?php

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');

    $kernel = new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);

    if ($_SERVER['REQUEST_URI'] === '/') {
        $request = Request::createFromGlobals();
        $pathInfo = $request->getPathInfo();

        $newUri = $pathInfo . 'releve/';

        $request = $request->duplicate(null, null, null, null, null, ['REQUEST_URI' => $newUri]);

        return $kernel->handle($request);
    }

    return $kernel;
};
