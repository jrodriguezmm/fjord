<?php

$links = array_filter(!empty($props['links']) ? (array) $props['links'] : []);

$el = $this->el('div');

// Grid
$grid = $this->el('div', [

    'class' => [
        'uk-child-width-auto',
        'uk-grid-{gap}',
        'uk-flex-{text_align}[@{text_align_breakpoint} [uk-flex-{text_align_fallback}]]',
    ],

    'uk-grid' => true,
]);

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

<?= $el($props, $attrs) ?>
    <?= $grid($props) ?>

    <?php foreach ($children as $child) : ?>
        <div><?= $builder->render($child, ['element' => $props]) ?></div>
    <?php endforeach ?>

    <?= $grid->end() ?>
<?= $el->end() ?>
