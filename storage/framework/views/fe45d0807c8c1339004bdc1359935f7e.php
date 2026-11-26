
    <div class="at-search-body-overlay"></div>
    <div class="at-search-form-toggle">
        <div class="container">
            <div class="row mb-60">
                <div class="col-lg-12">
                    <div class="at-search-top d-flex justify-content-between align-items-center">
                        <div class="at-header-logo at-search-logo">
                            <a href="<?php echo e(cms_url($s['s1'])); ?>">
                                <img data-width="30" src="<?php echo e(cms_url($s['s2'])); ?>" alt="<?php echo e($s['s3']); ?>">
                                <p class="h6 fw-700 fz-24 mb-0"><?php echo e($s['s4']); ?></p>
                            </a>
                        </div>
                        <button class="at-search-close">
                            <i class="fa-light fa-xmark"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="at-search-form">
                        <form action="#">
                            <div class="at-search-form-input">
                                <input type="text" placeholder="<?php echo e($s['s5']); ?>" required>
                                <span class="at-search-focus-border"></span>
                                <button class="at-search-form-btn at-btn" type="submit">
                                    <span>
                                        <span class="text-1"><?php echo e($s['s6']); ?></span>
                                        <span class="text-2"><?php echo e($s['s7']); ?></span>
                                    </span>
                                    <i class="icon-arrow-right">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="12" viewBox="0 0 14 12" fill="none">
                                            <path d="M8.33333 1L13 5.66667M13 5.66667L8.33333 10.3333M13 5.66667H1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="12" viewBox="0 0 14 12" fill="none">
                                            <path d="M8.33333 1L13 5.66667M13 5.66667L8.33333 10.3333M13 5.66667H1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-12">
                    <div class="at-categories">
                        <h3 class="at-categories-title"><?php echo e($s['s8']); ?></h3>
                        <ul class="at-categories-list">
                            <?php $__currentLoopData = ($g['g1'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><a href="#" class="at-categories-item"><?php echo e($it['t1']); ?></a></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
<?php /**PATH /home/claude/gold/resources/views/sections/global_search.blade.php ENDPATH**/ ?>