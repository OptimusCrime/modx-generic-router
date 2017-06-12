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

    function __construct(\modX &$modx, array $config = [])
    {

        $this->modx =& $modx;

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

        // We need an instance of modRequest to use the cleaning method...
        $responseClass = $this->modx->getOption('modRequest.class', $this->modx->config, 'modRequest');
        $className = $this->modx->loadClass($responseClass, '', !empty(''), true);
        $request = new $className($this->modx);

        return $request->_cleanResourceIdentifier($_REQUEST[$this->modx->getOption('request_param_alias',null,'q')]);
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
