<?php get_header(); ?>
<div id="content">
    <?php   
    overlap_page_title();
    
    $author_id = get_the_author_meta('ID');
    $avatar = get_avatar( get_the_author_meta('email', $author_id), '100' );
    ?>
    <div class="<?php echo esc_attr( overlap_get_layout_class( overlap_get_option('blog_archive_page_layout'), overlap_get_option('blog_archive_sidebar') ) ); ?>">
        <?php overlap_page_background(); ?>
        <div class="author-avatar">
            <?php echo sprintf('<a href="%s">%s</a>', get_author_posts_url($author_id), $avatar); ?>
        </div>
        <div class="page-content container">
            <?php 
            if( overlap_get_option('blog_archive_sidebar') == '2' ){
                overlap_sidebar( 'blog', '2', overlap_get_option('blog_archive_sidebar_style') );
            }
            ?>
            <div class="<?php echo esc_attr( overlap_get_main_class( overlap_get_option('blog_archive_sidebar') ) ); ?>">
                <div class="col-inner">               
                    <?php overlap_blog_archive(); ?>
                </div>
            </div>
            <?php 
            if( overlap_get_option('blog_archive_sidebar') == '3'){
                overlap_sidebar( 'blog', '3', overlap_get_option('blog_archive_sidebar_style') );
            }
            ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>