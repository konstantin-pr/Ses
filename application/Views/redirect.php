<?php 
use Application\H;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">
<html><head>
	<?php echo H::og($openGraphTags); ?>
    <meta property="fb:app_id" content="<?php echo H::vars()->fb->appId; ?>" />
    <meta property="og:url" content="<?php echo H::vars()->fb->canvasUrl; ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Tide+ ValenTide: Give a free bottle of Tide+ to someone special." />
    <meta property="og:description" content="Give that special someone more of what they love: the gift of clean. Just guess your friend's favorite Tide+, and if they match it, they could get a free 10 oz bottle, while supplies last." />
    <meta property="og:image" content="<?php echo H::u('img/ValenTide_Post_1200x627_itsBack.jpg');?>">
<script> top.location.href = '<?php echo $redirectUrl; ?>'; </script>
</head></html>
