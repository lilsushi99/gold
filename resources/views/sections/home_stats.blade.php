
                <div class="container-2200">
                    <div class="at-sec8-area pt-90 pb-90 bg-neutral-50 rounded-5 mx-lg-3 mx-2 mt-10">
                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex flex-wrap justify-content-lg-between justify-content-around align-items-center gap-4">
                                        @foreach (($g['g1'] ?? []) as $it)<div>
                                            <h1><span class="odometer text-nowrap" data-count="{{ $it['t1'] }}"></span>{{ $it['t2'] }}</h1>
                                            <p>{{ $it['t3'] }}</p>
                                        </div>@endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
