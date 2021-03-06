<?php

require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';

use ModxGenericRouter\Expression;
use ModxGenericRouter\DSN\Fragment;

use FastRoute\RouteCollector;
use FastRoute\Dispatcher;

class GenericRouter {

    const MODE_LAZY = 0;
    const MODE_EAGER = 1;

    const TYPE_RESOURCE = 0;
    const TYPE_MATCH = 1;

    private $modx;
    private $config;
    private $request;

    function __construct(\modX &$modx, array $config = [])
    {
        $this->modx =& $modx;
        $this->request = null;

        $basePath = $this->modx->getOption(
            'genericrouter.core_path',
            $config,
            $this->modx->getOption('core_path') . 'components/genericrouter/'
        );

        $assetsUrl = $this->modx->getOption(
            'genericrouter.assets_url',
            $config,
            $this->modx->getOption('assets_url') . 'components/genericrouter/'
        );

        $this->config = array_merge([
            'basePath' => $basePath,
            'corePath' => $basePath,
            'modelPath' => $basePath . 'model/',
            'processorsPath' => $basePath . 'processors/',
            'templatesPath' => $basePath . 'templates/',
            'jsUrl' => $assetsUrl . 'js/',
            'cssUrl' => $assetsUrl . 'css/',
            'assetsUrl' => $assetsUrl,
            'connectorUrl' => $assetsUrl . 'connector.php',
        ], $config);

        $this->modx->addPackage('genericrouter', $this->config['modelPath']);
    }

    public function handleRequest()
    {
        $requestAlias = $this->cleanRequestAlias();
        if ($requestAlias === null) {
            return;
        }

        $dispatcher = $this->buildDispatcher();

        $routeInfo = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $requestAlias);
        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:
                // Set GET variables
                if (isset($routeInfo[2]) and count($routeInfo[2]) > 0) {
                    foreach ($routeInfo[2] as $k => $v) {
                        $_GET[$k] = $v;
                    }
                }

                // Send forward
                $this->modx->sendForward($routeInfo[1]);
                exit();

                break;
            default:
                return null;
                break;
        }
    }

    private function buildDispatcher()
    {
        $routes = $this->getRoutes();

        return FastRoute\simpleDispatcher(function(RouteCollector $r) use ($routes) {
            foreach ($routes as $route) {
                $cleanRoute = $this->cleanAlias($route['expression']);

                // DEBUG
                $this->modx->log(1, 'Adding GET route for ' . $cleanRoute);

                $r->addRoute('GET', $cleanRoute, $route['target']);
            }
        });
    }

    private function getRoutes()
    {
        $routes = [];
        $c = $this->modx->newQuery('Routes');
        $c->where([
            'enabled' => 1,
            # Add mode
        ]);
        $c->sortby('priority', 'DESC');
        $collection = $this->modx->getCollection('Routes', $c);

        foreach ($collection as $v) {
            if ($v->get('expression') === null or $v->get('target') === null) {
                continue;
            }

            // Turn into Expression
            $expression = new Expression($v->get('expression'));

            $representation = $v->get('representation');
            if ($representation !== null) {
                $expression->load($representation);
            }
            else {
                $expression->parse();
            }

            $expressionValues = $this->expandExpression($expression);

            if ($expressionValues === null) {
                continue;
            }

            if (is_string($expressionValues)) {
                array_push($routes, [
                    'expression' => $expressionValues,
                    'target' => $v->get('target')
                ]);
            }
        }

        return $routes;
    }

    private function expandExpression(Expression $expression)
    {
        $output = '';
        foreach ($expression->getTree() as $v) {
            if ($v->isRaw()) {
                $output .= $v->getContent();
            }
            elseif ($v->isUrl()) {
                $output .= $this->handleUrl($v);
            }
        }

        return $output;
    }

    private function handleUrl(Fragment $fragment)
    {
        if ($fragment->isSystemSetting()) {
            // TODO
        }

        if ($fragment->getDepth() > 0) {
            // TODO
        }

        if (count($fragment->getFields()) > 0) {
            // TODO
        }

        return $this->modx->makeUrl($fragment->getContent());
    }

    private function cleanRequestAlias()
    {
        if ($this->modx->getOption('friendly_urls', null, false) === 'id') {
            return null;
        }

        $hasAlias = isset($_REQUEST[$this->modx->getOption('request_param_alias',null,'q')]);
        if (!$hasAlias) {
            return null;
        }

        return '/' . $this->cleanAlias($_REQUEST[$this->modx->getOption('request_param_alias',null,'q')]);
    }

    private function cleanAlias($alias)
    {
        if ($this->request === null) {
            // We need an instance of modRequest to use the cleaning method...
            $responseClass = $this->modx->getOption('modRequest.class', $this->modx->config, 'modRequest');
            $className = $this->modx->loadClass($responseClass, '', !empty(''), true);
            $this->request = new $className($this->modx);
        }

        return $this->request->_cleanResourceIdentifier($alias);
    }

    public function getConfig($key = null)
    {
        if ($key === null) {
            return $this->config;
        }

        if (isset($this->config[$key])) {
            return $this->config[$key];
        }

        return null;
    }

    public function recreateAll()
    {
        $it = $this->modx->getIterator('Routes');
        foreach ($it as $route) {
            $this->recreateRoute($route);
        }
    }

    public function recreateRoute($route)
    {
        $expression = new Expression($route->get('expression'));
        $expression->parse();

        $route->set('representation', $expression->dump());
        $route->save();
    }
}
