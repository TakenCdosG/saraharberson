<?php

    $client_name =  get_post_meta(get_the_ID(), '_w_client_name', true );
    $client_detail = get_post_meta(get_the_ID(), '_w_client_detail', true );
    $client_website = get_post_meta(get_the_ID(), '_w_client_website', true );

?>
<?php if( !empty( $client_name )): ?>
<div class="portfolio-client-widget widget">                    
    <h4><?php echo esc_html__('Client', 'overlap'); ?></h4>
    <?php if( !empty( $client_website )){ ?> 
    <h6><a href="<?php echo esc_url( $client_website );?>" title="<?php echo esc_attr( $client_name ); ?>" target="_blank" class="tooltip-item"><?php echo esc_html( $client_name ); ?></a></h6>
    <?php }else{ ?>
    <h6><?php echo esc_html( $client_name ); ?></h6>
    <?php } ?>
    <?php echo wp_kses_post( $client_detail ); ?>
</div>
<?php endif; ?>