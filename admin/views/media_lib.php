<?php if (!defined('EMLOG_ROOT')) {
	exit('error!');
} ?>

<?php foreach ($medias as $key => $value):
	$media_url = getFileUrl($value['filepath']);
	$media_name = $value['filename'];
	if (isImage($value['filepath'])) {
		$media_icon = getFileUrl($value['filepath_thum']);
	} else {
		$media_icon = "./views/images/fnone.png";
	}
	?>
    <div class="card" style="min-height: 280px;">
        <a href="<?php echo $media_url; ?>" target="_blank""><img class="card-img-top" src="<?php echo $media_icon; ?>"/></a>
        <div class="card-body">
            <p class="card-text text-muted small">
				<?php echo $media_name; ?><br>
                上传时间：<?php echo $value['addtime']; ?><br>
                文件大小：<?php echo $value['attsize']; ?>，
            </p>
            <p class="card-text d-flex justify-content-between">
				<?php if (isImage($value['filepath'])): ?>
                    <a href="javascript:insert_media_img('<?php echo $media_url; ?>', '<?php echo $media_icon; ?>')"><i class="icofont-plus"></i></a>
				<?php elseif (isVideo($value['filepath'])): ?>
                    <a href="javascript:insert_media_video('<?php echo $media_url; ?>')"><i class="icofont-plus"></i></a>
				<?php else: ?>
                    <a href="javascript:insert_media('<?php echo $media_url; ?>', '<?php echo $media_name; ?>')"><i class="icofont-plus"></i></a>
				<?php endif; ?>
            </p>
        </div>
    </div>
<?php endforeach; ?>

