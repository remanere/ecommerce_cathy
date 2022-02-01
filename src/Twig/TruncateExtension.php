<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TruncateExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('truncateText', [
                $this, 'truncateText'])
        ];
    }

    public function truncateText($value)
    {
        $truncateValue = substr($value,0,200);

        return $truncateValue . ' ...';
    }
}