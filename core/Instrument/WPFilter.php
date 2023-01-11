<?php

namespace Instrument;

class WPFilter
{
    public static function resetURLPrefix(string $prefix)
    {
        add_filter('rest_url_prefix', function ($slug) use ($prefix) {
            return $prefix;
        });
    }
}
