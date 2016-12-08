<?php get_header(); ?>
<div id="content">
    <?php overlap_page_title(); ?>
   <div class="<?php echo esc_attr( overlap_get_layout_class( overlap_get_option('portfolio_archive_page_layout'), '1') ); ?>">
        <?php overlap_page_background(); ?>
        <div class="page-content container">            
            <div class="<?php echo esc_attr( overlap_get_main_class('1') ); ?>">  
                <div class="col-inner">
                    <?php $cate_desc = term_description(); ?>
                    <?php if( $cate_desc ): ?>
                    <div class="post-detail">
                    <?php echo wp_kses_post( $cate_desc ); ?>    
                    </div>
                    <?php endif; ?>
                    <?php overlap_portfolio_archive(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>