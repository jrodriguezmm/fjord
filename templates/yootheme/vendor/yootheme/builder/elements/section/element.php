<?php

namespace YOOtheme;

return [

    'updates' => [

        '2.3.0-beta.1.1' => function ($node) {

            /**
             * @var Config $config
             */
            $config = app(Config::class);

            list($style) = explode(':', $config('~theme.style'));

            if (in_array($style, ['fjord'])) {
                if (@$node->props['width'] === 'default') {
                    $node->props['width'] = 'large';
                }
            }

        },

        '2.1.0-beta.2.1' => function ($node) {

            if (in_array(@$node->props['style'], ['primary', 'secondary'])) {
                @$node->props['text_color'] = '';
            }

        },

        '2.0.0-beta.5.1' => function ($node) {

            /**
             * @var Config $config
             */
            $config = app(Config::class);

            list($style) = explode(':', $config('~theme.style'));

            if (!in_array($style, ['jack-baker', 'morgan-consulting', 'vibe'])) {
                if (@$node->props['width'] === 'large') {
                    $node->props['width'] = 'xlarge';
                }
            }

            if (in_array($style, ['craft', 'district', 'florence', 'makai', 'matthew-taylor', 'pinewood-lake', 'summit', 'tomsen-brody', 'trek', 'vision', 'yard'])) {
                if (@$node->props['width'] === 'default') {
                    $node->props['width'] = 'large';
                }
            }

        },

        '1.18.10.2' => function ($node) {

            if (!empty($node->props['image']) && !empty($node->props['video'])) {
                unset($node->props['video']);
            }

        },

        '1.18.0' => function ($node) {

            if (!isset($node->props['image_effect'])) {
                $node->props['image_effect'] = @$node->props['image_fixed'] ? 'fixed' : '';
            }

            if (!isset($node->props['vertical_align']) && in_array(@$node->props['height'], ['full', 'percent', 'section'])) {
                $node->props['vertical_align'] = 'middle';
            }

            if (@$node->props['style'] === 'video') {
                $node->props['style'] = 'default';
            }

            if (@$node->props['width'] === 0) {
                $node->props['width'] = 'default';
            } elseif (@$node->props['width'] === 2) {
                $node->props['width'] = 'small';
            } elseif (@$node->props['width'] === 3) {
                $node->props['width'] = 'expand';
            }

        },

    ],

];
