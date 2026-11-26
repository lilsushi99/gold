<title><?php echo e($seo['title']); ?></title>
<?php if($seo['description']): ?><meta name="description" content="<?php echo e($seo['description']); ?>"><?php endif; ?>
<?php if($seo['keywords']): ?><meta name="keywords" content="<?php echo e($seo['keywords']); ?>"><?php endif; ?>
<?php if($seo['author']): ?><meta name="author" content="<?php echo e($seo['author']); ?>"><?php endif; ?>
<meta name="robots" content="<?php echo e($preview ?? false ? 'noindex,nofollow' : $seo['robots']); ?>">
<link rel="canonical" href="<?php echo e($seo['canonical']); ?>">
<meta property="og:title" content="<?php echo e($seo['og']['title']); ?>">
<?php if($seo['og']['description']): ?><meta property="og:description" content="<?php echo e($seo['og']['description']); ?>"><?php endif; ?>
<meta property="og:type" content="<?php echo e($seo['og']['type']); ?>">
<meta property="og:url" content="<?php echo e($seo['og']['url']); ?>">
<meta property="og:site_name" content="<?php echo e($seo['og']['site_name']); ?>">
<?php if($seo['og']['image']): ?><meta property="og:image" content="<?php echo e($seo['og']['image']); ?>"><?php endif; ?>
<meta name="twitter:card" content="<?php echo e($seo['twitter']['card']); ?>">
<meta name="twitter:title" content="<?php echo e($seo['twitter']['title']); ?>">
<?php if($seo['twitter']['description']): ?><meta name="twitter:description" content="<?php echo e($seo['twitter']['description']); ?>"><?php endif; ?>
<?php if($seo['twitter']['image']): ?><meta name="twitter:image" content="<?php echo e($seo['twitter']['image']); ?>"><?php endif; ?>
<?php if($seo['twitter']['site']): ?><meta name="twitter:site" content="<?php echo e($seo['twitter']['site']); ?>"><?php endif; ?>
<?php if(setting('gsc_verification')): ?><meta name="google-site-verification" content="<?php echo e(setting('gsc_verification')); ?>"><?php endif; ?>
<?php if(setting('bing_verification')): ?><meta name="msvalidate.01" content="<?php echo e(setting('bing_verification')); ?>"><?php endif; ?>
<?php /**PATH /home/claude/gold/resources/views/partials/seo-head.blade.php ENDPATH**/ ?>