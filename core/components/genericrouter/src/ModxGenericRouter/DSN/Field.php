<?php
namespace OptimusCrime\ModxGenericRouter\DSN;

class Field
{
    private $value;
    private $templateVariable;

    public function __construct($value = null)
    {
        if ($value === null) {
            return;
        }

        $this->setValue($value);
        $this->setTemplateVariable(substr($value, 0, 3) === 'tv.');
    }

    public function setValue($value)
    {
        if (substr($value, 0, 3) === 'tv.') {
            $this->value = substr($value, 3);
            return;
        }

        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setTemplateVariable($flag)
    {
        $this->templateVariable = $flag;
    }

    public function isTemplateVariable()
    {
        return $this->templateVariable;
    }

    public function toArray()
    {
        return [
            'templateVariable' => $this->templateVariable,
            'value' => $this->value
        ];
    }

    public static function fromArrays(array $data)
    {
        $fields = [];
        foreach ($data as $item) {
            $fields[] = Field::fromArray($item);
        }

        return $fields;
    }

    public static function fromArray(array $data)
    {
        $field = new Field();
        $field->setValue($data['value']);
        $field->setTemplateVariable($data['templateVariable']);
        return $field;
    }

    public function __toString()
    {
        $str = '';
        if ($this->templateVariable) {
            $str = 'tv.';
        }

        return $str . $this->value;
    }
}
