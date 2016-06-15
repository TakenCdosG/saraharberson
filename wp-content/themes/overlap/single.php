<?php get_header(); ?>
<div id="content">
    <?php

    if( have_posts() ) :
    
    the_post();

    overlap_page_title();

    $classes = array();

    $sidebar_position = overlap_get_sidebar_position();

    $classes[] = overlap_get_layout_class('boxed', $sidebar_position);

    $post_format = get_post_format();
    
    if( !empty($post_format) ) $classes[] = 'format-'. $post_format;

    ?>
    <div class="<?php echo esc_attr( implode(' ', $classes) );?>">    
        <?php overlap_page_background(); ?>
        <div class="page-content container">  
            <?php 
            if( $sidebar_position == '2' ){
                overlap_sidebar('blog', '2');
            }
            ?>
            <div class="<?php echo esc_attr( overlap_get_main_class( $sidebar_position ) ); ?>">  
                <div class="col-inner">     
                    <?php
                    if(post_password_required(get_the_ID())){
                        the_content();
                    }else{  
                        get_template_part('templates/blog/single'); 
                    }
                    ?>
                </div>
            </div>
            <?php 
            if( $sidebar_position == '3' ){
                overlap_sidebar('blog', '3');
            }
            ?>
        </div>
    </div>
    <?php 
    endif; 
    ?>
</div>
<?php get_footer(); ?>