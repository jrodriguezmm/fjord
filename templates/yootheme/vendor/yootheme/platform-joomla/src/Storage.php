<?php

namespace YOOtheme\Joomla;

use Joomla\CMS\Factory;
use YOOtheme\Database as DatabaseInterface;
use YOOtheme\Storage as AbstractStorage;

class Storage extends AbstractStorage
{
    /**
     * Constructor.
     *
     * @param Database $db
     * @param string   $element
     * @param string   $folder
     *
     * @throws \Exception
     */
    public function __construct(DatabaseInterface $db, $element = 'yootheme', $folder = 'system')
    {
        $query = 'SELECT custom_data FROM @extensions WHERE element = :element AND folder = :folder LIMIT 1';
        $result = $db->fetchAssoc($query, compact('element', 'folder'));

        if ($result) {
            $this->addJson($result['custom_data']);
        }

        $app = Factory::getApplication();
        $app->registerEvent('onAfterRespond', function () use ($db, $result, $element, $folder) {
            if ($values = json_encode($this) and $values != $result['custom_data']) {
                $db->update('@extensions', ['custom_data' => $values], compact('element', 'folder'));
            }
        });
    }
}
