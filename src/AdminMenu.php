<?php

namespace TwinElements\SliderBundle;

use TwinElements\AdminBundle\Menu\AdminMenuInterface;
use TwinElements\AdminBundle\Menu\MenuItem;
use TwinElements\SliderBundle\Entity\Slider;
use TwinElements\SliderBundle\Security\SliderVoter;

class AdminMenu implements AdminMenuInterface
{
    public function getItems()
    {
        return [
            MenuItem::newInstance('slider.slider', 'slider_index', [], 20, null,[SliderVoter::VIEW, new Slider()]),
        ];
    }
}
