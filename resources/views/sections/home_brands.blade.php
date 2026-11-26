
                <div class="at-brand-area at_fade_anim container-2200">
                    <div class="carouselTicker carouselTicker-right position-relative z-1">
                        <ul class="carouselTicker__list scroll-move-right">
                            @foreach (($g['g1'] ?? []) as $it)<li class="carouselTicker__item">
                                <div class="brand-item dark-mode-invert">
                                    <img src="{{ cms_url($it['t1']) }}" alt="{{ $it['t2'] }}">
                                </div>
                            </li>@endforeach
                        </ul>
                    </div>
                </div>
                
