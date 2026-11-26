<?php $arrow = file_get_contents(resource_path('views/components/_megamenu-arrow.blade.php')); ?>
<ul>
<?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if($item->children->isEmpty()): ?>
        <li class="<?php echo e($item->css_class); ?>"><a href="<?php echo e(\App\View\Components\CmsMenu::href($item)); ?>" <?php if($item->target === '_blank'): ?> target="_blank" rel="noopener" <?php endif; ?>><?php echo e($item->label); ?></a></li>
    <?php else: ?>
        <li class="has-dropdown <?php echo e($item->css_class); ?>">
            <a href="#"><?php echo e($item->label); ?></a>
            <div class="at-submenu submenu at-megamenu">
                <div class="row">
                    <?php
                        $grouped = $item->children->contains(fn ($c) => $c->children->isNotEmpty());
                        $columns = $grouped ? $item->children : collect([$item]);
                    ?>
                    <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-xl-4">
                            <div class="at-megamenu-box">
                                <div class="at-megamenu-title-wrap">
                                    <span class="at-megamenu-title"><?php echo e($col->label); ?></span>
                                    <?php echo $arrow; ?>

                                </div>
                                <ul>
                                    <?php $__currentLoopData = ($grouped ? $col->children : $item->children); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><a href="<?php echo e(\App\View\Components\CmsMenu::href($child)); ?>" <?php if($child->target === '_blank'): ?> target="_blank" rel="noopener" <?php endif; ?>><?php echo e($child->label); ?></a></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </li>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>
<?php /**PATH /home/claude/gold/resources/views/components/cms-menu.blade.php ENDPATH**/ ?>