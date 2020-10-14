<?php

namespace YOOtheme;

return [

    'transforms' => [

        'render' => function ($node) {

            /**
             * @var Builder  $builder
             * @var Config   $config
             * @var Metadata $metadata
             */
            list($builder, $config, $metadata) = app(Builder::class, Config::class, Metadata::class);

            $center = [];
            $markers = [];

            foreach ($node->children as $child) {

                if (empty($child->props['location'])) {
                    continue;
                }

                @list($lat, $lng) = explode(',', $child->props['location']);

                if (!is_numeric($lat) || !is_numeric($lng)) {
                    continue;
                }

                if (empty($center)) {
                    $center = ['lat' => (float) $lat, 'lng' => (float) $lng];
                }

                if (!empty($child->props['hide'])) {
                    continue;
                }

                $markers[] = [
                    'lat' => (float) $lat,
                    'lng' => (float) $lng,
                    'title' => $child->props['title'],
                    'content' => $builder->render($child, ['element' => $node->props]),
                    'show_popup' => !empty($child->props['show_popup']),
                ];
            }

            // map options
            $node->options = Arr::pick($node->props, ['type', 'zoom', 'zooming', 'dragging', 'controls', 'styler_invert_lightness', 'styler_hue', 'styler_saturation', 'styler_lightness', 'styler_gamma', 'popup_max_width']);
            $node->options['center'] = $center ?: ['lat' => 53.5503, 'lng' => 10.0006];
            $node->options['markers'] = $markers;
            $node->options['lazyload'] = $config('~theme.lazyload', false);
            $node->options = array_filter($node->options, function ($value) { return isset($value); });

            // add scripts, styles
            if ($key = $config('~theme.google_maps')) {
                $metadata->set('script:google-maps', ['src' => "https://maps.googleapis.com/maps/api/js?key={$key}", 'defer' => true]);
            } else {
                $baseUrl = 'https://cdn.jsdelivr.net/npm/leaflet@1.6.0/dist';
                $node->options['baseUrl'] = $baseUrl;
                $metadata->set('script:leaflet', ['src' => "{$baseUrl}/leaflet.js", 'defer' => true]);
            }

            $metadata->set('script:builder-map', ['src' => Path::get('./app/map.min.js'), 'defer' => true]);
        },

    ],

    'updates' => [

        '1.20.0-beta.1.1' => function ($node) {

            if (isset($node->props['maxwidth_align'])) {
                $node->props['block_align'] = $node->props['maxwidth_align'];
                unset($node->props['maxwidth_align']);
            }

        },

        '1.18.0' => function ($node) {

            if (!isset($node->props['width_breakpoint']) && @$node->props['width_max'] === false) {
                $node->props['width_breakpoint'] = true;
            }

        },

    ],

];
