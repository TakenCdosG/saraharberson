<?php
    
    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );

    $attrs = array();

    $classes = array();

    $classes[] = 'w-testimonials-slider';

    if($show_navigation == 'true') $classes[] = 'show-navigation';    

    if( !empty($el_class) ){
        $classes[] = $el_class;
    }

	$attrs['class'] = implode(' ', $classes);

    if($animation) $attrs['data-animation'] = $animation;
    if($animation_delay) $attrs['data-animation-delay'] = floatval( $animation_delay );

    $slider_attrs = array();

    $slider_attrs['class'] = 'owl-carousel';
    $slider_attrs['data-auto-play'] = ($auto_play =='true' ? 'true':'false');
    $slider_attrs['data-navigation'] = ($show_navigation =='true' ? 'true':'false');
    $slider_attrs['data-pagination'] = ($show_pagination =='true' ? 'true':'false');
    $slider_attrs['data-loop'] = 'true';
    if( !empty($transition) ) $slider_attrs['data-transition'] = $transition;

    if ( $count != '' && !is_numeric( $count ) ) $count = - 1;
        
    list( $query_args, $loop ) = vc_build_loop_query( $posts_query );

    $query_args['post_type'] = 'wyde_testimonial';
    $query_args['has_password'] = false;
    $query_args['posts_per_page'] = intval( $count );       

    $post_query = new WP_Query( $query_args );

?>
<div<?php echo overlap_get_attributes( $attrs );?>>
    <?php
    if( !empty($title) || !empty($subtitle) ){
        echo do_shortcode(sprintf('[wyde_heading title="%s" subheading="%s" style="3"]', esc_attr($title), esc_attr($subtitle) ));
    }
    ?>
    <div<?php echo overlap_get_attributes( $slider_attrs ); ?>>
        <?php while ( $post_query->have_posts() ) : $post_query->the_post(); ?>
        <div class="w-testimonial">
            <?php
                $image_size = 'thumbnail';
                $cover_id = get_post_thumbnail_id(get_the_ID());
                $item_class = ' no-cover';
                if( $cover_id ) $item_class = ' has-cover';
            ?>
            <div class="w-customer<?php echo esc_attr($item_class); ?>">
                <?php if( $cover_id ){ ?>
                <div class="w-border">
                    <?php echo wp_get_attachment_image($cover_id, $image_size); ?>
                </div>
                <?php } ?>
                <div class="w-header">
                    <h4><?php echo esc_html( get_the_title() ); ?></h4>
                    <h6>
                    <?php
                    $position =  get_post_meta( get_the_ID(), '_w_testimonial_position', true );
                    $company =  get_post_meta( get_the_ID(), '_w_testimonial_company', true );
                    $website =  get_post_meta( get_the_ID(), '_w_testimonial_website', true );
                    if( !empty($position) ){  
                    ?> 
                        <span><?php echo esc_html( $position );?></span> / 
                    <?php 
                    }             
                    if( !empty($company) ){
                        if( !empty($website) ){
                            echo '<a href="'.esc_url( $website ).'" target="_blank">';
                        } 
                        echo esc_html( $company );
                        if( !empty($website) ){
                            echo '</a>';
                        } 
                    }
                    ?>
                    </h6>
                </div>
            </div>
            <div class="w-content">
                <?php echo  wp_kses_post( get_post_meta( get_the_ID(), '_w_testimonial_detail', true ) ); ?>
            </div>
        </div>
        <?php endwhile; ?>    
        <?php wp_reset_postdata(); ?>
    </div>
</div>