<?php

namespace ModxGenericRouter\DSN;

class Fragment
{
    private $raw;
    private $url;
    private $id;
    private $systemSetting;
    private $depth;
    private $fields;
    private $content;

    public function __construct()
    {
        $this->raw = false;
        $this->url = false;
        $this->id = false;
        $this->systemSetting = false;
        $this->depth = 0;
        $this->fields = [];
        $this->content = '';
    }

    public function setUrl($flag)
    {
        $this->url = $flag;
    }

    public function isUrl()
    {
        return $this->url;
    }

    public function setId($flag)
    {
        $this->id = $flag;
    }

    public function isId()
    {
        return $this->id;
    }

    public function setSystemSetting($flag)
    {
        $this->systemSetting = $flag;
    }

    public function isSystemSetting()
    {
        return $this->systemSetting;
    }

    public function setRaw($flag)
    {
        $this->raw = $flag;
    }

    public function isRaw()
    {
        return $this->raw;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function addContent($c)
    {
        $this->content .= $c;
    }

    public function addAllContent($content)
    {
        $this->setContent($content);
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setDepth($depth)
    {
        if (!is_integer($depth)) {
            return;
        }

        $this->depth = $depth;
    }

    public function getDepth()
    {
        return $this->depth;
    }

    public function addAllFields(array $fields)
    {
        $this->fields = $fields;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function toArray()
    {
        $fields = null;
        if (count($this->fields) > 0) {
            $fields = [];
            foreach ($this->fields as $field) {
                $fields[] = $field->toArray();
            }
        }

        return [
            'raw' => $this->raw,
            'url' => $this->url,
            'id' => $this->id,
            'systemSetting' => $this->systemSetting,
            'depth' => $this->depth,
            'fields' => $fields,
            'content' => $this->content
        ];
    }

    public static function fromArray(array $data)
    {
        $fragment = new Fragment();
        $fragment->setRaw($data['raw']);
        $fragment->setUrl($data['url']);
        $fragment->setId($data['id']);
        $fragment->setSystemSetting($data['systemSetting']);
        $fragment->setDepth($data['depth']);
        $fragment->addAllContent($data['content']);

        if (count($data['fields']) > 0) {
            $fragment->addAllFields(Field::fromArrays($data['fields']));
        }

        return $fragment;
    }

    public function __toString()
    {
        if ($this->isRaw()) {
            return $this->content;
        }

        $output = '[[';
        if ($this->isUrl()) {
            $output .= '~';
        }
        if ($this->isSystemSetting()) {
            $output .= '++';
        }

        $output .= $this->content;

        if ($this->depth > 0) {
            $output .= '~' . $this->depth;
        }

        if (count($this->fields) > 0) {
            $output .= '=';
            $output .= implode('|', $this->fields);
        }

        return $output . ']]';
    }
}
