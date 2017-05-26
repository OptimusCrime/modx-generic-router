<?php

namespace ModxGenericRouter\Tokens\Modx;

class Snippet extends ModxToken
{
    public function __toString()
    {
        $start = '<' . ($this->cached ? '!' : '' ) . 'Snippet: ' . $this->getNamePretty() . PHP_EOL;
        $start .= 'Propeties: {' . PHP_EOL;
        foreach ($this->properties as $v) {
            $start .= '    ' . $v['name'] . ' => ' . implode(' ', $v['value']) . PHP_EOL;
        }
        $start .= '}' . PHP_EOL;

        return $start;
    }

    public function toArray()
    {
        $arr = parent::toArray();
        $arr['type'] = 'snippet';

        return $arr;
    }
}
