<?php

class GenericRouterGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'Routes';
    public $languageTopics = ['genericrouter'];
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';
    public $objectType = 'genericrouter.genericrouter';

    /*
    public function prepareRow(xPDOObject $object) {
        $object_array = $object->toArray();

        $resource = $this->modx->getObject('modResource', $object_array['resource']);
        if ($resource) {
            $object_array['resource_pretty'] = $resource->get('pagetitle') .' ('.$object_array['resource'].')'.(($object_array['children'] == 1)?' & children':'');
        }
        else {
            $object_array['resource_pretty'] = 'Not found! ('.$object_array['resource'].')';
        }

        $constraints = array();
        if ($object_array['on_create'] == 1)
            $constraints[] = 'create';
        if ($object_array['on_update'] == 1)
            $constraints[] = 'update';
        if ($object_array['on_sort'] == 1)
            $constraints[] = 'sort';
        if ($object_array['on_delete'] == 1)
            $constraints[] = 'delete';

        $object_array['constraints'] = ucfirst(implode(', ',$constraints));

        return $object_array;
    }*/
}

return 'GenericRouterGetListProcessor';
