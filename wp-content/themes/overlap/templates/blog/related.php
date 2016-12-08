<?php

    $tags = get_the_tags();
    
    if ( is_array($tags) ) {
        
        $tag_ids = array();

        foreach($tags as $tag){
            $tag_ids[] = $tag->term_id;
        } 

        $columns = intval( overlap_get_option('blog_single_related_posts') );
    
        $args=array(
            'tag__in' => $tag_ids,
            'post__not_in' => array( get_the_ID() ),
            'posts_per_page'    => $columns,
            'ignore_sticky_posts'   => 1
        );

        $post_query = new WP_Query( $args );
         
        if( $post_query->have_posts() ) {

        
        $col_name = overlap_get_column_name($columns);
        
        
?>
<div class="related-posts">
    <h3><?php echo esc_html( overlap_get_option('blog_single_related_title') );?></h3>
    <ul class="row">
    <?php
    $image_size = 'overlap-medium';

    while( $post_query->have_posts() ) :

	    $post_query->the_post();
        
        $cover_id = get_post_thumbnail_id(get_the_ID());

	?>
	    <li class="col <?php echo esc_attr($col_name);?>">
           <span class="thumb">
            <a href="<?php echo esc_url(get_permalink()); ?>" title="<?php echo esc_attr(get_the_title()); ?>">
            <?php 
            if( $cover_id ) {
                echo wp_get_attachment_image($cover_id, $image_size);
            }else{
                $cover_image = overlap_get_option('portfolio_placeholder_image');
                if( !empty($cover_image) ) echo '<img src="'. esc_url( $cover_image['url'] ) .'" alt="'. esc_attr(get_the_title()) .'" />';
            }
            ?>
            </a>
            </span>
            <h4>
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h4>
            <span class="date"><?php echo get_the_date(); ?></span>
		</li>
	<?php endwhile; ?>
    </ul>
</div>
<?php
	    }
	wp_reset_postdata();
    }