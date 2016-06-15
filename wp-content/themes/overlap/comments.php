<div id="comments">
    <?php
    if ( post_password_required() ) { 
    ?>
	<p class="no-comments"><?php echo esc_html__('This post is password protected. Enter the password to view comments.', 'overlap'); ?></p>
    <?php
        return;
    }
    if ( have_comments() ) { ?>
	<div class="comments clear">
        <h3><?php comments_number(esc_html__('No Comments', 'overlap'), esc_html__('One Comment', 'overlap'), '% '.esc_html__('Comments', 'overlap'));?></h3>
		<ul class="comment-list">
			<?php 

            $args = array(
                'style' => 'ul',
                'callback' => 'overlap_comment',
                'avatar_size' => 64
            );

            wp_list_comments($args); 

            ?>
		</ul>
        <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
		<div class="comments-nav clear">
		    <span class="alignleft"><?php previous_comments_link('<i class="ol-left"></i>'); ?></span>
		    <span class="alignright"><?php next_comments_link('<i class="ol-right"></i>'); ?></span>
		</div>
        <?php endif; ?>
	</div>
<?php
 } else { 

    if ( !comments_open() ) {
    ?>
	<p class="no-comments"><?php echo esc_html__('Comments are closed.', 'overlap'); ?></p>
    <?php
    } 
} 

if ( comments_open() ) { 

	function overlap_comment_form_fields($fields){
		$commenter = wp_get_current_commenter();
		$req       = get_option( 'require_name_email' );

		$fields['author'] = '<p class="inputrow"><input type="text" name="author" id="author" value="'. esc_attr( $commenter['comment_author'] ) .'" placeholder="'. esc_html__('Name', 'overlap'). ' ('. esc_html__('Required', 'overlap') .')" size="22" tabindex="1"'. ( $req ? ' required' : '' ).' /></p>';

		$fields['email'] = '<p class="inputrow"><input type="text" name="email" id="email" value="'. sanitize_email( $commenter['comment_author_email'] ) .'" placeholder="'. esc_html__('Email', 'overlap'). ' ('. esc_html__('Required', 'overlap') .')" size="22" tabindex="2"'. ( $req ? ' required' : '' ).'  /></p>';

		$fields['url'] = '<p class="inputrow"><input type="text" name="url" id="url" value="'. esc_url( $commenter['comment_author_url'] ) .'" placeholder="'. esc_html__('Website', 'overlap').'" size="22" tabindex="3" /></p>';

		return  $fields ;
	}
	add_filter('comment_form_default_fields','overlap_comment_form_fields');

	$comments_args = array(
		'title_reply' => esc_html__('Post A Comment', 'overlap'),
		'title_reply_to' =>  esc_html__("Leave A Reply", 'overlap'),
		'must_log_in' => '<p class="inputrow">' .  sprintf( esc_html__( 'You must be %slogged in%s to post a comment.', 'overlap'), '<a href="'.wp_login_url( apply_filters( 'the_permalink', get_permalink( ) ) ).'">', '</a>' ) . '</p>',
		'logged_in_as' => '<p class="inputrow">' . esc_html__( 'Logged in as', 'overlap' ).' <a href="' .admin_url( "profile.php" ).'" class="user-link">'.$user_identity.'</a>  <a href="' .wp_logout_url(get_permalink()).'" class="logout-link" title="' . esc_html__('Log out of this account', 'overlap').'">'. esc_html__('Log out', 'overlap').'</a></p>',
		'comment_notes_before' => '',
		'comment_notes_after' => '',
		'comment_field' => '<p class="inputrow"><textarea name="comment" id="comment" cols="45" rows="8" tabindex="4" class="textarea-comment" placeholder="'. esc_html__('Comment...', 'overlap').'"></textarea></p>',
		'id_submit' => 'comment-submit',
		'label_submit'=> esc_html__('Post Comment', 'overlap'),
	);

	comment_form($comments_args);
}
?>
</div>