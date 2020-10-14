<?php // $file = C:/xampp/htdocs/fjord1/templates/yootheme/vendor/yootheme/builder/elements/social_item/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'social_item', 
  'title' => 'Item', 
  'width' => 500, 
  'templates' => [
    'render' => $filter->apply('path', './templates/template.php', $file), 
    'content' => $filter->apply('path', './templates/content.php', $file)
  ], 
  'fields' => [
    'link' => [
      'label' => 'Link', 
      'attrs' => [
        'placeholder' => 'https://'
      ], 
      'source' => true, 
      'description' => 'Enter link to your social profile. A corresponding <a href="https://getuikit.com/docs/icon" target="_blank">UIkit brand icon</a> will be displayed automatically, if available. Links to email addresses and phone numbers, like mailto:info@example.com or tel:+491570156, are also supported.'
    ], 
    'status' => $config->get('builder.statusItem'), 
    'source' => $config->get('builder.source')
  ], 
  'fieldset' => [
    'default' => [
      'type' => 'tabs', 
      'fields' => [[
          'title' => 'Content', 
          'fields' => ['link']
        ], $config->get('builder.advancedItem')]
    ]
  ]
];
