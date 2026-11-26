@php $arrow = file_get_contents(resource_path('views/components/_megamenu-arrow.blade.php')); @endphp
<ul>
@foreach ($items as $item)
    @if ($item->children->isEmpty())
        <li class="{{ $item->css_class }}"><a href="{{ \App\View\Components\CmsMenu::href($item) }}" @if ($item->target === '_blank') target="_blank" rel="noopener" @endif>{{ $item->label }}</a></li>
    @else
        <li class="has-dropdown {{ $item->css_class }}">
            <a href="#">{{ $item->label }}</a>
            <div class="at-submenu submenu at-megamenu">
                <div class="row">
                    @php
                        $grouped = $item->children->contains(fn ($c) => $c->children->isNotEmpty());
                        $columns = $grouped ? $item->children : collect([$item]);
                    @endphp
                    @foreach ($columns as $col)
                        <div class="col-xl-4">
                            <div class="at-megamenu-box">
                                <div class="at-megamenu-title-wrap">
                                    <span class="at-megamenu-title">{{ $col->label }}</span>
                                    {!! $arrow !!}
                                </div>
                                <ul>
                                    @foreach (($grouped ? $col->children : $item->children) as $child)
                                        <li><a href="{{ \App\View\Components\CmsMenu::href($child) }}" @if ($child->target === '_blank') target="_blank" rel="noopener" @endif>{{ $child->label }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </li>
    @endif
@endforeach
</ul>
