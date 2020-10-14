<?php // $file = C:/xampp/htdocs/fjord1/templates/yootheme/vendor/yootheme/builder/elements/accordion_item/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'accordion_item', 
  'title' => 'Item', 
  'width' => 500, 
  'placeholder' => [
    'props' => [
      'title' => 'Title', 
      'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 
      'image' => ''
    ]
  ], 
  'templates' => [
    'render' => $filter->apply('path', './templates/template.php', $file), 
    'content' => $filter->apply('path', './templates/content.php', $file)
  ], 
  'fields' => [
    'title' => [
      'label' => 'Title', 
      'source' => true
    ], 
    'content' => [
      'label' => 'Content', 
      'type' => 'editor', 
      'source' => true
    ], 
    'image' => $config->get('builder.image'), 
    'image_alt' => [
      'label' => 'Image Alt', 
      'source' => true, 
      'enable' => 'image'
    ], 
    'link' => $config->get('builder.link'), 
    'status' => $config->get('builder.statusItem'), 
    'source' => $config->get('builder.source')
  ], 
  'fieldset' => [
    'default' => [
      'type' => 'tabs', 
      'fields' => [[
          'title' => 'Content', 
          'fields' => ['title', 'content', 'image', 'image_alt', 'link']
        ], $config->get('builder.advancedItem')]
    ]
  ]
];
