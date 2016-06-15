<?php if( overlap_get_option('slidingbar') ):?>
<div id="slidingbar" class="w-<?php echo esc_attr( overlap_get_header_style() ); ?>">
    <a href="#" class="sliding-remove-button"><i class="ol-cancel"></i></a>
    <div class="slidingbar-wrapper">
        <div class="sliding-widgets">
            <?php dynamic_sidebar('slidingbar'); ?>
        </div>
        <?php 
        if( overlap_get_option('menu_contact') ) {
        	overlap_contact_info(); 
        }
        if( overlap_get_option('menu_social_icon') ) {
        	overlap_social_icons(); 
        }
        ?>
    </div>
</div>
<?php endif; ?>