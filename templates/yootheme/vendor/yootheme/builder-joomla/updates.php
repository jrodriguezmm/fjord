<?php

namespace YOOtheme;

return [

    '2.2.0-beta.0.1' => function ($node, array $params) {

        static $fields;

        if (class_exists(\FieldsHelper::class) && is_null($fields)) {
            $fields = array_column(\FieldsHelper::getFields(null), 'type', 'name');
        }

        if (isset($node->source->query->field->name) && in_array('field', $field = explode('.', $node->source->query->field->name))) {

            $node->source->query->field->name = strtr($node->source->query->field->name, '-', '_');

            // snake case repeatable field names
            if (isset($fields[end($field)]) && $fields[end($field)] === 'repeatable') {
                foreach ((array) $node->source->props as $prop) {
                    $prop->name = Str::snakeCase($prop->name);
                }
            }

        }

        if (isset($node->source->props)) {

            // snake case custom field names
            foreach ((array) $node->source->props as $prop) {
                if (isset($prop->name) && in_array('field', explode('.', $prop->name))) {
                    $prop->name = strtr($prop->name, '-', '_');
                }
            }

        }

    },

];
