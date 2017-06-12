<?php

class GenericRouterDeleteProcessor extends modObjectRemoveProcessor {
    public $classKey = 'Routes';
    public $languageTopics = ['smartcache:default'];
    public $objectType = 'genericrouter.genericrouter';
}

return 'GenericRouterDeleteProcessor';
