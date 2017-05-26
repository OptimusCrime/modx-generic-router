<?php

namespace ModxGenericRouter\Tokens\Modx;

use ModxGenericRouter\Utilities\ParentRelationship;

abstract class ModxToken extends ParentRelationship
{
    protected $cached;
    protected $name;
    protected $properties;
    protected $data;

    public function __construct()
    {
        parent::__construct();

        $this->cached = false;
        $this->name = null;
        $this->properties = [];
        $this->data = [];
    }

    public function setData($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function getData($key)
    {
        if (!isset($this->data[$key])) {
            return null;
        }

        return $this->data[$key];
    }

    public function removeData($key)
    {
        if ($this->getData($key) !== null) {
            unset($this->data[$key]);
        }
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getNamePretty()
    {
        if ($this->name === null) {
            return null;
        }

        return $this->name->getPretty();
    }

    public function setCached($flag)
    {
        $this->cached = $flag;
    }

    public function isCached()
    {
        return $this->cached;
    }

    public function setProperties(array $properties)
    {
        $this->properties = $properties;
    }

    public function getProperties()
    {
        return $this->properties;
    }

    public function toArray()
    {
        $properties = [];
        foreach ($this->properties as $property) {
            $values = [];
            foreach ($property['value'] as $v) {
                $values[] = $v->toArray();
            }

            $properties[] = [
                'name' => $property['name'],
                'value' => $values
            ];
        }

        $name = null;
        if ($this->name !== null) {
            $name = $this->name->toArray();
        }

        $tokenArr = [
            'type' => null,
            'cached' => (int) $this->cached,
            'name' => $name,
        ];

        if (count($properties) > 0) {
            $tokenArr['properties'] = $properties;
        }

        return $tokenArr;
    }
}
