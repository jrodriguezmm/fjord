<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Factory;

trait ViewTrait
{
    /**
     * Load a template file -- with triggering 'onLoadTemplate' event.
     *
     * @param string $tpl
     *
     * @return string
     */
    public function loadTemplate($tpl = null)
    {
        $this->_output = null;

        // trigger load template event
        $app = Factory::getApplication();
        $app->triggerEvent('onLoadTemplate', [$this, $tpl]);

        // event set the output?
        if (is_null($this->_output)) {
            parent::loadTemplate($tpl);
        }

        return $this->_output;
    }
}
