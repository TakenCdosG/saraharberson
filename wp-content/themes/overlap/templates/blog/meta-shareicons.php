<div class="post-share">
	<?php
	
	$share_links = array(
		'ol-facebook' => 'http://www.facebook.com/sharer/sharer.php?u='. urlencode( get_permalink() ),
		'ol-twitter'	=> 'https://twitter.com/intent/tweet?source=webclient&amp;url='. urlencode( get_permalink() ).'&amp;text='. urlencode( get_the_title() ),
		'ol-google-plus' => 'https://plus.google.com/share?url='. urlencode( get_permalink() ),
	);

	$share_links = apply_filters('overlap_blog_share_links', $share_links);

	foreach ($share_links as $icon => $link) {
		echo sprintf('<a href="%s" target="_blank"><i class="%s"></i></a>', $link, $icon);
	}
	?>	
</div>