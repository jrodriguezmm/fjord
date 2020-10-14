<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Plugin\PluginHelper;
use YOOtheme\Config;
use YOOtheme\Database;

class SystemCheck extends \YOOtheme\Theme\SystemCheck
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Database
     */
    protected $database;

    /**
     * SystemCheck constructor.
     *
     * @param Database $database
     * @param Config   $config
     */
    public function __construct(Database $database, Config $config)
    {
        $this->config = $config;
        $this->database = $database;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequirements()
    {
        $res = [];

        // Page cache @TODO is needed?
        if (PluginHelper::isEnabled('system', 'cache')) {
            $res[] = 'j_page_cache';
        }

        // Check for SEBLOD Plugin and setting
        $components = ComponentHelper::getComponents();
        $cck = isset($components['com_cck']) ? $components['com_cck'] : false;
        if ($cck && $cck->enabled == 1) {
            if ($cck->params->get('hide_edit_icon')) {
                $res[] = 'j_seblod_icon';
            }
        }

        try {

            // Check for RSFirewall settings @TODO check if enabled?
            $rsfw = $this->database->fetchAssoc("SELECT value FROM @rsfirewall_configuration WHERE name = 'verify_emails'");

            if ($rsfw['value'] == 1) {
                $res[] = 'j_rsfw_mail';
            }

        } catch (\Exception $e) {}

        return array_merge($res, parent::getRequirements());
    }

    /**
     * {@inheritdoc}
     */
    public function getRecommendations()
    {
        $res = [];

        if (!$this->config->get('app.apikey')) {
            $res[] = 'j_apikey';
        }

        return array_merge($res, parent::getRecommendations());
    }
}
