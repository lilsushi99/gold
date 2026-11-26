
                <div class="at-brand-area at_fade_anim container-2200">
                    <div class="carouselTicker carouselTicker-right position-relative z-1">
                        <ul class="carouselTicker__list scroll-move-right">
                            <?php $__currentLoopData = ($g['g1'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li class="carouselTicker__item">
                                <div class="brand-item dark-mode-invert">
                                    <img src="<?php echo e(cms_url($it['t1'])); ?>" alt="<?php echo e($it['t2']); ?>">
                                </div>
                            </li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
                
<?php /**PATH /home/claude/gold/resources/views/sections/home_brands.blade.php ENDPATH**/ ?>