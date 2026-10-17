<?php

namespace core;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TextExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('truncate', [$this, 'truncateText']),
        ];
    }

    public function truncateText($text, $length = 30, $ellipsis = '...')
    {
        if (strlen($text) > $length) {
            return substr($text, 0, $length) . $ellipsis;
        }

        return $text;
    }
}