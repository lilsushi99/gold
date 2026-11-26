<?php if(setting('gtm_id')): ?>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer',<?php echo json_encode(setting('gtm_id'), 15, 512) ?>);</script>
<?php endif; ?>
<?php if(setting('ga_id')): ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo e(setting('ga_id')); ?>"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config',<?php echo json_encode(setting('ga_id'), 15, 512) ?>);</script>
<?php endif; ?>
<?php if(setting('head_scripts')): ?><?php echo setting('head_scripts'); ?><?php endif; ?>
<?php if($seo['structured_data']): ?><script type="application/ld+json"><?php echo json_encode($seo['structured_data'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG); ?></script><?php endif; ?>
<?php /**PATH /home/claude/gold/resources/views/partials/analytics-head.blade.php ENDPATH**/ ?>