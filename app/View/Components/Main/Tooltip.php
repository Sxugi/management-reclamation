<?php

namespace App\View\Components\Main;

use Illuminate\View\Component;

class Tooltip extends Component
{
    public $content;
    public $maxLength;
    public $position;
    
    public function __construct($content, $maxLength = 30, $position = 'top')
    {
        $this->content = $content;
        $this->maxLength = $maxLength;
        $this->position = $position;
    }
    
    public function render()
    {
        return view('components.main.tooltip');
    }
}