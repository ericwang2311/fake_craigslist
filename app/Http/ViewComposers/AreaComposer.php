<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

class AreaComposer
{
    private $area;

    public function compose(View $view)
    {

    // us in config

        if (!$this->area) {
            $this->area = \App\Area::where('slug', session()->get('area', config()->get('fresh.defaults.area')))->first();//us is the default area
        }

        return $view->with('area', $this->area); // shares area with every single view
    }
}
