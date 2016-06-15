<?php if( overlap_get_nav_layout() == 'fullscreen' ):?>
<div id="fullscreen-nav" class="w-<?php echo esc_attr( overlap_get_header_style() ); ?>">
    <div class="container">
        <div class="full-nav-container">
            <div class="full-nav-wrapper">
                <nav id="full-nav">
                    <ul class="vertical-menu">
                        <?php overlap_fullscreen_menu(); ?>
                    </ul>
                </nav>            
            </div>   
            <?php             
            if( overlap_get_option('menu_social_icon') ) {
                overlap_social_icons(); 
            }
            ?>         
        </div>
    </div>
</div>
<?php endif;?>