<footer class="container-2200">
                
                <div class="at-footer-area mp-footer-style pt-60 bg-neutral-950 rounded-5 mx-lg-3 mx-2 changeless">
                    <div class="container">
                        <div class="row g-5">
                            <div class="col-xxl-4 col-lg-6">
                                <div class="d-flex flex-wrap align-items-start gap-4">
                                    <img class="mt-5" data-width="50" src="{{ cms_url($s['s1']) }}" alt="{{ $s['s2'] }}">
                                    <div>
                                        <h3 class="h4 text-white reveal-text">{{ $s['s3'] }} <br> {{ $s['s4'] }}</h3>
                                        <p class="mb-0">
                                            {{ $s['s5'] }} <br>
                                            {{ $s['s6'] }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-3 col-lg-5 col-md-8 ms-lg-auto text-lg-end">
                                <div class="at-footer-title-wrap">
                                    <h4 class="h6 text-white">{{ $s['s7'] }}</h4>
                                    <h5 class="h4 text-white text-decoration-underline text-wrap">{{ $s['s8'] }}</h5>
                                    <div class="at-footer-widget at-footer-link pt-50">
                                        <div class="at-hero-social justify-content-lg-end">
                                            @foreach (($g['g1'] ?? []) as $it)<a href="#">
                                                {{ $it['t1'] }}
                                                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="10" viewBox="0 0 9 10" fill="none">
                                                    <path d="M5.62494 9.99994L0.562517 10L0.5625 8.75003L4.49994 8.74996L4.5 2.39273L2.27828 4.86124L1.48278 3.97739L5.0625 0L8.64225 3.97739L7.84676 4.86124L5.625 2.3927L5.62494 9.99994Z" fill="currentColor" />
                                                </svg>
                                            </a>@endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="at-about pt-100 pb-60 p-relative">
                            <div class="row align-items-end g-5">
                                <div class="col-xxl-3 col-lg-4 col-md-6">
                                    <div class="at-footer-widget alt-footer-link-item-wrap row">
                                        <span class="d-block fz-font-label neutral-0 opacity-50 text-uppercase mb-3">{{ $s['s9'] }}</span>
                                        <div class="alt-footer-link-item col-6">
                                            <ul>
                                                @foreach (($g['g2'] ?? []) as $it)<li class="mb-15"><a href="{{ cms_url($it['t1']) }}">{{ $it['t2'] }}</a></li>@endforeach
                                            </ul>
                                        </div>
                                        <div class="alt-footer-link-item col-6">
                                            <ul>
                                                @foreach (($g['g3'] ?? []) as $it)<li class="mb-15"><a href="{{ cms_url($it['t1']) }}">{{ $it['t2'] }}</a></li>@endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-9 col-lg-8 col-12 text-lg-end">
                                    <h1 class="fz-150 common-white mb-0 text-scale-anim">{{ $s['s10'] }}<sup class="fz-80 fw-400">{{ $s['s11'] }}</sup></h1>
                                </div>
                            </div>
                        </div>
                        <div class="at-footer-copyright-area at-about-border pt-40 pb-40">
                            <div class="row align-items-center g-3">
                                <div class="col-lg-2">
                                    <div class="at-footer-copyright-wrap text">
                                        <span class="at-footer-copyright">{{ $s['s12'] }} </span>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <ul class="d-flex flex-wrap gap-lg-4 gap-3 ps-3">
                                        @foreach (($g['g4'] ?? []) as $it)<li>
                                            <div class="at-btn border-0 ps-2 pe-2 py-0 common-white opacity-50 bg-transparent rounded-0">
                                                <span>
                                                    <span class="text-1">{{ $it['t1'] }}</span>
                                                    <span class="text-2">{{ $it['t2'] }}</span>
                                                </span>
                                                <i>
                                                    <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                                                    </svg>
                                                    <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                                                    </svg>
                                                </i>
                                            </div>
                                        </li>@endforeach
                                    </ul>
                                </div>
                                <div class="col-lg-2">
                                    <div class="at-footer-copyright-wrap text-lg-end">
                                        <span class="at-footer-copyright">{{ $s['s13'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-neutral-0 pt-10"></div>
                
            </footer>
