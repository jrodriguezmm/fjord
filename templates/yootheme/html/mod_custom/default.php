<?php

defined('_JEXEC') or die;

$image = $params->get('backgroundimage');

?>

<div class="uk-margin-remove-last-child custom" <?= $image ? " style=\"background-image:url({$image})\"" : '' ?>><?= $module->content ?></div>
