<?php

namespace YOOtheme\Builder\Joomla\Fields\Type;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\Component\Users\Administrator\Helper\UsersHelper;
use YOOtheme\Builder\Joomla\Source\ArticleHelper;
use YOOtheme\Builder\Source;
use YOOtheme\Str;

class FieldsType
{
    /**
     * @var string
     */
    protected $context;

    /**
     * Constructor.
     *
     * @param string $context
     */
    public function __construct($context)
    {
        $this->context = $context;
    }

    /**
     * @param Source $source
     * @param string $type
     * @param string $context
     * @param array  $fields
     *
     * @return array
     */
    public static function config(Source $source, $type, $context, array $fields)
    {
        return [

            'fields' => array_filter(array_map(function ($field) use ($source, $type, $context) {

                $config = [
                    'type' => 'String',
                    'name' => strtr($field->name, '-', '_'),
                    'metadata' => [
                        'label' => $field->title,
                        'group' => $field->group_title,
                    ],
                    'extensions' => [
                        'call' => "{$type}.fields@resolve",
                    ],
                ];

                if (is_callable($callback = [__CLASS__, "config{$field->type}"])) {
                    return $callback($field, $config, $source);
                }

                return static::configField($field, $config);

            }, $fields)),

            'extensions' => [

                'bind' => [

                    "{$type}.fields" => [
                        'class' => __CLASS__,
                        'args' => ['$context' => $context],
                    ],

                ],

            ],

        ];
    }

    protected static function configField($field, array $config)
    {
        if ($field->fieldparams->get('multiple')) {
            return ['type' => ['listOf' => 'ValueField']] + $config;
        }

        return $config;
    }

    protected static function configText($field, array $config)
    {
        return array_replace_recursive($config, ['metadata' => ['filters' => ['limit']]]);
    }

    protected static function configTextarea($field, array $config)
    {
        return array_replace_recursive($config, ['metadata' => ['filters' => ['limit']]]);
    }

    protected static function configEditor($field, array $config)
    {
        return array_replace_recursive($config, ['metadata' => ['filters' => ['limit']]]);
    }

    protected static function configCalendar($field, array $config)
    {
        return array_replace_recursive($config, ['metadata' => ['filters' => ['date']]]);
    }

    protected static function configUser($field, array $config)
    {
        return ['type' => 'User'] + $config;
    }

    protected static function configArticles($field, array $config)
    {
        return ['type' => $field->fieldparams->get('multiple') ? ['listOf' => 'Article'] : 'Article'] + $config;
    }

    protected static function configRepeatable($field, array $config, Source $source)
    {
        $fields = [];

        foreach ((array) $field->fieldparams->get('fields') as $params) {

            $fields[$params->fieldname] = [
                'type' => 'String',
                'name' => Str::snakeCase($params->fieldname),
                'metadata' => [
                    'label' => $params->fieldname,
                    'group' => $field->group_title,
                    'filters' => !in_array($params->fieldtype, ['media', 'number']) ? ['limit'] : [],
                ],
            ];

        }

        if ($fields) {

            $name = Str::camelCase(['Field', $field->name], true);
            $source->objectType($name, compact('fields'));

            return ['type' => ['listOf' => $name]] + $config;
        }
    }

    protected static function configSql($field, array $config)
    {
        return ['type' => $field->fieldparams->get('multiple') ? ['listOf' => 'SqlField'] : 'SqlField'] + $config;
    }

    protected static function configList($field, array $config)
    {
        return ['type' => $field->fieldparams->get('multiple') ? ['listOf' => 'ChoiceField'] : 'ChoiceField'] + $config;
    }

    protected static function configRadio($field, array $config)
    {
        return ['type' => 'ChoiceField'] + $config;
    }

    protected static function configCheckboxes($field, array $config)
    {
        return ['type' => ['listOf' => 'ChoiceField']] + $config;
    }

    public static function field($item, $args, $ctx, $info)
    {
        return $item;
    }

