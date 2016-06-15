<?php

if ( ! class_exists( 'Wyde_Mega_Menu' ) ){

    class Wyde_Mega_Menu{
     
        function __construct() {
            add_filter( 'wp_setup_nav_menu_item', array( $this, 'add_custom_nav_fields' ) );
            add_action( 'wp_update_nav_menu_item', array( $this, 'update_custom_nav_fields'), 10, 3 );
            add_filter( 'wp_edit_nav_menu_walker', array( $this, 'edit_walker'), 10, 2 );
            add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_scripts') );
        }

        function Wyde_Mega_Menu(){
            $this->__construct();
        }

        function load_admin_scripts(){
            // Icon Picker scripts
            wp_enqueue_style('wyde-font-awesome', WYDE_PLUGIN_URI. 'assets/css/font-awesome.min.css', null, '4.4.0');
            wp_enqueue_style('wyde-megamenu-style', WYDE_PLUGIN_URI. 'assets/css/wyde-megamenu.css', null, WYDE_VERSION);
            wp_enqueue_script('wyde-iconpicker-script', WYDE_PLUGIN_URI. 'assets/js/wyde-iconpicker.js', array('jquery'), WYDE_VERSION, true);
        }

        function add_custom_nav_fields( $menu_item ) {
            $menu_item->icon = get_post_meta( $menu_item->ID, '_w_menu_icon', true );
            $menu_item->megamenu = get_post_meta( $menu_item->ID, '_w_megamenu', true );
            return $menu_item;
        }

        function update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {

            if ( isset( $_REQUEST['menu-item-megamenu'] ) && is_array( $_REQUEST['menu-item-megamenu'] ) ) {
                if( isset( $_REQUEST['menu-item-megamenu'][$menu_item_db_id] ) ){
                    $megamenu = $_REQUEST['menu-item-megamenu'][$menu_item_db_id];
                    update_post_meta( $menu_item_db_id, '_w_megamenu', $megamenu );
                }
            }
            if ( isset( $_REQUEST['menu-item-icon'] ) && is_array( $_REQUEST['menu-item-icon'] ) ) {
                if( isset( $_REQUEST['menu-item-icon'][$menu_item_db_id] ) ){
                    $icon = $_REQUEST['menu-item-icon'][$menu_item_db_id];
                    update_post_meta( $menu_item_db_id, '_w_menu_icon', $icon );
                }
            }

        }

        function edit_walker($walker,$menu_id) {            
	        return 'Wyde_Walker_MegaMenu_Edit';
	    }
    }

    new Wyde_Mega_Menu();

}

