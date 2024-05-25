<?php

namespace App\TwigExtensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MyFirstNEarlyCustomTwigFilter extends AbstractExtension
{

    public function getFilters()
    {
        return [
            new TwigFilter('defaultImage', [$this, 'defaultImage'])
        ];
    }

    public function defaultImage(string $path): string {
        if(strlen(trim($path)) == 0){
            return "think-network-exercise-relax-love-live-3840x2160_665549-rm-90.jpg";
        }
        return $path;
    }
    
}