    public function resolve($item, $args, $ctx, $info)
    {
        $name = strtr($info->fieldName, '_', '-');

        if (!isset($item->id) || !$field = $this->getField($name, $item)) {
            return;
        }

        if (is_callable($callback = [$this, "resolve{$field->type}"])) {
            return $callback($field);
        }

        return $this->resolveField($field, $field->rawvalue);
    }

    public function resolveField($field, $value)
    {
        if ($field->fieldparams->exists('multiple')) {

            $value = (array) $value;

            if ($field->fieldparams['multiple']) {
                return array_map(function ($value) {
                    return is_scalar($value) ? compact('value') : $value;
                }, $value);
            } else {
                return array_shift($value);
            }
        }

        return $field->rawvalue;
    }

    public function resolveUser($field)
    {
        return Factory::getUser($field->rawvalue);
    }

    public function resolveArticles($field)
    {
        $ordering = $field->fieldparams->get('articles_ordering', 'ordering');
        $direction = $field->fieldparams->get('articles_ordering_direction', 'ASC');

        return $this->resolveField($field, ArticleHelper::get($field->rawvalue, [
            'list.ordering' => "a.{$ordering}",
            'list.direction' => $direction,
        ]));
    }

    public function resolveRepeatable($field)
    {
        return array_map(function ($vals) {

            $values = [];

            foreach ($vals as $name => $value) {
                $values[Str::snakeCase($name)] = $value;
            }

            return $values;

        }, array_values(json_decode($field->rawvalue, true) ?: []));
    }

    public function resolveList($field)
    {
        return $this->resolveSelect($field, $field->fieldparams->get('multiple'));
    }

    public function resolveCheckboxes($field)
    {
        return $this->resolveSelect($field, true);
    }

    public function resolveRadio($field)
    {
        return $this->resolveSelect($field);
    }

    public function resolveSelect($field, $multiple = false)
    {
        $result = [];

        foreach ($field->fieldparams->get('options', []) as $option) {
            if ($multiple) {
                if (in_array($option->value, (array) $field->rawvalue ?: [])) {
                    $result[] = $option;
                }
            } elseif ($option->value === $field->rawvalue) {
                return $option;
            }
        }

        return $result;
    }

    public function resolveImagelist($field)
    {
        $root = ComponentHelper::getParams('com_media')->get('file_path', 'images') . "/{$field->fieldparams->get('directory')}";

        return $this->resolveField($field, array_map(function ($value) use ($root) {
            return "{$root}/{$value}";
        }, array_filter((array) $field->rawvalue, function ($value) {
            return $value && $value != -1;
        })));
    }

    public function resolveUsergrouplist($field)
    {
        return $this->resolveField($field, array_intersect_key($this->getUserGroups(), array_flip((array) $field->rawvalue)));
    }

    public function resolveSql($field)
    {
        if ($field->value === '') {
            return;
        }

        $db = Factory::getDbo();
        $query = $field->fieldparams->get('query', '');
        $condition = array_reduce((array) $field->value, function ($carry, $value) use ($db) {
            return $value ? $carry . ", {$db->q($value)}" : $carry;
        });

        // Run the query with a having condition because it supports aliases
        $db->setQuery($query . ' having value in (' . trim($condition, ',') . ')');

        try {

            $items = $db->loadObjectlist();

            return $field->fieldparams->get('multiple') ? $items : array_pop($items);

        } catch (\Exception $e) {
            return;
        }
    }

    protected function getUserGroups()
    {
        $data = [];

        foreach (UsersHelper::getGroups() as $group) {
            $data[$group->value] = Text::_(preg_replace('/^(- )+/', '', $group->text));
        }

        return $data;
    }

    protected function getField($name, $item)
    {
        foreach (\FieldsHelper::getFields($this->context, $item) as $field) {
            if ($field->name === $name) {
                return $field;
            }
        }
    }
}
