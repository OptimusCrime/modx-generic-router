<?php

require_once dirname(dirname(__FILE__)) . '/model/genericrouter/genericrouter.class.php';

class GenericRouterHomeManagerController extends modExtraManagerController {

    private $genericRouter;

    public function initialize()
    {
        $this->genericRouter = new GenericRouter($this->modx);

        $this->addJavascript($this->genericRouter->getConfig('jsUrl') . 'mgr/genericrouter.class.js');
        $this->addHtml('<script type="text/javascript">
            Ext.onReady(function() {
                GenericRouter.config = ' . $this->modx->toJSON($this->genericRouter->getConfig()) . ';
            });
        </script>');

        return parent::initialize();
    }

    public function getLanguageTopics()
    {
        return array('genericrouter:default');
    }

    public function checkPermissions()
    {
        return true;
    }

    public function getPageTitle()
    {
        return $this->modx->lexicon('genericrouter');
    }

    public function loadCustomCssJs() {
        $this->addJavascript($this->genericRouter->getConfig('jsUrl') . 'mgr/widgets/home.panel.js');
        $this->addJavascript($this->genericRouter->getConfig('jsUrl') . 'mgr/widgets/home.grid.js');
        $this->addLastJavascript($this->genericRouter->getConfig('jsUrl') . 'mgr/sections/home.js');
    }

    public function getTemplateFile()
    {
        return $this->genericRouter->getConfig('templatesPath') . 'home.tpl';
    }
}
