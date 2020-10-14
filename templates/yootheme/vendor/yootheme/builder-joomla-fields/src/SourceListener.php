<?php

namespace YOOtheme\Builder\Joomla\Fields;

use YOOtheme\Arr;

class SourceListener
{
    public static function initSource($source)
    {
        if (!class_exists(\FieldsHelper::class)) {
            return;
        }

        $types = [
            'User' => 'com_users.user',
            'Article' => 'com_content.article',
            'Category' => 'com_content.categories',
        ];

        $source->objectType('SqlField', Type\SqlFieldType::config());
        $source->objectType('ValueField', Type\ValueFieldType::config());
        $source->objectType('ChoiceField', Type\ChoiceFieldType::config());

        foreach ($types as $type => $context) {

            $fields = [];

            foreach (\FieldsHelper::getFields($context) as $field) {
                if ($field->state == 1) {
                    $fields[$field->name] = $field;
                }
            }

            // has custom fields?
            if ($fields) {

                static::configFields($source, $type, $context, $fields);

                if ($type === 'Article') {
                    static::configOrder($source, 'customArticle', $fields);
                    static::configOrder($source, 'customArticles', $fields);
                }
            }
        }
    }

    protected static function configFields($source, $type, $context, array $fields)
    {
        // add field on type
        $source->objectType($type, [

            'fields' => [

                'field' => [
                    'type' => $fieldType = "{$type}Fields",
                    'metadata' => [
                        'label' => 'Fields',
                    ],
                    'extensions' => [
                        'call' => Type\FieldsType::class . '::field',
                    ],
                ],

            ],

        ]);

        // configure field type
        $source->objectType($fieldType, Type\FieldsType::config($source, $type, $context, $fields));
    }

    protected static function configOrder($source, $query, array $fields)
    {
        $key = "fields.{$query}.metadata.fields._order.fields.order.options";

        $source->queryType(function ($typeDef) use ($key, $fields) {

            Arr::update($typeDef->config, $key, function ($options) use ($fields) {

                $orderOptions = [];

                foreach ($fields as $field) {
                    $orderOptions[$field->title] = "field:{$field->id}";
                }

                return array_replace_recursive($options, ['Custom Fields' => $orderOptions]);
            });

        });
    }
}
