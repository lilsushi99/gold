
                <div class="container-2200">
                    <div class="sec-6-home-1 mx-lg-3 mx-2 bg-black pt-100 pb-100 rounded-5 changeless overflow-hidden">
                        <div class="container">
                            <div class="row align-items-end mb-50 g-3 z-index-2">
                                <div class="col-md-8">
                                    <svg class="fill-primary mb-20" xmlns="http://www.w3.org/2000/svg" width="40" height="42" viewBox="0 0 40 42" fill="none">
                                        <path d="M16 14L12 7L16 0H24L28 7H20L16 14Z" fill="#F0460E" />
                                        <path d="M36 21L32 14H24L28 7H36L40 14L36 21Z" fill="#F0460E" />
                                        <path d="M28 35H36L40 28L36 21H28L32 28L28 35Z" fill="#F0460E" />
                                        <path d="M12 35H20L24 28L28 35L24 42H16L12 35Z" fill="#F0460E" />
                                        <path d="M4 21H12L8 14L12 7H4L0 14L4 21Z" fill="#F0460E" />
                                        <path d="M4 21L0 28L4 35H12L16 28H8L4 21Z" fill="#F0460E" />
                                    </svg>
                                    <h2 class="alt-section-title lh-1 mb-10 reveal-text text-white"><?php echo e($s['s1']); ?></h2>
                                    <p class="mg-portfolio-dec mb-50 text-white mb-0"><?php echo e($s['s2']); ?>

                                    </p>
                                </div>
                                <div class="col-lg-2 ms-auto">
                                    <div class="swiper-button-wrapper justify-content-end">
                                        <button class="swiper-btn-prev">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="27" height="27" viewBox="0 0 27 27" fill="none">
                                                <path d="M11.3481 7.47314L5.25879 13.2856L11.3481 19.0981" stroke="currentColor" stroke-width="1.66071" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M21.3124 13.2856H5.53564" stroke="currentColor" stroke-width="1.66071" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                        <button class="swiper-btn-next">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="27" height="27" viewBox="0 0 27 27" fill="none">
                                                <path d="M15.2234 7.47314L21.3126 13.2856L15.2234 19.0981" stroke="currentColor" stroke-width="1.66071" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M5.259 13.2856H21.0358" stroke="currentColor" stroke-width="1.66071" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 scroll-move-up2 z-index-2">
                                    <div class="swiper slider-testimonial">
                                        <div class="swiper-wrapper">
                                            
                        <?php $__currentLoopData = ($g['g1'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="swiper-slide">
    <div class="testimonial-cart-wrap p-xxl-5 p-lg-4 p-md-5 p-3">
        <div class="rectangular"></div>
        <div class="testimonial-top d-flex align-items-center justify-content-between">
            <div class="testimonial-top-left-img">
                <img src="<?php echo e(cms_url($it['t1'])); ?>" alt="<?php echo e($it['t2']); ?>">
            </div>
            <div class="testimonial-top-right-logo">
                <img src="<?php echo e(cms_url($it['t3'])); ?>" alt="<?php echo e($it['t4']); ?>">
            </div>
        </div>
        <div class="testimonial-bottom-wrap">
            <div class="testimonial-content">
                <p class="testimonial-content-text neutral-0 fz-font-3xl fw-400 text-truncate-4"><?php echo e($it['t5']); ?></p>
                <div class="testimonial-content-rating mt-3">
                    <div class="testimonial-content-rating-stars">
                        <?php echo $it['t6']; ?>

                    </div>
                </div>
                <div class="testimonial-content-author">
                    <h3 class="h6 testimonial-content-author-name common-white fw-600"><?php echo e($it['t7']); ?></h3>
                    <p class="testimonial-content-author-position m-0"><?php echo e($it['t8']); ?></p>
                    <p class="testimonial-content-author-company m-0"><?php echo e($it['t9']); ?></p>
                </div>
            </div>
        </div>
    </div>
</div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                                        </div>

                                    </div>
                                </div>
                                <div class="col-12 text-center pt-50 z-index-1">
                                    <a href="<?php echo e(cms_url($s['s3'])); ?>" class="at-btn bg-transparent p-relative">
                                        <img class="badge-zoon-in" src="<?php echo e(cms_url($s['s4'])); ?>" alt="<?php echo e($s['s5']); ?>">
                                        <span class="position-absolute top-50 start-50 translate-middle d-flex flex-column align-items-center justify-content-center overflow-unset">

                                            <span class="mt-2 badge-text-zoom-in overflow-unset text-wrap">
                                                <span class="fw-700 common-white text-uppercase overflow-unset"><?php echo e($s['s6']); ?> <br><?php echo e($s['s7']); ?></span>
                                            </span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                
<?php /**PATH /home/claude/gold/resources/views/sections/home_testimonials.blade.php ENDPATH**/ ?>