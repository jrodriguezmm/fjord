<?php

defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Plugin\CMSPlugin;

// require classmap
require_once __DIR__ . '/classmap.php';

class plgSystemYOOtheme extends CMSPlugin
{
    /**
     * @var \JDatabaseDriver
     */
    public $db;

    /**
     * @var CMSApplication
     */
    public $app;

    /**
     * Constructor.
     *
     * @param \JEventDispatcher $subject
     * @param array             $config
     */
    public function __construct(&$subject, $config = array())
    {
        parent::__construct($subject, $config);

        $pattern = JPATH_ROOT . '/templates/*/template_bootstrap.php';

        array_map([$this, 'loadFile'], glob($pattern) ?: array());
    }

    /**
     * Loads a file.
     *
     * @param  string $file
     * @return void
     */
    public function loadFile($file)
    {
        require $file;
    }
}
