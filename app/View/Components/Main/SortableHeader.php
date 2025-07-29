<?php

namespace App\View\Components\Main;

use Illuminate\View\Component;
use Illuminate\View\View;

class SortableHeader extends Component
{
    public $column;
    public $title;
    public $currentSort;
    public $currentDirection;
    public $sortUrl;
    public $isActive;

    public function __construct($column, $title)
    {
        $this->column = $column;
        $this->title = $title;
        $this->currentSort = request('tableSortColumn');
        $this->currentDirection = request('tableSortDirection');
        $this->isActive = ($this->currentSort === $this->column);
        $this->sortUrl = $this->getSortUrl();
    }

    private function getSortUrl()
    {
        if ($this->currentSort === $this->column) {
            if ($this->currentDirection === 'desc') {
                return request()->fullUrlWithQuery([
                    'tableSortColumn' => $this->column,
                    'tableSortDirection' => 'asc'
                ]);
            } elseif ($this->currentDirection === 'asc') {
                $params = request()->except(['tableSortColumn', 'tableSortDirection']);
                return url()->current() . ($params ? '?' . http_build_query($params) : '');
            }
        }

        return request()->fullUrlWithQuery([
            'tableSortColumn' => $this->column,
            'tableSortDirection' => 'desc'
        ]);
    }

    public function render(): View
    {
        return view('components.main.sortable-header');
    }
}