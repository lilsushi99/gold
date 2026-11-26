
    <div class="at-offcanvas-area">
        <div class="at-offcanvas">
            <div class="at-offcanvas-top d-flex align-items-center justify-content-between">
                <div class="at-offcanvas-logo">
                    <a href="{{ cms_url($s['s1']) }}">
                        <img data-width="30" src="{{ cms_url($s['s2']) }}" alt="{{ $s['s3'] }}">
                        <p class="h6 fw-700 fz-24 mb-0">{{ $s['s4'] }}</p>
                    </a>
                </div>
                <div class="at-offcanvas-close-btn">
                    <button class="close-btn">
                        <svg width="37" height="38" viewBox="0 0 37 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.19141 9.80762L27.5762 28.1924" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M9.19141 28.1924L27.5762 9.80761" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="at-offcanvas-content d-none d-xl-block">
                <h3 class="at-offcanvas-title">{{ $s['s5'] }}</h3>
                <p class="fz-font-lg">{{ $s['s6'] }} </p>
            </div>
            <div class="at-offcanvas-menu d-xl-none pb-50">
                <nav></nav>
            </div>
            <div class="at-offcanvas-gallery d-none d-xl-block">
                <div class="sec-2-home-5__avatars-row d-flex gap-2">
                    @foreach (($g['g1'] ?? []) as $it)<div class="sec-2-home-5__avatar-sm at-offcanvas-gallery-img">
                        <img class="img-cover" src="{{ cms_url($it['t1']) }}" alt="{{ $it['t2'] }}">
                    </div>@endforeach
                </div>
            </div>
            <div class="at-offcanvas-contact">
                <h4 class="h5 at-offcanvas-title sm">{{ $s['s7'] }}</h4>
                <ul>
                    @foreach (($g['g2'] ?? []) as $it)<li><a class="fz-font-lg" href="{{ cms_url($it['t1']) }}">{{ $it['t2'] }}</a></li>@endforeach
                    <li>
                        <a class="fz-font-lg" href="#">
                            {{ $s['s8'] }} <br>
                            {{ $s['s9'] }}
                        </a>
                    </li>
                </ul>
            </div>
            <div class="at-offcanvas-social">
                <h3 class="at-offcanvas-title sm">{{ $s['s10'] }}</h3>
                <ul class="at-offcanvas-social__grid">
                    @foreach (($g['g3'] ?? []) as $it)<li>
                        <a href="#" class="at-offcanvas-social__link" aria-label="{{ $it['t1'] }}">
                            {!! $it['t2'] !!}
                            <span>{{ $it['t3'] }}</span>
                        </a>
                    </li>@endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="body-overlay"></div>
    

    
    <div class="at-offcanvas-2-area">
        <div class="offcanvas-bg"></div>
        <div class="at-offcanvas-2-wrapper offcanvas-menu">
            <div class="at-offcanvas-2-left">
                <div class="at-header-logo d-flex justify-content-between align-items-center mb-50">
                    <a href="{{ cms_url($s['s11']) }}">
                        <img class="dark-mode-invert" data-width="30" src="{{ cms_url($s['s12']) }}" alt="{{ $s['s13'] }}">
                        <p class="h6 fw-700 fz-24 mb-0">{{ $s['s14'] }}</p>
                    </a>
                    <span class="hamburger-close-btn">
                        <svg width="37" height="38" viewBox="0 0 37 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.19141 9.80762L27.5762 28.1924" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M9.19141 28.1924L27.5762 9.80761" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                </div>
                <div class="at-offcanvas-menu counter-row">
                    <nav></nav>
                </div>
                <div class="at-offcanvas-social">
                    <ul class="at-offcanvas-social__grid">
                        @foreach (($g['g4'] ?? []) as $it)<li>
                            <a href="#" class="at-offcanvas-social__link" aria-label="{{ $it['t1'] }}">
                                {!! $it['t2'] !!}
                                <span>{{ $it['t3'] }}</span>
                            </a>
                        </li>@endforeach
                    </ul>
                </div>
                <span class="hamburger-close-btn hamburger-mobile-close-btn d-md-none">{{ $s['s15'] }}</span>
            </div>
        </div>
    </div>
    
