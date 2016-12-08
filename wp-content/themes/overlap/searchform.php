<form action="<?php echo esc_url( get_site_url() );?>" method="get" class="search-form">
	<input type="text" name="s" id="s" value="<?php the_search_query(); ?>" class="keyword" />
    <button type="submit" class="button"><i class="ol-search"></i></button>
</form>