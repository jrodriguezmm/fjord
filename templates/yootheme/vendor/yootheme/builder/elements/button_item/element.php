<?php

return [

    'transforms' => [

        'render' => function ($node) {

            // Don't render element if content fields are empty
            return $node->props['link'] && ($node->props['content'] || $node->props['icon']);

        },

    ],

    'updates' => [

        '1.18.0' => function ($node) {

            if (@$node->props['link_target'] === true) {
                $node->props['link_target'] = 'blank';
            }

            if (@$node->props['button_style'] === 'muted') {
                $node->props['button_style'] = 'link-muted';
            }

        },

    ],

];
