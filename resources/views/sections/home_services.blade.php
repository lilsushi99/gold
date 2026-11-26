
                <div class="at-service-area at-panel-pin-area pt-120 pb-120">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="at-service-subtitle-wrap at-about-border d-flex justify-content-between gap-3 mb-50">
                                    <span class="at-btn common-black text-uppercase bg-transparent mb-10 rounded-0 p-0">
                                        <span class="text-uppercase">
                                            <span class="text-1">{{ $s['s1'] }}</span>
                                            <span class="text-2">{{ $s['s2'] }}</span>
                                        </span>
                                        <i>
                                            <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                                            </svg>
                                            <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                                            </svg>
                                        </i>
                                    </span>
                                    <span class="fs-font-md fw-500 text-decoration-underline">{{ $s['s3'] }}</span>
                                </div>
                            </div>
                            <div class="col-xxl-4 col-lg-4 col-xl-4 mb-40">
                                <div class="at-service-content mr-60 mt-20">
                                    <div class="at-service-sales-wrap at-panel-pin fix p-relative">
                                        <div class="at-service-img-wrapper image-container">
                                            @foreach (($g['g1'] ?? []) as $it)<div class="hover-image">
                                                <img class="thumb" src="{{ cms_url($it['t1']) }}" alt="{{ $it['t2'] }}">
                                            </div>@endforeach
                                        </div>
                                        <h4 class="h5 fw-600 mb-0 mt-10"><span class="odometer" data-count="{{ $s['s4'] }}"></span>{{ $s['s5'] }}</h4>
                                        <span class="fz-font-lg neutral-500 fw-500">{{ $s['s6'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-7 ms-auto col-lg-8 col-xl-8 mb-40">
                                <div class="at-service-list-wrap">
                                    @foreach (($g['g2'] ?? []) as $it)<a href="{{ cms_url($it['t1']) }}">
                                        <div class="at-service-item service-item">
                                            <div class="count">
                                                <span class="number">{{ $it['t2'] }}</span>
                                            </div>

                                            <div class="content">
                                                <h1 class="title">{{ $it['t3'] }}</h1>

                                                <p class="text">
                                                    {{ $it['t4'] }} <br> {{ $it['t5'] }} </p>
                                            </div>

                                            <div class="thumb anim-zoomin">
                                                <img src="{{ cms_url($it['t6']) }}" alt="{{ $it['t7'] }}">
                                            </div>
                                        </div>
                                    </a>@endforeach
                                    <div class="at-service-btn pt-30">
                                        <a class="at-btn" href="{{ cms_url($s['s7']) }}">
                                            <span>
                                                <span class="text-1">{{ $s['s8'] }}</span>
                                                <span class="text-2">{{ $s['s9'] }}</span>
                                            </span>
                                            <i>
                                                <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                                                </svg>
                                                <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                                                </svg>
                                            </i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
