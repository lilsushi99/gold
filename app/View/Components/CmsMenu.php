<?php

namespace App\View\Components;

use App\Models\Menu;
use Illuminate\View\Component;
use Illuminate\View\View;

class CmsMenu extends Component
{
    public $items;

    public function __construct(public string $location = 'primary')
    {
        $menu = Menu::where('location', $location)->first();
        $this->items = $menu
            ? $menu->items()->where('is_active', true)->whereNull('parent_id')
                ->with(['page', 'children' => fn ($q) => $q->where('is_active', true)->orderBy('position'),
                    'children.page', 'children.children' => fn ($q) => $q->where('is_active', true)->orderBy('position'),
                    'children.children.page'])->get()
            : collect();
    }

    public static function href($item): string
    {
        if ($item->type === 'page' && $item->page) {
            return $item->page->path();
        }

        return cms_url($item->url ?: '#');
    }

    public function render(): View
    {
        return view('components.cms-menu');
    }
}
