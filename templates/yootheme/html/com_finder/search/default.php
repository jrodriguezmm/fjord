<?php

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::_('behavior.core');
HTMLHelper::_('formbehavior.chosen');
HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');
HTMLHelper::_('stylesheet', 'com_finder/finder.css', ['version' => 'auto', 'relative' => true]);

?>
<div class="finder <?= $this->pageclass_sfx ?>">

	<?php if ($this->params->get('show_page_heading')) : ?>
	<h1>
		<?php if ($this->escape($this->params->get('page_heading'))) : ?>
			<?= $this->escape($this->params->get('page_heading')) ?>
		<?php else : ?>
            <?= $this->escape($this->params->get('page_title')) ?>
		<?php endif ?>
	</h1>
	<?php endif ?>

	<?php if ($this->params->get('show_search_form', 1)) : ?>
		<?= $this->loadTemplate('form') ?>
	<?php endif ?>

	<?php // Load the search results layout if we are performing a search. ?>
	<?php if ($this->query->search === true) : ?>
		<?= $this->loadTemplate('results') ?>
	<?php endif ?>

</div>