if( !class_exists( 'Wyde_Walker_MegaMenu_Edit' ) ){

    class Wyde_Walker_MegaMenu_Edit extends Walker_Nav_Menu{

        protected $icon_picker_field_html;
        /**
        * Starts the list before the elements are added.
        *
        * @see Walker_Nav_Menu::start_lvl()
        *
        * @since 3.0.0
        *
        * @param string $output Passed by reference.
        * @param int    $depth  Depth of menu item. Used for padding.
        * @param array  $args   Not used.
        */
        public function start_lvl( &$output, $depth = 0, $args = array() ) {}

        /**
         * Ends the list of after the elements are added.
         *
         * @see Walker_Nav_Menu::end_lvl()
         *
         * @since 3.0.0
         *
         * @param string $output Passed by reference.
         * @param int    $depth  Depth of menu item. Used for padding.
         * @param array  $args   Not used.
         */
        public function end_lvl( &$output, $depth = 0, $args = array() ) {}


        /**
         * Icon picker field
         *
         * @param object $item  menu item.
         */
        public function icon_picker_field( $item ){
          
            $item_id = esc_attr( $item->ID );     
            $icon = ($item->icon && $item->icon != 'none')? '<i class="fa '.$item->icon.'"></i>': esc_html__('None', 'wyde-core');             

        ?>
            <label for="edit-menu-item-icon-<?php echo $item_id; ?>"><?php echo esc_html__( 'Icon', 'wyde-core' ); ?><br /></label>
            <div class="wyde-icons">
                <input id="edit-menu-item-icon-<?php echo $item_id; ?>" name="menu-item-icon[<?php echo $item_id; ?>]" class="wyde-icon-field" type="hidden" value="<?php echo esc_attr($item->icon); ?>" />
                <ul class="list-icons">
                    <li><a href="#"><span class="selected-value"><?php echo $icon; ?></span> <i class="dropit-arrow fa fa-angle-down"></i></a>
                    <?php echo $this->get_icon_list(); ?>
                    </li>
                </ul>
            </div>
        <?php                 

        }

        protected function get_icon_list(){

            if( ! $this->icon_picker_field_html ){

                $icons  = Wyde_Core::get_font_icons('fontawesome', 4.5, WYDE_PLUGIN_DIR . 'assets/css/font-awesome.min.css');
                ob_start();
                ?>                
                <ul>
                    <li><a href="#" title="No Icon"><?php echo esc_html__('None', 'wyde-core'); ?></a></li>
                <?php
                foreach($icons as $key => $text){
                    echo sprintf('<li><a href="#" title="%s"><i class="fa fa-%s"></i></a></li>', $text, $text);
                }
                ?>
                </ul>                   
                <?php
                $this->icon_picker_field_html = ob_get_clean();
            }

            return $this->icon_picker_field_html;
        }

        /**
         * Mega Menu field
         *
         * @param object $item  menu item.
         */
        public function megamenu_field($item){
            $item_id = esc_attr( $item->ID );            
            ?>
            <label for="edit-menu-item-megamenu-<?php echo $item_id; ?>">
                <?php echo esc_html__( 'Mega Menu', 'wyde-core' ); ?><br />
                <select id="edit-menu-item-megamenu-<?php echo $item_id; ?>" class="widefat code edit-menu-item-custom" name="menu-item-megamenu[<?php echo $item_id; ?>]">
                    <?php
                    $megamenu_options = array(
                        ''  => __('Disable', 'wyde-core'),
                        '2' => '2 '. __('Columns', 'wyde-core'),
                        '3' => '3 '. __('Columns', 'wyde-core'),
                        '4' => '4 '. __('Columns', 'wyde-core'),
                    );
                    foreach( $megamenu_options as $value => $text){
                    ?>
                    <option value="<?php echo esc_attr($value); ?>"<?php echo $item->megamenu == $value ? ' selected':''?>><?php echo esc_html($text);?></option>
                    <?php  } ?>
                </select>
            </label>
        <?php
        }

        /**
         * Start the element output.
         *
         * @see Walker_Nav_Menu::start_el()
         * @since 3.0.0
         *
         * @param string $output Passed by reference. Used to append additional content.
         * @param object $item   Menu item data object.
         * @param int    $depth  Depth of menu item. Used for padding.
         * @param array  $args   Not used.
         * @param int    $id     Not used.
         */
        public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
            global $_wp_nav_menu_max_depth;
            $_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

            ob_start();
            $item_id = esc_attr( $item->ID );
            $removed_args = array(
                'action',
                'customlink-tab',
                'edit-menu-item',
                'menu-item',
                'page-tab',
                '_wpnonce',
            );

            $original_title = '';
            if ( 'taxonomy' == $item->type ) {
                $original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
                if ( is_wp_error( $original_title ) )
                    $original_title = false;
            } elseif ( 'post_type' == $item->type ) {
                $original_object = get_post( $item->object_id );
                $original_title = get_the_title( $original_object->ID );
            }

            $classes = array(
                'menu-item menu-item-depth-' . $depth,
                'menu-item-' . esc_attr( $item->object ),
                'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
            );

            $title = $item->title;

            if ( ! empty( $item->_invalid ) ) {
                $classes[] = 'menu-item-invalid';
                /* translators: %s: title of menu item which is invalid */
                $title = sprintf( __( '%s (Invalid)', 'wyde-core'), $item->title );
            } elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
                $classes[] = 'pending';
                /* translators: %s: title of menu item in draft status */
                $title = sprintf( __('%s (Pending)', 'wyde-core'), $item->title );
            }

            $title = ( ! isset( $item->label ) || '' == $item->label ) ? $title : $item->label;

            $submenu_text = '';
            if ( 0 == $depth )
                $submenu_text = 'style="display: none;"';

            ?>
            <li id="menu-item-<?php echo $item_id; ?>" class="<?php echo implode(' ', $classes ); ?>">
                <dl class="menu-item-bar">
                    <dt class="menu-item-handle">
                        <span class="item-title"><span class="menu-item-title"><?php echo esc_html( $title ); ?></span> <span class="is-submenu" <?php echo $submenu_text; ?>><?php echo esc_html__( 'sub item', 'wyde-core' ); ?></span></span>
                        <span class="item-controls">
                            <span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
                            <span class="item-order hide-if-js">
                                <a href="<?php
                                    echo wp_nonce_url(
                                        add_query_arg(
                                            array(
                                                'action' => 'move-up-menu-item',
                                                'menu-item' => $item_id,
                                            ),
                                            remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
                                        ),
                                        'move-menu_item'
                                    );
                                ?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up'); ?>">&#8593;</abbr></a>
                                |
                                <a href="<?php
                                    echo wp_nonce_url(
                                        add_query_arg(
                                            array(
                                                'action' => 'move-down-menu-item',
                                                'menu-item' => $item_id,
                                            ),
                                            remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
                                        ),
                                        'move-menu_item'
                                    );
                                ?>" class="item-move-down"><abbr title="<?php esc_attr_e('Move down'); ?>">&#8595;</abbr></a>
                            </span>
                            <a class="item-edit" id="edit-<?php echo $item_id; ?>" title="<?php esc_attr_e('Edit Menu Item'); ?>" href="<?php
                                echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) );
                            ?>"><?php echo esc_html__( 'Edit Menu Item', 'wyde-core' ); ?></a>
                        </span>
                    </dt>
                </dl>

                <div class="menu-item-settings" id="menu-item-settings-<?php echo $item_id; ?>">
                    <?php if( 'custom' == $item->type ) : ?>
                        <p class="field-url description description-wide">
                            <label for="edit-menu-item-url-<?php echo $item_id; ?>">
                                <?php echo esc_html__( 'URL', 'wyde-core' ); ?><br />
                                <input type="text" id="edit-menu-item-url-<?php echo $item_id; ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
                            </label>
                        </p>
                    <?php endif; ?>
                    <div class="field-icon description description-wide">
                        <?php $this->icon_picker_field($item); ?>
                    </div>
                    <p class="description description-thin">
                        <label for="edit-menu-item-title-<?php echo $item_id; ?>">
                            <?php echo esc_html__( 'Navigation Label', 'wyde-core' ); ?><br />
                            <input type="text" id="edit-menu-item-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
                        </label>
                    </p>
                    <p class="description description-thin">
                        <label for="edit-menu-item-attr-title-<?php echo $item_id; ?>">
                            <?php echo esc_html__( 'Title Attribute', 'wyde-core' ); ?><br />
                            <input type="text" id="edit-menu-item-attr-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
                        </label>
                    </p>
                    <p class="field-link-target description">
                        <label for="edit-menu-item-target-<?php echo $item_id; ?>">
                            <input type="checkbox" id="edit-menu-item-target-<?php echo $item_id; ?>" value="_blank" name="menu-item-target[<?php echo $item_id; ?>]"<?php checked( $item->target, '_blank' ); ?> />
                            <?php echo esc_html__( 'Open link in a new window/tab', 'wyde-core' ); ?>
                        </label>
                    </p>
                    <?php /* Begin Wyde Mega Menu */ ?>      
                    <p class="w-megamenu-field field-custom description description-wide">
                        <?php $this->megamenu_field($item); ?>
                    </p>
                    <?php /* End Wyde Mega Menu */ ?>
                    <p class="field-css-classes description description-thin">
                        <label for="edit-menu-item-classes-<?php echo $item_id; ?>">
                            <?php echo esc_html__( 'CSS Classes (optional)', 'wyde-core' ); ?><br />
                            <input type="text" id="edit-menu-item-classes-<?php echo $item_id; ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo $item_id; ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
                        </label>
                    </p>
                    <p class="field-xfn description description-thin">
                        <label for="edit-menu-item-xfn-<?php echo $item_id; ?>">
                            <?php echo esc_html__( 'Link Relationship (XFN)', 'wyde-core' ); ?><br />
                            <input type="text" id="edit-menu-item-xfn-<?php echo $item_id; ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
                        </label>
                    </p>
                    <p class="field-description description description-wide">
                        <label for="edit-menu-item-description-<?php echo $item_id; ?>">
                            <?php echo esc_html__( 'Description', 'wyde-core' ); ?><br />
                            <textarea id="edit-menu-item-description-<?php echo $item_id; ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo $item_id; ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
                            <span class="description"><?php echo esc_html__('The description will be displayed in the menu if the current theme supports it.', 'wyde-core'); ?></span>
                        </label>
                    </p>

                    <p class="field-move hide-if-no-js description description-wide">
                        <label>
                            <span><?php echo esc_html__( 'Move', 'wyde-core' ); ?></span>
                            <a href="#" class="menus-move menus-move-up" data-dir="up"><?php echo esc_html__( 'Up one', 'wyde-core' ); ?></a>
                            <a href="#" class="menus-move menus-move-down" data-dir="down"><?php echo esc_html__( 'Down one', 'wyde-core' ); ?></a>
                            <a href="#" class="menus-move menus-move-left" data-dir="left"></a>
                            <a href="#" class="menus-move menus-move-right" data-dir="right"></a>
                            <a href="#" class="menus-move menus-move-top" data-dir="top"><?php echo esc_html__( 'To the top', 'wyde-core' ); ?></a>
                        </label>
                    </p>

                    <div class="menu-item-actions description-wide submitbox">
                        <?php if( 'custom' != $item->type && $original_title !== false ) : ?>
                            <p class="link-to-original">
                                <?php printf( esc_html__('Original: %s', 'wyde-core'), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
                            </p>
                        <?php endif; ?>
                        <a class="item-delete submitdelete deletion" id="delete-<?php echo $item_id; ?>" href="<?php
                        echo wp_nonce_url(
                            add_query_arg(
                                array(
                                    'action' => 'delete-menu-item',
                                    'menu-item' => $item_id,
                                ),
                                admin_url( 'nav-menus.php' )
                            ),
                            'delete-menu_item_' . $item_id
                        ); ?>"><?php echo esc_html__( 'Remove', 'wyde-core' ); ?></a> <span class="meta-sep hide-if-no-js"> | </span> <a class="item-cancel submitcancel hide-if-no-js" id="cancel-<?php echo $item_id; ?>" href="<?php echo esc_url( add_query_arg( array( 'edit-menu-item' => $item_id, 'cancel' => time() ), admin_url( 'nav-menus.php' ) ) );
                            ?>#menu-item-settings-<?php echo $item_id; ?>"><?php echo esc_html__('Cancel', 'wyde-core'); ?></a>
                    </div>

                    <input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo $item_id; ?>]" value="<?php echo $item_id; ?>" />
                    <input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
                    <input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
                    <input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
                    <input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
                    <input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
                </div><!-- .menu-item-settings-->
                <ul class="menu-item-transport"></ul>
            <?php
            $output .= ob_get_clean();
        }
    }
}