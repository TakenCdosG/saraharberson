<?php
    
    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
    extract( $atts );
    
    $attrs = array();
    
    $classes = array();
    
    $classes[] = 'w-team-slider';
    
    $layout_attrs = array();

    $col_name = '';

    if( !empty($layout) ){   

        $layout_attrs['class'] = 'w-grid-layout';
        $layout_attrs['class'] .= ' grid-'.intval( $columns ).'-cols clear';
        
        $col_name = ' '.  overlap_get_column_name($columns); 
        

    }else{       

        $classes[] = 'grid-'.$visible_items.'-cols';
        $layout_attrs['class'] = 'owl-carousel';        
        $layout_attrs['data-items'] = intval( $visible_items );
        $layout_attrs['data-navigation'] = ($show_navigation =='true' ? 'true':'false');
        $layout_attrs['data-pagination'] = ($show_pagination =='true' ? 'true':'false');
        $layout_attrs['data-loop'] = ($slide_loop =='true' ? 'true':'false');
        if( $auto_play == 'true' ){
            $layout_attrs['data-auto-play'] = 'true';
            $layout_attrs['data-speed'] = $speed;
        }else{
            $layout_attrs['data-auto-play'] = 'false';
        }
        if( $visible_items == '1' && !empty($transition) ) $layout_attrs['data-transition'] = $transition;

    }

    if( !empty($el_class) ){
        $classes[] = $el_class;
    }
    
    $attrs['class'] = implode(' ', $classes);
    
    if($animation) $attrs['data-animation'] = $animation;
    if($animation_delay) $attrs['data-animation-delay'] = floatval( $animation_delay );

    if ( $count != '' && ! is_numeric( $count ) ) $count = - 1;
    
    list( $query_args, $loop ) = vc_build_loop_query( $posts_query );

    $query_args['post_type'] = 'wyde_team_member';
    $query_args['has_password'] = false;
    $query_args['posts_per_page'] = intval( $count );       
    
    $post_query = new WP_Query( $query_args );
    
    $image_size = 'overlap-portrait-medium';

    $popup_id = wp_rand(0, 100);
    
?>
<div<?php echo overlap_get_attributes( $attrs );?>>
    <div <?php echo overlap_get_attributes( $layout_attrs );?>>
        <?php while ( $post_query->have_posts() ) : $post_query->the_post(); ?>
        <div class="team-member<?php echo esc_attr($col_name);?>">
            <?php

            $name = get_the_title();
            $position = get_post_meta( get_the_ID(), '_w_member_position', true );
            $cover_id = get_post_thumbnail_id(get_the_ID());
            $image = '';
            if( $cover_id ) $image = wp_get_attachment_image($cover_id, $image_size);  
            if( $image ){                
                echo sprintf('<a href="#team-member-%s-%s" class="cover-image">%s</a>', $popup_id, get_the_ID(), $image );
            }
            ob_start();
                
            $email =  get_post_meta( get_the_ID(), '_w_member_email', true );
            $website =  get_post_meta( get_the_ID(), '_w_member_website', true );

            if($email){
                echo '<a href="mailto:'.sanitize_email( $email ).'" title="'.esc_html__('Email', 'overlap').'" target="_blank" class="tooltip-item"><i class="ol-mail"></i></a>';
            }

            if($website){
                echo '<a href="'.esc_url( $website ).'" title="'.esc_html__('Website', 'overlap').'" target="_blank" class="tooltip-item"><i class="ol-globe"></i></a>';
            }

            $socials_icons = overlap_get_social_icons();
            $socials = get_post_meta( get_the_ID(), '_w_member_socials', true );

            foreach ( (array) $socials as $key => $entry ) {
                if ( isset( $entry['url'] ) && !empty( $entry['url'] ) ){
                    echo sprintf('<a href="%s" title="%s" target="_blank" class="tooltip-item"><i class="%s"></i></a>', esc_url( $entry['url'] ), esc_attr( $entry['social'] ), esc_attr( array_search($entry['social'], $socials_icons) ));
                }
            }

            $social_content = ob_get_clean();
       
            ?>
            <?php if( $hide_member_name != 'true'): ?>
            <div class="member-name"<?php echo !empty($color)? ' style="color:'.$color.';"':''; ?>>
                <h3>
                    <?php 
                    if( $layout == 'grid' ){
                        echo sprintf('<span>%s</span>', $name[0]);
                    }
                    ?>             
                    <?php echo esc_html( $name ); ?>
                </h3>
                <h4><?php echo esc_html( $position ); ?></h4>
                <?php echo '<p class="social-link">'. $social_content .'</p>'; ?>
            </div>
            <?php endif; ?>
            <div id="team-member-<?php echo esc_attr($popup_id);?>-<?php echo esc_attr(get_the_ID());?>" class="popup-content">
                <div class="member-content clear">
                    <a href="#" class="w-close-button"></a>
                    <?php 
                    if( $image ){
                        echo '<div class="member-image">'. $image .'</div>';
                    }
                    ?>
                    <div class="member-detail">
                        <h3><?php echo esc_html( $name ); ?></h3>
                        <h4 class="member-meta"><?php echo esc_html( $position ); ?></h4>
                        <?php echo '<p class="social-link">'. $social_content .'</p>'; ?>
                        <div class="member-desc"><?php echo get_post_meta( get_the_ID(), '_w_member_detail', true ); ?></div>
                    </div>               
                </div>
            </div>
        </div>
        <?php endwhile;?>
        <?php wp_reset_postdata(); ?>
    </div>
</div>