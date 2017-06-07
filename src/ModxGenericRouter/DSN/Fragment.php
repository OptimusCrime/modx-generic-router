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

    public function addContent($c)
    {
        $this->content .= $c;
    }

    public function addAllContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function addAllFields(array $fields)
    {
        $this->fields = $fields;
    }

    public function getFields()
    {
        return $this->fields;
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
