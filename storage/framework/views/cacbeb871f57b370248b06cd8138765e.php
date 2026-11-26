
                <div class="mg-portfolio-area pt-145 pb-65">
                    <div class="container">
                        <div class="row">
                            <div class="col-xxl-4 col-lg-4">
                                <div class="mg-portfolio-title-wrap mg-portfolio-pin mb-30">
                                    <svg class="fill-primary mb-10" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" fill="none">
                                        <path d="M17 18L30 5H43V18L30 31V18H17Z" fill="#F0460E" />
                                        <path d="M30 31H43V44H30V31Z" fill="#F0460E" />
                                        <path d="M17 18L4 31V44H17L30 31H17V18Z" fill="#F0460E" />
                                        <path d="M17 18H4V5H17V18Z" fill="#F0460E" />
                                    </svg>
                                    <h2 class="alt-section-title lh-1 mb-30 reveal-text"><?php echo e($s['s1']); ?></h2>
                                    <div class="at_fade_anim" data-delay=".3">
                                        <p class="mg-portfolio-dec mb-50"><?php echo e($s['s2']); ?></p>
                                    </div>
                                    <div class="at-btn-group at_fade_anim" data-delay=".4" data-fade-from="bottom" data-ease="bounce">
                                        <a class="at-btn-circle" href="<?php echo e(cms_url($s['s3'])); ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 16 15" fill="none">
                                                <path d="M0.0001297 8.99993L0 3.00407e-05L2 0L2.0001 6.99993L12.1719 7.00003L8.22224 3.05027L9.63644 1.63606L16.0003 8.00003L9.63644 14.364L8.22224 12.9497L12.1719 9.00003L0.0001297 8.99993Z" fill="currentColor" />
                                            </svg>
                                        </a>
                                        <a class="at-btn z-index-1" href="<?php echo e(cms_url($s['s4'])); ?>"><?php echo e($s['s5']); ?></a>
                                        <a class="at-btn-circle" href="<?php echo e(cms_url($s['s6'])); ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 16 15" fill="none">
                                                <path d="M0.0001297 8.99993L0 3.00407e-05L2 0L2.0001 6.99993L12.1719 7.00003L8.22224 3.05027L9.63644 1.63606L16.0003 8.00003L9.63644 14.364L8.22224 12.9497L12.1719 9.00003L0.0001297 8.99993Z" fill="currentColor" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7 ms-auto">
                                <div class="mg-portfolio-item-wrap ml-130 mb-40">
                                    
                    <?php $__currentLoopData = ($g['g1'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="mg-portfolio-item anim-zoomin-wrap mb-55" data-category="">
    <div class="mg-portfolio-thumb  anim-zoomin not-hide-cursor">
        <a class="cursor-hide" href="<?php echo e(cms_url($it['t1'])); ?>">
            <img data-speed=".8" class="w-100" src="<?php echo e(cms_url($it['t2'])); ?>" alt="<?php echo e($it['t3']); ?>" />
        </a>
    </div>
    <div class="mg-portfolio-content cs-portfolio-content d-flex align-items-center flex-wrap flex-md-nowrap justify-content-between">
        <div class="w-md-75">
            <h3 class="h5 cs-portfolio-title at-title-anim fix mr-20 at-ff-sequel-semi-bold">
                <a href="<?php echo e(cms_url($it['t4'])); ?>" class="at-title-text"><?php echo e($it['t5']); ?></a>
            </h3>
            <p class="fz-font-lg neutral-500"><?php echo e($it['t6']); ?></p>
        </div>
        <div class="cs-portfolio-tag">
            <ul class="d-flex justify-content-md-end flex-wrap text-nowrap">
                <li><a href="#"><?php echo e($it['t7']); ?></a></li><li><a href="#"><?php echo e($it['t8']); ?></a></li><li><a href="#"><?php echo e($it['t9']); ?></a></li>
            </ul>
        </div>
    </div>
</div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<div class="mg-portfolio-item anim-zoomin-wrap mb-55" data-category="">
    <div class="mg-portfolio-thumb  anim-zoomin not-hide-cursor">
        <a class="cursor-hide" href="<?php echo e(cms_url($s['s7'])); ?>">
            <img data-speed=".8" class="w-100" src="<?php echo e(cms_url($s['s8'])); ?>" alt="<?php echo e($s['s9']); ?>" />
        </a>
    </div>
    <div class="mg-portfolio-content cs-portfolio-content d-flex align-items-center flex-wrap flex-md-nowrap justify-content-between">
        <div class="w-md-75">
            <h3 class="h5 cs-portfolio-title at-title-anim fix mr-20 at-ff-sequel-semi-bold">
                <a href="<?php echo e(cms_url($s['s10'])); ?>" class="at-title-text"><?php echo e($s['s11']); ?></a>
            </h3>
            <p class="fz-font-lg neutral-500"><?php echo e($s['s12']); ?></p>
        </div>
        <div class="cs-portfolio-tag">
            <ul class="d-flex justify-content-md-end flex-wrap text-nowrap">
                <?php $__currentLoopData = ($g['g2'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><a href="#"><?php echo e($it['t1']); ?></a></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    </div>
</div>

                    

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
<?php /**PATH /home/claude/gold/resources/views/sections/home_portfolio.blade.php ENDPATH**/ ?>