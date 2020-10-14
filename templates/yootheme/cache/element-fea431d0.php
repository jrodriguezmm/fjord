<?php // $file = C:/xampp/htdocs/fjord1/templates/yootheme/vendor/yootheme/builder/elements/gallery_item/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'gallery_item', 
  'title' => 'Item', 
  'width' => 500, 
  'placeholder' => [
    'props' => [
      'image' => $filter->apply('url', '~yootheme/theme/assets/images/element-image-placeholder.png', $file), 
      'title' => 'Title', 
      'meta' => '', 
      'content' => '', 
      'hover_image' => ''
    ]
  ], 
  'templates' => [
    'render' => $filter->apply('path', './templates/template.php', $file), 
    'content' => $filter->apply('path', './templates/content.php', $file)
  ], 
  'fields' => [
    'image' => $config->get('builder.image'), 
    'image_alt' => [
      'label' => 'Image Alt', 
      'enable' => 'image', 
      'source' => true
    ], 
    'title' => [
      'label' => 'Title', 
      'source' => true
    ], 
    'meta' => [
      'label' => 'Meta', 
      'source' => true
    ], 
    'content' => [
      'label' => 'Content', 
      'type' => 'editor', 
      'source' => true
    ], 
    'link' => $config->get('builder.link'), 
    'hover_image' => [
      'label' => 'Hover Image', 
      'description' => 'Select an optional image that appears on hover.', 
      'type' => 'image', 
      'source' => true
    ], 
    'text_color' => [
      'label' => 'Text Color', 
      'description' => 'Set light or dark color mode for text, buttons and controls.', 
      'type' => 'select', 
      'options' => [
        'None' => '', 
        'Light' => 'light', 
        'Dark' => 'dark'
      ], 
      'source' => true
    ], 
    'text_color_hover' => [
      'type' => 'checkbox', 
      'text' => 'Inverse the text color on hover'
    ], 
    'tags' => [
      'label' => 'Tags', 
      'description' => 'Enter a comma-separated list of tags, for example, <code>blue, white, black</code>.', 
      'source' => true
    ], 
    'status' => $config->get('builder.statusItem'), 
    'source' => $config->get('builder.source')
  ], 
  'fieldset' => [
    'default' => [
      'type' => 'tabs', 
      'fields' => [[
          'title' => 'Content', 
          'fields' => ['image', 'image_alt', 'title', 'meta', 'content', 'link', 'hover_image', 'text_color', 'text_color_hover', 'tags']
        ], $config->get('builder.advancedItem')]
    ]
  ]
];
