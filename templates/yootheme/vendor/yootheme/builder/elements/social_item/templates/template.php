<?php

// Icon
$icon = $this->el('a', [

    'class' => [
        'el-link',
        'uk-icon-link {@!link_style}',
        'uk-icon-button {@link_style: button}',
        'uk-link-{link_style: muted|text|reset}',
    ],

    'target' => ['_blank {@link_target}'],

    'rel' => 'noreferrer',
]);

?>

<?= $icon($element, ['href' => $props['link'], 'uk-icon' => [
    "icon: {$this->e($props['link'], 'social')};",
    'width: {icon_width}; height: {icon_width}; {@!link_style: button}',
]], '') ?>
