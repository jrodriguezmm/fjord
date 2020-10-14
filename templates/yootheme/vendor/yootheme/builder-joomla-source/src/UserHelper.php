<?php

namespace YOOtheme\Builder\Joomla\Source;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Router\Route;
use Joomla\Component\Contact\Site\Helper\RouteHelper;
use function YOOtheme\app;
use YOOtheme\Database;

class UserHelper
{
    /**
     * Gets the user's contact.
     *
     * @param int $id
     *
     * @return object
     */
    public static function getContact($id)
    {
        static $contacts = [];

        if (!isset($contacts[$id])) {

            /**
             * @var Database $db
             */
            $db = app(Database::class);

            $query = 'SELECT id AS contactid, alias, catid
                FROM #__contact_details
                WHERE published = 1 AND user_id = :id';

            $params = ['id' => $id];

            if (Multilanguage::isEnabled() === true) {
                $query .= ' AND (language in (:lang) OR language IS NULL)';
                $params += ['lang' => [Factory::getLanguage()->getTag(), '*']];
            }

            $query .= 'ORDER BY id DESC LIMIT 1';

            $contacts[$id] = $db->fetchObject($query, $params);
        }

        return $contacts[$id];
    }

    /**
     * Gets the user's contact link.
     *
     * @param int $id
     *
     * @return string|void
     */
    public static function getContactLink($id)
    {
        $contact = self::getContact($id);

        if (empty($contact->contactid)) {
            return;
        }

        return Route::_(RouteHelper::getContactRoute("{$contact->contactid}:{$contact->alias}", $contact->catid));
    }
}
