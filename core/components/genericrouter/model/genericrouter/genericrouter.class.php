<?php

require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';

use \ModxGenericRouter\Expression;

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

        $routes = $this->getRoutes();

        foreach ($routes as $route) {
            $cleanRoute = $this->cleanAlias($route['expression']);
            if ($requestAlias === substr($cleanRoute, 1)) {
                $this->modx->sendForward($route['target']);
                die();
            }
        }

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

            // TODO link, system setting, everything else
        }

        return $output;
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

        return $this->cleanAlias($_REQUEST[$this->modx->getOption('request_param_alias',null,'q')]);
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
