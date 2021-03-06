<?php
/*
* This file is a part of graphql-youshido project.
*
* @author Alexandr Viniychuk <a@viniychuk.com>
* created: 12/1/15 11:05 PM
*/

namespace Youshido\GraphQL\Config\Traits;


use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Field\Field;
use Youshido\GraphQL\Field\FieldInterface;

/**
 * Class FieldsAwareTrait
 * @package Youshido\GraphQL\Config\Traits
 */
trait FieldsAwareConfigTrait
{
    protected $fields = [];

    public function buildFields()
    {
        if (!empty($this->data['fields'])) {
            $this->addFields($this->data['fields']);
        }
    }

    /**
     * @param array $fieldsList
     * @return $this
     */
    public function addFields($fieldsList)
    {
        foreach ($fieldsList as $fieldName => $fieldConfig) {

            if ($fieldConfig instanceof FieldInterface) {
                $this->fields[$fieldConfig->getName()] = $fieldConfig;
                continue;
            } else {
                $this->addField($fieldName, $this->buildFieldConfig($fieldName, $fieldConfig));
            }
        }

        return $this;
    }

    /**
     * @param AbstractField|string $field     Field name or Field Object
     * @param mixed                $fieldInfo Field Type or Field Config array
     * @return $this
     */
    public function addField($field, $fieldInfo = null)
    {
        if (!($field instanceof FieldInterface)) {
            $field = new Field($this->buildFieldConfig($field, $fieldInfo));
        }

        $this->fields[$field->getName()] = $field;

        return $this;
    }

    protected function buildFieldConfig($name, $info = null)
    {
        if (!is_array($info)) {
            $info = [
                'type' => $info,
                'name' => $name,
            ];
        } elseif (empty($info['name'])) {
            $info['name'] = $name;
        }

        return $info;
    }

    /**
     * public function addFieldOld($name, $type, $config = [])
     * {
     * if (
     * isset($this->contextObject)
     * && method_exists($this->contextObject, 'getKind')
     * && $this->contextObject->getKind() == TypeMap::KIND_INPUT_OBJECT
     * ) {
     * $field = new InputField($config);
     * } else {
     * $field = new Field($config);
     * }
     * }
     */

    /**
     * @param $name
     *
     * @return Field
     */
    public function getField($name)
    {
        return $this->hasField($name) ? $this->fields[$name] : null;
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function hasField($name)
    {
        return array_key_exists($name, $this->fields);
    }

    public function hasFields()
    {
        return !empty($this->fields);
    }

    /**
     * @return Field[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    public function removeField($name)
    {
        if ($this->hasField($name)) {
            unset($this->fields[$name]);
        }

        return $this;
    }
}
