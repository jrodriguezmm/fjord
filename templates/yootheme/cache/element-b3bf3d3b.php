<?php // $file = C:/xampp/htdocs/fjord1/templates/yootheme/vendor/yootheme/builder/elements/map_marker/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'map_marker', 
  'title' => 'Marker', 
  'width' => 500, 
  'placeholder' => [
    'props' => [
      'location' => '53.5503, 10.0006'
    ]
  ], 
  'templates' => [
    'render' => $filter->apply('path', './templates/template.php', $file), 
    'content' => $filter->apply('path', './templates/content.php', $file)
  ], 
  'fields' => [
    'location' => [
      'label' => 'Location', 
      'type' => 'location', 
      'source' => true
    ], 
    'title' => [
      'label' => 'Title', 
      'source' => true
    ], 
    'content' => [
      'label' => 'Content', 
      'description' => 'Click the marker to open the popup content.', 
      'type' => 'editor', 
      'source' => true
    ], 
    'hide' => [
      'label' => 'Settings', 
      'type' => 'checkbox', 
      'text' => 'Hide marker'
    ], 
    'show_popup' => [
      'type' => 'checkbox', 
      'text' => 'Show popup on load'
    ], 
    'status' => $config->get('builder.statusItem'), 
    'source' => $config->get('builder.source')
  ], 
  'fieldset' => [
    'default' => [
      'type' => 'tabs', 
      'fields' => [[
          'title' => 'Content', 
          'fields' => ['location', 'title', 'content', 'hide', 'show_popup']
        ], $config->get('builder.advancedItem')]
    ]
  ]
];
