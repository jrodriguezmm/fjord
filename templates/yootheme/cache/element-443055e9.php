<?php // $file = C:/xampp/htdocs/fjord1/templates/yootheme/vendor/yootheme/builder-joomla/elements/module/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'module', 
  'title' => 'Module', 
  'group' => 'system', 
  'icon' => $filter->apply('url', 'images/icon.svg', $file), 
  'iconSmall' => $filter->apply('url', 'images/iconSmall.svg', $file), 
  'element' => true, 
  'width' => 500, 
  'templates' => [
    'render' => $filter->apply('path', './templates/template.php', $file)
  ], 
  'fields' => [
    'module' => [
      'type' => 'select-module', 
      'label' => 'Module', 
      'description' => 'Any Joomla module can be displayed in your custom layout.', 
      'default' => ''
    ], 
    'style' => [
      'type' => 'select', 
      'label' => 'Style', 
      'description' => 'Select a panel style.', 
      'options' => [
        'None' => '', 
        'Card Default' => 'card-default', 
        'Card Primary' => 'card-primary', 
        'Card Secondary' => 'card-secondary', 
        'Card Hover' => 'card-hover'
      ]
    ], 
    'title_style' => [
      'type' => 'select', 
      'label' => 'Style', 
      'description' => 'Title styles differ in font-size but may also come with a predefined color, size and font.', 
      'options' => [
        'None' => '', 
        '2Xlarge' => 'heading-2xlarge', 
        'XLarge' => 'heading-xlarge', 
        'Large' => 'heading-large', 
        'Medium' => 'heading-medium', 
        'Small' => 'heading-small', 
        'H1' => 'h1', 
        'H2' => 'h2', 
        'H3' => 'h3', 
        'H4' => 'h4', 
        'H5' => 'h5', 
        'H6' => 'h6'
      ]
    ], 
    'title_decoration' => [
      'type' => 'select', 
      'label' => 'Decoration', 
      'description' => 'Decorate the title with a divider, bullet or a line that is vertically centered to the heading.', 
      'options' => [
        'None' => '', 
        'Divider' => 'divider', 
        'Bullet' => 'bullet', 
        'Line' => 'line'
      ]
    ], 
    'title_font_family' => [
      'label' => 'Font Family', 
      'description' => 'Select an alternative font family.', 
      'type' => 'select', 
      'options' => [
        'None' => '', 
        'Default' => 'default', 
        'Primary' => 'primary', 
        'Secondary' => 'secondary', 
        'Tertiary' => 'tertiary'
      ]
    ], 
    'title_color' => [
      'type' => 'select', 
      'label' => 'Color', 
      'description' => 'Select the text color. If the background option is selected, styles that don\'t apply a background image use the primary color instead.', 
      'options' => [
        'None' => '', 
        'Muted' => 'muted', 
        'Primary' => 'primary', 
        'Success' => 'success', 
        'Warning' => 'warning', 
        'Danger' => 'danger', 
        'Background' => 'background'
      ]
    ], 
    'list_style' => [
      'label' => 'List Style', 
      'description' => 'Select the list style.', 
      'type' => 'select', 
      'options' => [
        'None' => '', 
        'Divider' => 'divider'
      ], 
      'enable' => '$match(type, \'articles_archive|articles_categories|articles_latest|articles_popular|tags_popular|tags_similar\')'
    ], 
    'link_style' => [
      'label' => 'Link Style', 
      'description' => 'Select the link style.', 
      'type' => 'select', 
      'options' => [
        'None' => '', 
        'Muted' => 'muted'
      ], 
      'enable' => '$match(type, \'articles_archive|articles_categories|articles_latest|articles_popular|tags_popular|tags_similar\')'
    ], 
    'menu_style' => [
      'label' => 'Style', 
      'description' => 'Select the menu style', 
      'type' => 'select', 
      'default' => 'nav', 
      'options' => [
        'Nav' => 'nav', 
        'Subnav' => 'subnav'
      ], 
      'enable' => '$match(type, \'menu\')'
    ], 
    'position' => $config->get('builder.position'), 
    'position_left' => $config->get('builder.position_left'), 
    'position_right' => $config->get('builder.position_right'), 
    'position_top' => $config->get('builder.position_top'), 
    'position_bottom' => $config->get('builder.position_bottom'), 
    'position_z_index' => $config->get('builder.position_z_index'), 
    'margin' => $config->get('builder.margin'), 
    'margin_remove_top' => $config->get('builder.margin_remove_top'), 
    'margin_remove_bottom' => $config->get('builder.margin_remove_bottom'), 
    'maxwidth' => $config->get('builder.maxwidth'), 
    'maxwidth_breakpoint' => $config->get('builder.maxwidth_breakpoint'), 
    'block_align' => $config->get('builder.block_align'), 
    'block_align_breakpoint' => $config->get('builder.block_align_breakpoint'), 
    'block_align_fallback' => $config->get('builder.block_align_fallback'), 
    'text_align' => $config->get('builder.text_align_justify'), 
    'text_align_breakpoint' => $config->get('builder.text_align_breakpoint'), 
    'text_align_fallback' => $config->get('builder.text_align_justify_fallback'), 
    'animation' => $config->get('builder.animation'), 
    '_parallax_button' => $config->get('builder._parallax_button'), 
    'visibility' => $config->get('builder.visibility'), 
    'name' => $config->get('builder.name'), 
    'status' => $config->get('builder.status'), 
    'id' => $config->get('builder.id'), 
    'class' => $config->get('builder.cls'), 
    'attributes' => $config->get('builder.attrs'), 
    'css' => [
      'label' => 'CSS', 
      'description' => 'Enter your own custom CSS. The following selectors will be prefixed automatically for this element: <code>.el-element</code>, <code>.el-title</code>', 
      'type' => 'editor', 
      'editor' => 'code', 
      'mode' => 'css', 
      'attrs' => [
        'debounce' => 500
      ]
    ]
  ], 
  'fieldset' => [
    'default' => [
      'type' => 'tabs', 
      'fields' => [[
          'title' => 'Content', 
          'fields' => ['module']
        ], [
          'title' => 'Settings', 
          'fields' => [[
              'type' => 'group', 
              'label' => 'Panel', 
              'divider' => true, 
              'fields' => ['style']
            ], [
              'type' => 'group', 
              'label' => 'Title', 
              'divider' => true, 
              'fields' => ['title_style', 'title_decoration', 'title_font_family', 'title_color']
            ], [
              'type' => 'group', 
              'label' => 'List', 
              'divider' => true, 
              'fields' => ['list_style', 'link_style']
            ], [
              'type' => 'group', 
              'label' => 'Menu', 
              'divider' => true, 
              'fields' => ['menu_style']
            ], [
              'type' => 'group', 
              'label' => 'General', 
              'fields' => ['position', 'position_left', 'position_right', 'position_top', 'position_bottom', 'position_z_index', 'margin', 'margin_remove_top', 'margin_remove_bottom', 'maxwidth', 'maxwidth_breakpoint', 'block_align', 'block_align_breakpoint', 'block_align_fallback', 'text_align', 'text_align_breakpoint', 'text_align_fallback', 'animation', '_parallax_button', 'visibility']
            ]]
        ], [
          'title' => 'Advanced', 
          'fields' => ['name', 'status', 'id', 'class', 'css']
        ]]
    ]
  ]
];
