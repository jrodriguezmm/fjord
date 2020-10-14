<?php

namespace YOOtheme;

defined('JPATH_BASE') or die;

use Joomla\CMS\Language\Text;

if (Path::get(__FILE__) !== $file = Path::get('~theme/html/plg_content_pagebreak/navigation.php')) {
    return include $file;
}

?>

<?php if ($links['next']) :
	$title = htmlspecialchars($this->list[$page + 2]->title, ENT_QUOTES, 'UTF-8');
	$ariaLabel = Text::_('JNEXT') . ': ' . $title . ' (' . Text::sprintf('JLIB_HTML_PAGE_CURRENT_OF_TOTAL', ($page + 2), $n) . ')';
?>
<div class="uk-grid-small uk-flex-middle uk-text-default" uk-grid>
	<div>
		<a class="uk-button uk-button-secondary" href="<?= $links['next'] ?>" aria-label="<?= $ariaLabel ?>" rel="next"><?= Text::_('TPL_YOOTHEME_NEXT_PAGE') ?></a>
	</div>

	<?php
	// Show title only if it's a custom title, and not eg. Page 2
	if ($this->list[$page + 2]->title != Text::sprintf('JLIB_HTML_PAGE_CURRENT', $page + 2)) : ?>
	<div>
		<?= $title ?>
	</div>
	<?php endif ?>

</div>
<?php endif ?>

<div class="uk-grid-small uk-flex-middle uk-child-width-auto uk-text-default uk-margin-top" uk-grid>
	<div>
		<?= Text::sprintf('JLIB_HTML_PAGE_CURRENT', '') ?>
	</div>
	<div>

		<ul class="uk-pagination">
			<?php foreach ($this->list as $index => $item) :
				$item->liClass = str_replace('active', 'uk-active', $item->liClass);
			?>
			<li class="<?= $item->liClass ?>">
				<?php if($index == $page + 1) : ?>
					<span class="<?= $item->class ?>"><?= ($index == count($this->list)) ? $item->title : $index ?></span>
				<?php else : ?>
					<a href="<?= $item->link ?>" class="<?= $item->class ?>"><?= (Text::sprintf('PLG_CONTENT_PAGEBREAK_ALL_PAGES') == $item->title) ? $item->title : $index ?></a>
				<?php endif ?>
			</li>
			<?php endforeach ?>
		</ul>

	</div>
</div>
