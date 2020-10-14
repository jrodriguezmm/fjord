<?php

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

if ($this->params->get('show_advanced', 1) || $this->params->get('show_autosuggest', 1))
{
	HTMLHelper::_('jquery.framework');

	$script = '
jQuery(function() {';

	if ($this->params->get('show_advanced', 1))
	{
		/*
		 * This segment of code disables select boxes that have no value when the
		 * form is submitted so that the URL doesn't get blown up with null values.
		 */
		$script .= "
	jQuery('#finder-search').on('submit', function(e){
		e.stopPropagation();
		// Disable select boxes with no value selected.
		jQuery('#advancedSearch').find('select').each(function(index, el) {
			var el = jQuery(el);
			if(!el.val()){
				el.attr('disabled', 'disabled');
			}
		});
	});";
	}

	// This segment of code sets up the autocompleter.
	if ($this->params->get('show_autosuggest', 1))
	{
		HTMLHelper::_('script', 'jui/jquery.autocomplete.min.js', ['version' => 'auto', 'relative' => true]);

		$script .= "
	var suggest = jQuery('#q').autocomplete({
		serviceUrl: '" . Route::_('index.php?option=com_finder&task=suggestions.suggest&format=json&tmpl=component') . "',
		paramName: 'q',
		minChars: 1,
		maxHeight: 400,
		width: 300,
		zIndex: 9999,
		deferRequestBy: 500
	});";
	}

	$script .= '
});';

	Factory::getDocument()->addScriptDeclaration($script);
}

?>
<form id="finder-search" action="<?= Route::_($this->query->toUri()) ?>" method="get" class="form-inline">

	<?= $this->getFields() ?>

	<?php // DISABLED UNTIL WEIRD VALUES CAN BE TRACKED DOWN. ?>
	<?php if (false && $this->state->get('list.ordering') !== 'relevance_dsc') : ?>
		<input type="hidden" name="o" value="<?= $this->escape($this->state->get('list.ordering')) ?>" />
	<?php endif ?>

	<fieldset class="word">
		<div class="uk-grid-small" uk-grid>
            <div class="uk-width-expand@s">

				<input type="text" name="q" id="q" size="30" value="<?= $this->escape($this->query->input) ?>" class="uk-input" style="margin: 0 !important" />

			</div>
			<div class="uk-width-auto@s">

				<div class="uk-grid-small" uk-grid>
					<div class="uk-width-auto@s">
						<?php if ($this->escape($this->query->input) != '' || $this->params->get('allow_empty_query')) : ?>
							<button name="Search" type="submit" class="uk-button uk-button-primary uk-width-1-1"><?= Text::_('JSEARCH_FILTER_SUBMIT') ?></button>
						<?php else : ?>
							<button name="Search" type="submit" class="uk-button uk-button-primary uk-width-1-1"><?= Text::_('JSEARCH_FILTER_SUBMIT') ?></button>
						<?php endif ?>
					</div>
					<?php if ($this->params->get('show_advanced', 1)) : ?>
					<div class="uk-width-auto@s"><a href="#advancedSearch" uk-toggle="target: #advancedSearch" class="uk-button uk-button-default uk-width-1-1"><?= Text::_('COM_FINDER_ADVANCED_SEARCH_TOGGLE') ?></a></div>
					<?php endif ?>
				</div>

			</div>
		</div>
	</fieldset>

	<?php if ($this->params->get('show_advanced', 1)) : ?>
		<div id="advancedSearch" class="uk-margin" <?php if (!$this->params->get('expand_advanced', 0)) echo ' hidden' ?>>

			<?php if ($this->params->get('show_advanced_tips', 1)) : ?>
				<?= Text::_('COM_FINDER_ADVANCED_TIPS') ?>
			<?php endif ?>

			<div id="finder-filter-window">
				<?= HTMLHelper::_('filter.select', $this->query, $this->params) ?>
			</div>

		</div>
	<?php endif ?>

</form>
