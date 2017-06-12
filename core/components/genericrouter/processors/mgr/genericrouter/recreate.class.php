<?php

class GenericRouterRecreateProcessor extends modObjectGetListProcessor {
    public $classKey = 'Routes';
    public $languageTopics = ['genericrouter'];
    public $objectType = 'genericrouter.genericrouter';

    private function loadModel()
    {
        $corePath = $this->modx->getOption('genericrouter.core_path', null, $this->modx->getOption('core_path') . 'components/genericrouter/');
        require_once $corePath . 'model/genericrouter/genericrouter.class.php';

        $this->genericRouter = new GenericRouter($this->modx);
    }

    public function process()
    {
        $this->loadModel();
        $this->genericRouter->recreateAll();

        return $this->success();
    }
}

return 'GenericRouterRecreateProcessor';
