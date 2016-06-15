(function ($) {

    $.extend(window, {
        wyde_row_background_image_callback: function () {
            var self = this;
            this.$content.find(".attach_image.background_image").change(function () {
                var $image = $(this).next(".gallery_widget_attached_images").find(".inner img");
                self.$content.find(".vc_param-name-bg_image_url").val($image.attr("src"));
            });
        },
        wyde_column_background_image_callback: function () {
            var self = this;
            this.$content.find(".attach_image.background_image").change(function () {
                var $image = $(this).next(".gallery_widget_attached_images").find(".inner img");
                self.$content.find(".vc_param-name-bg_image_url").val($image.attr("src"));
            });
        }
    });    


    if (vc && vc.shortcode_view) { 
    
        var Shortcodes = vc.shortcodes;

        // Row
        window.WydeRowView = vc.shortcode_view.extend( {
            change_columns_layout: false,
            events: {
                'click > .vc_controls [data-vc-control="delete"]': 'deleteShortcode',
                'click > .vc_controls .set_columns': 'setColumns',
                'click > .vc_controls [data-vc-control="add"]': 'addElement',
                'click > .vc_controls [data-vc-control="edit"]': 'editElement',
                'click > .vc_controls [data-vc-control="clone"]': 'clone',
                'click > .vc_controls [data-vc-control="move"]': 'moveElement',
                'click > .vc_controls [data-vc-control="toggle"]': 'toggleElement',
                'click > .wpb_element_wrapper .vc_controls': 'openClosedRow'
            },
            convertRowColumns: function ( layout ) {
                var layout_split = layout.toString().split(/_/),
                    columns = Shortcodes.where( { parent_id: this.model.id } ),
                    new_columns = [],
                    new_layout = [],
                    new_width = '';
                _.each( layout_split, function ( value, i ) {
                    var column_data = _.map( value.toString().split( '' ), function ( v, i ) {
                            return parseInt( v, 10 );
                        } ),
                        new_column_params, new_column;
                    if ( 3 < column_data.length ) {
                        new_width = column_data[ 0 ] + '' + column_data[ 1 ] + '/' + column_data[ 2 ] + '' + column_data[ 3 ];
                    } else if ( 2 < column_data.length ) {
                        new_width = column_data[ 0 ] + '/' + column_data[ 1 ] + '' + column_data[ 2 ];
                    } else {
                        new_width = column_data[ 0 ] + '/' + column_data[ 1 ];
                    }
                    new_layout.push( new_width );
                    new_column_params = _.extend( ! _.isUndefined( columns[ i ] ) ? columns[ i ].get( 'params' ) : {},
                        { width: new_width } ),
                        vc.storage.lock();
                    new_column = Shortcodes.create( {
                        shortcode: this.getChildTag(),
                        params: new_column_params,
                        parent_id: this.model.id
                    } );
                    if ( _.isObject( columns[ i ] ) ) {
                        _.each( Shortcodes.where( { parent_id: columns[ i ].id } ), function ( shortcode ) {
                            vc.storage.lock();
                            shortcode.save( { parent_id: new_column.id } );
                            vc.storage.lock();
                            shortcode.trigger( 'change_parent_id' );
                        } );
                    }
                    new_columns.push( new_column );
                }, this );
                if ( layout_split.length < columns.length ) {
                    _.each( columns.slice( layout_split.length ), function ( column ) {
                        _.each( Shortcodes.where( { parent_id: column.id } ), function ( shortcode ) {
                            vc.storage.lock();
                            shortcode.save( { 'parent_id': _.last( new_columns ).id } );
                            vc.storage.lock();
                            shortcode.trigger( 'change_parent_id' );
                        } );
                    } );
                }
                _.each( columns, function ( shortcode ) {
                    vc.storage.lock();
                    shortcode.destroy();
                }, this );
                this.model.save();
                this.setActiveLayoutButton( '' + layout );
                return new_layout;
            },
            changeShortcodeParams: function ( model ) {
                window.WydeRowView.__super__.changeShortcodeParams.call( this, model );
                this.buildDesignHelpers();
            },
            designHelpersSelector: "> .vc_controls .column_toggle",
            buildDesignHelpers: function () {
                //Edit by Wyde
                var $columnToggle,
                    image,
                    color,
                    rowId,
                    matches;

                    $columnToggle = this.$el.find('> .vc_controls .column_toggle');

                    this.$el.find('> .vc_controls  .vc_row_color').remove();
                    this.$el.find('> .vc_controls  .vc_row_image').remove();

                    //Todo refactor this to separate methods and maybe vc.events
                    rowId = this.model.getParam( 'el_id' );
                    this.$el.find( '> .vc_controls  .vc_row-hash-id' ).remove();
                    if ( ! _.isEmpty( rowId ) ) {
                        $( '<span class="vc_row-hash-id"></span>' )
                            .text( '#' + rowId )
                            .insertAfter( $columnToggle );
                    }

                    color = this.model.getParam('background_color');
                    if (color) {
                        $('<span class="vc_row_color" style="background-color: ' + color + ';" title="' + window.i18nLocale.row_background_color + '"></span>')
                        .insertAfter($columnToggle);
                    }

                    image = this.model.getParam('bg_image_url');
                    if (image) {
                        $('<span class="vc_row_image" style="background-image: url(' + image + ');" title="' + window.i18nLocale.row_background_image + '"></span>')
                        .insertAfter($columnToggle);

                        var parallax = this.model.getParam('parallax');
                        if (parallax) {
                            $('<span class="vc_row_image parallax-icon" title="Parallax Background"></span>')
                        .insertAfter($columnToggle);
                        }
                    }
            },
            addElement: function ( e ) {
                e && e.preventDefault();
                Shortcodes.create( { shortcode: this.getChildTag(), params: {}, parent_id: this.model.id } );
                this.setActiveLayoutButton();
                this.$el.removeClass( 'vc_collapsed-row' );
            },
            getChildTag: function () {
                return 'vc_row_inner' === this.model.get( 'shortcode' ) ? 'vc_column_inner' : 'vc_column';
            },
            sortingSelector: "> [data-element_type='vc_column'], > [data-element_type='vc_column_inner']",
            sortingSelectorCancel: ".vc-non-draggable-column",
            setSorting: function () {
                var that = this;
                if ( 1 < this.$content.find( this.sortingSelector ).length ) {
                    this.$content.removeClass( 'wpb-not-sortable' ).sortable( {
                        forcePlaceholderSize: true,
                        placeholder: "widgets-placeholder-column",
                        tolerance: "pointer",
                        // cursorAt: { left: 10, top : 20 },
                        cursor: "move",
                        //handle: '.vc_controls ',
                        items: this.sortingSelector, //wpb_sortablee
                        distance: 0.5,
                        start: function ( event, ui ) {
                            $( '#visual_composer_content' ).addClass( 'vc_sorting-started' );
                            ui.placeholder.width( ui.item.width() );
                        },
                        stop: function ( event, ui ) {
                            $( '#visual_composer_content' ).removeClass( 'vc_sorting-started' );
                        },
                        update: function () {
                            var $columns = $( that.sortingSelector, that.$content );
                            $columns.each( function () {
                                var model = $( this ).data( 'model' ),
                                    index = $( this ).index();
                                model.set( 'order', index );
                                if ( $columns.length - 1 > index ) {
                                    vc.storage.lock();
                                }
                                model.save();
                            } );
                        },
                        over: function ( event, ui ) {
                            ui.placeholder.css( { maxWidth: ui.placeholder.parent().width() } );
                            ui.placeholder.removeClass( 'vc_hidden-placeholder' );
                        },
                        beforeStop: function ( event, ui ) {
                        }
                    } );
                } else {
                    if ( this.$content.hasClass( 'ui-sortable' ) ) {
                        this.$content.sortable( 'destroy' );
                    }
                    this.$content.addClass( 'wpb-not-sortable' );
                }
            },
            validateCellsList: function ( cells ) {
                var return_cells = [],
                    split = cells.replace( /\s/g, '' ).split( '+' ),
                    b;
                var sum = _.reduce( _.map( split, function ( c ) {
                    if ( c.match( /^(vc_)?span\d?$/ ) ) {
                        var converted_c = vc_convert_column_span_size( c );
                        if ( false === converted_c ) {
                            return 1000;
                        }
                        b = converted_c.split( /\// );
                        return_cells.push( b[ 0 ] + '' + b[ 1 ] );
                        return 12 * parseInt( b[ 0 ], 10 ) / parseInt( b[ 1 ], 10 );
                    } else if ( c.match( /^[1-9]|1[0-2]\/[1-9]|1[0-2]$/ ) ) {
                        b = c.split( /\// );
                        return_cells.push( b[ 0 ] + '' + b[ 1 ] );
                        return 12 * parseInt( b[ 0 ], 10 ) / parseInt( b[ 1 ], 10 );
                    }
                    return 10000;

                } ), function ( num, memo ) {
                    memo = memo + num;
                    return memo;
                }, 0 );
                if ( 12 !== sum ) {
                    return false;
                }
                return return_cells.join( '_' );
            },
            setActiveLayoutButton: function ( column_layout ) {
                if ( ! column_layout ) {
                    column_layout = _.map( vc.shortcodes.where( { parent_id: this.model.get( 'id' ) } ),
                        function ( model ) {
                            var width = model.getParam( 'width' );
                            return ! width ? '11' : width.replace( /\//, '' );
                        } ).join( '_' );
                }
                this.$el.find( '> .vc_controls .vc_active' ).removeClass( 'vc_active' );
                var $button = this.$el.find( '> .vc_controls  [data-cells-mask="' + vc_get_column_mask( column_layout ) + '"] [data-cells="' + column_layout + '"]'
                + ', > .vc_controls [data-cells-mask="' + vc_get_column_mask( column_layout ) + '"][data-cells="' + column_layout + '"]' );
                if ( $button.length ) {
                    $button.addClass( 'vc_active' );
                } else {
                    this.$el.find( '> .vc_controls  [data-cells-mask="custom"]' ).addClass( 'vc_active' );
                }
            },
            layoutEditor: function () {
                if ( _.isUndefined( vc.row_layout_editor ) ) {
                    // vc.row_layout_editor = new vc.RowLayoutEditorPanelViewBackend( { el: $( '#vc_row-layout-panel' ) } );
                    vc.row_layout_editor = new vc.RowLayoutUIPanelBackendEditor( { el: $( '#vc_ui-panel-row-layout' ) } );
                }
                return vc.row_layout_editor;
            },
            setColumns: function ( e ) {
                if ( _.isObject( e ) ) {
                    e.preventDefault();
                }
                var $button = $( e.currentTarget );
                if ( 'custom' === $button.data( 'cells' ) ) {
                    this.layoutEditor().render( this.model ).show();
                } else {
                    if ( vc.is_mobile ) {
                        var $parent = $button.parent();
                        if ( ! $parent.hasClass( 'vc_visible' ) ) {
                            $parent.addClass( 'vc_visible' );
                            $( document ).bind( 'click.vcRowColumnsControl', function ( e ) {
                                $parent.removeClass( 'vc_visible' );
                                $( document ).unbind( 'click.vcRowColumnsControl' );
                            } );
                        }
                    }
                    if ( ! $button.is( '.vc_active' ) ) {
                        this.change_columns_layout = true;
                        _.defer( function ( view, cells ) {
                            view.convertRowColumns( cells );
                        }, this, $button.data( 'cells' ) );
                    }
                }
                this.$el.removeClass( 'vc_collapsed-row' );
            },
            sizeRows: function () {
                var max_height = 45;
                $( '> .wpb_vc_column, > .wpb_vc_column_inner', this.$content ).each( function () {
                    var content_height = $( this ).find( '> .wpb_element_wrapper > .wpb_column_container' ).css( { minHeight: 0 } ).height();
                    if ( content_height > max_height ) {
                        max_height = content_height;
                    }
                } ).each( function () {
                    $( this ).find( '> .wpb_element_wrapper > .wpb_column_container' ).css( { minHeight: max_height } );
                } );
            },
            ready: function ( e ) {
                window.WydeRowView.__super__.ready.call( this, e );
                return this;
            },
            checkIsEmpty: function () {
                window.WydeRowView.__super__.checkIsEmpty.call( this );
                this.setSorting();
            },
            changedContent: function ( view ) {
                if ( this.change_columns_layout ) {
                    return this;
                }
                this.setActiveLayoutButton();
            },
            moveElement: function ( e ) {
                e.preventDefault();
            },
            toggleElement: function ( e ) {
                e && e.preventDefault();
                this.$el.toggleClass( 'vc_collapsed-row' );
            },
            openClosedRow: function ( e ) {
                this.$el.removeClass( 'vc_collapsed-row' );
            },
            remove: function() {
                this.$content && this.$content.data("uiSortable") && this.$content.sortable("destroy"), this.$content && this.$content.data("uiDroppable") && this.$content.droppable("destroy"), delete vc.app.views[this.model.id], window.VcRowView.__super__.remove.call(this)
            }
        } );        

        // Column
        window.WydeColumnView = vc.shortcode_view.extend( {
            events: {
                'click > .vc_controls [data-vc-control="delete"]': 'deleteShortcode',
                'click > .vc_controls [data-vc-control="add"]': 'addElement',
                'click > .vc_controls [data-vc-control="edit"]': 'editElement',
                'click > .vc_controls [data-vc-control="clone"]': 'clone',
                'click > .wpb_element_wrapper > .vc_empty-container': 'addToEmpty'
            },
            current_column_width: false,
            initialize: function ( options ) {
                window.WydeColumnView.__super__.initialize.call( this, options );
                _.bindAll( this, 'setDropable', 'dropButton' );
            },
            ready: function ( e ) {
                window.WydeColumnView.__super__.ready.call( this, e );
                this.setDropable();
                return this;
            },
            render: function () {
                window.WydeColumnView.__super__.render.call( this );
                this.current_column_width = this.model.get( 'params' ).width || '1/1';
                this.$el.attr( 'data-width', this.current_column_width );
                this.setEmpty();
                return this;
            },
            changeShortcodeParams: function ( model ) {
                window.WydeColumnView.__super__.changeShortcodeParams.call( this, model );
                this.setColumnClasses();
                this.buildDesignHelpers();
            },
            designHelpersSelector: '> .vc_controls .column_add',
            buildDesignHelpers: function () {
                //Edit by Wyde
                var matches;
                var css = this.model.getParam('css'),
                $column_toggle = this.$el.find(this.designHelpersSelector).get(0),
                image,
                color;
                this.$el.find('> .vc_controls .vc_column_color').remove();
                this.$el.find('> .vc_controls .vc_column_image').remove();

                color = this.model.getParam('background_color');
                image = this.model.getParam('bg_image_url');
                if (image) {
                    $('<span class="vc_column_image" style="background-image: url(' + image + ');" title="' + i18nLocale.column_background_image + '"></span>')
                    .insertBefore($column_toggle);
                }
                if (color) {
                    $('<span class="vc_column_color" style="background-color: ' + color + '" title="' + i18nLocale.column_background_color + '"></span>')
                    .insertBefore($column_toggle);
                }
            },
            setColumnClasses: function () {
                var offset = this.model.getParam( 'offset' ) || '',
                    width = this.model.getParam( 'width' ) || '1/1',
                    css_class_width = this.convertSize( width ), current_css_class_width;
                this.current_offset_class && this.$el.removeClass( this.current_offset_class );
                if ( this.current_column_width !== width ) {
                    current_css_class_width = this.convertSize( this.current_column_width );
                    this.$el
                        .attr( 'data-width', width )
                        .removeClass( current_css_class_width )
                        .addClass( css_class_width );
                    this.current_column_width = width;
                }
                if ( offset.match( /vc_col\-sm\-\d+/ ) ) {
                    this.$el.removeClass( css_class_width );
                }
                if ( ! _.isEmpty( offset ) ) {
                    this.$el.addClass( offset );
                }
                this.current_offset_class = offset;
            },
            addToEmpty: function ( e ) {
                e.preventDefault();
                if ( $( e.target ).hasClass( 'vc_empty-container' ) ) {
                    this.addElement( e );
                }
            },
            setDropable: function () {
                this.$content.droppable( {
                    greedy: true,
                    accept: ('vc_column_inner' === this.model.get( 'shortcode' ) ? '.dropable_el' : ".dropable_el,.dropable_row"),
                    hoverClass: "wpb_ui-state-active",
                    drop: this.dropButton
                } );
                return this;
            },
            dropButton: function ( event, ui ) {
                if ( ui.draggable.is( '#wpb-add-new-element' ) ) {
                    new vc.element_block_view( { model: { position_to_add: 'end' } } ).show( this );
                } else if ( ui.draggable.is( '#wpb-add-new-row' ) ) {
                    this.createRow();
                }
            },
            setEmpty: function () {
                this.$el.addClass( 'vc_empty-column' );
                this.$content.addClass( 'vc_empty-container' );
            },
            unsetEmpty: function () {
                this.$el.removeClass( 'vc_empty-column' );
                this.$content.removeClass( 'vc_empty-container' );
            },
            checkIsEmpty: function () {
                if ( Shortcodes.where( { parent_id: this.model.id } ).length ) {
                    this.unsetEmpty();
                } else {
                    this.setEmpty();
                }
                /*
                 if (this.model.get('parent_id')) {
                 var row_view = vc.app.views[this.model.get('parent_id')];
                 if (row_view.model.get('shortcode').match(/^vc\_row/)) {
                 row_view.sizeRows();
                 }
                 }
                 */
                window.WydeColumnView.__super__.checkIsEmpty.call( this );
            },
            /**
             * Create row
             */
            createRow: function () {
                var row = Shortcodes.create( { shortcode: 'vc_row_inner', parent_id: this.model.id } );
                Shortcodes.create( { shortcode: 'vc_column_inner', params: { width: '1/1' }, parent_id: row.id } );
                return row;
            },
            convertSize: function ( width ) {
                var prefix = 'vc_col-sm-',
                    numbers = width ? width.split( '/' ) : [
                        1,
                        1
                    ],
                    range = _.range( 1, 13 ),
                    num = ! _.isUndefined( numbers[ 0 ] ) && 0 <= _.indexOf( range,
                        parseInt( numbers[ 0 ], 10 ) ) ? parseInt( numbers[ 0 ], 10 ) : false,
                    dev = ! _.isUndefined( numbers[ 1 ] ) && 0 <= _.indexOf( range,
                        parseInt( numbers[ 1 ], 10 ) ) ? parseInt( numbers[ 1 ], 10 ) : false;
                if ( false !== num && false !== dev ) {
                    return prefix + (12 * num / dev);
                }
                return prefix + '12';
            },
            deleteShortcode: function ( e ) {
                var parent_id = this.model.get( 'parent_id' ),
                    parent;
                if ( _.isObject( e ) ) {
                    e.preventDefault();
                }
                var answer = confirm( window.i18nLocale.press_ok_to_delete_section );
                if ( true !== answer ) {
                    return false;
                }
                this.model.destroy();
                if ( parent_id && ! vc.shortcodes.where( { parent_id: parent_id } ).length ) {
                    parent = vc.shortcodes.get( parent_id );
                    if ( ! _.contains( [
                            'vc_column',
                            'vc_column_inner'
                        ], parent.get( 'shortcode' ) ) ) {
                        parent.destroy();
                    }
                } else if ( parent_id ) {
                    parent = vc.shortcodes.get( parent_id );
                    if ( parent && parent.view && parent.view.setActiveLayoutButton ) {
                        parent.view.setActiveLayoutButton();
                    }
                }
            }
        } );   


        if (window.VcColumnView) {

            /* Accordion Tab */
            window.WydeAccordionTabView = window.VcColumnView.extend( {
                events: {
                    'click > [data-element_type] > .vc_controls .vc_control-btn-delete': 'deleteShortcode',
                    'click > [data-element_type] > .vc_controls .vc_control-btn-prepend': 'addElement',
                    'click > [data-element_type] > .vc_controls .vc_control-btn-edit': 'editElement',
                    'click > [data-element_type] > .vc_controls .vc_control-btn-clone': 'clone',
                    'click > [data-element_type] > .wpb_element_wrapper > .vc_empty-container': 'addToEmpty'
                },
                setContent: function () {
                    this.$content = this.$el.find( '> [data-element_type] > .wpb_element_wrapper > .vc_container_for_children' );
                },
                changeShortcodeParams: function ( model ) {

                    window.WydeAccordionTabView.__super__.changeShortcodeParams.call( this, model );

                    var params = model.get( 'params' );
                    if ( _.isObject( params ) && _.isString( params.title ) ) {
                        var icon = "";
                        switch (params.icon_set) {
                            case "typicons":
                                icon = params.icon_typicons;
                                break;
                            case "linecons":
                                icon = params.icon_linecons;
                                break;
                            case "bigmug_line":
                                icon = params.icon_bigmug_line;
                                break;
                            case "simple_line":
                            icon = params.icon_simple_line;
                            break;
                            default:
                                icon = params.icon;
                                break;
                        }

                        var title = params.title;

                        if (icon) title = "<i class=\"" + icon + "\"></i> " + title;
                        this.$el.find( '> h3 .tab-label' ).html( title );
                    }
                },
                setEmpty: function () {
                    $( '> [data-element_type]', this.$el ).addClass( 'vc_empty-column' );
                    this.$content.addClass( 'vc_empty-container' );
                },
                unsetEmpty: function () {
                    $( '> [data-element_type]', this.$el ).removeClass( 'vc_empty-column' );
                    this.$content.removeClass( 'vc_empty-container' );
                }
            } );


            /* Tab Icon */
            window.WydeTabView = window.VcColumnView.extend({
                events: {
                    'click > .vc_controls .vc_control-btn-delete': 'deleteShortcode',
                    'click > .vc_controls .vc_control-btn-prepend': 'addElement',
                    'click > .vc_controls .vc_control-btn-edit': 'editElement',
                    'click > .vc_controls .vc_control-btn-clone': 'clone',
                    'click > .wpb_element_wrapper > .vc_empty-container': 'addToEmpty'
                },
                render: function () {
                    var params = this.model.get('params');
                    window.WydeTabView.__super__.render.call(this);
                    if (!params.tab_id || params.tab_id.indexOf('def') != -1) {
                        params.tab_id = (+new Date() + '-' + Math.floor(Math.random() * 11));
                        this.model.save('params', params);
                    }
                    this.id = 'tab-' + params.tab_id;
                    this.$el.attr('id', this.id);                
                    return this;
                },
                ready: function (e) {
                    window.WydeTabView.__super__.ready.call(this, e);
                    this.$tabs = this.$el.closest('.wpb_tabs_holder');
                    var params = this.model.get('params');
                    return this;
                },
                renderTabIcons: function(model){
                    var params = model.get('params');
                    if (_.isObject(params) &&  _.isString(params.tab_id)) {
                        var icon = "";
                       switch (params.icon_set) {
                            case "typicons":
                                icon = params.icon_typicons;
                                break;
                            case "linecons":
                                icon = params.icon_linecons;
                                break;
                            case "bigmug_line":
                                icon = params.icon_bigmug_line;
                                break;
                            case "simple_line":
                                icon = params.icon_simple_line;
                                break;
                            default:
                                icon = params.icon;
                                break;
                        }

                        var title = params.title;

                        if (icon) title = "<i class=\"" + icon + "\"></i>";

                        $(".ui-tabs-nav a[href='#tab-" + params.tab_id + "']").html(title);
                    }
                },
                changeShortcodeParams: function (model) {
                    window.WydeTabView.__super__.changeShortcodeParams.call(this, model);                
                    this.renderTabIcons(model);
                },
                deleteShortcode: function (e) {
                    _.isObject(e) && e.preventDefault();
                    var answer = confirm(window.i18nLocale.press_ok_to_delete_section),
                    parent_id = this.model.get('parent_id');
                    if (answer !== true) return false;
                    this.model.destroy();
                    if (!vc.shortcodes.where({ parent_id: parent_id }).length) {
                        vc.shortcodes.get(parent_id).destroy();
                        return false;
                    }
                    var params = this.model.get('params'),
                    current_tab_index = $("a[href='#tab-" + params.tab_id + "']", this.$tabs).parent().index();
                    $("a[href='#tab-" + params.tab_id + "']").parent().remove();
                    var tab_length = this.$tabs.find('.ui-tabs-nav li:not(.add_tab_block)').length;
                    if (tab_length > 0) {
                        this.$tabs.tabs('refresh');
                    }
                    if (current_tab_index < tab_length) {
                        this.$tabs.tabs("option", "active", current_tab_index);
                    } else if (tab_length > 0) {
                        this.$tabs.tabs("option", "active", tab_length - 1);
                    }

                },
                cloneModel: function (model, parent_id, save_order) {
                    var new_order,
                        model_clone,
                        params,
                        tag;

                    new_order = _.isBoolean( save_order ) && true === save_order ? model.get( 'order' ) : parseFloat( model.get( 'order' ) ) + vc.clone_index;
                    params = _.extend( {}, model.get( 'params' ) );
                    tag = model.get( 'shortcode' );

                    if ( 'wyde_tab' === tag ) {
                        _.extend( params,
                            { tab_id: Date.now() + '-' + this.$tabs.find( '[data-element_type="wyde_tab"]' ).length + '-' + Math.floor( Math.random() * 11 ) } );
                    }

                    model_clone = Shortcodes.create( {
                        shortcode: tag,
                        parent_id: parent_id,
                        order: new_order,
                        cloned: true,
                        cloned_from: model.toJSON(),
                        params: params
                    } );

                    _.each( Shortcodes.where( { parent_id: model.id } ), function ( shortcode ) {
                        this.cloneModel( shortcode, model_clone.get( 'id' ), true );
                    }, this );

                    this.renderTabIcons(model_clone);

                    return model_clone;
                }
            });

            /* Slide */
            window.WydeSlideView = window.VcColumnView.extend( {
                events: {
                    'click > .vc_controls .vc_control-btn-delete': 'deleteShortcode',
                    'click > .vc_controls .vc_control-btn-prepend': 'addElement',
                    'click > .vc_controls .vc_control-btn-edit': 'editElement',
                    'click > .vc_controls .vc_control-btn-clone': 'clone',
                    'click > .wpb_element_wrapper > .vc_empty-container': 'addToEmpty'
                },
                render: function () {
                    var params = this.model.get( 'params' );
                    window.WydeSlideView.__super__.render.call( this );
                    /**
                     * @deprecated 4.4.3
                     * @see composer-atts.js vc.atts.slide_id.addShortcode
                     */
                    if ( ! params.slide_id/* || params.slide_id.indexOf('def') != -1*/ ) {
                        params.slide_id = (Date.now() + '-' + Math.floor( Math.random() * 11 ));
                        this.model.save( 'params', params );
                    }
                    this.id = 'slide-' + params.slide_id;
                    this.$el.attr( 'id', this.id );
                    return this;
                },
                ready: function ( e ) {
                    window.WydeSlideView.__super__.ready.call( this, e );
                    this.$tabs = this.$el.closest( '.wpb_tabs_holder' );
                    var params = this.model.get( 'params' );
                    return this;
                },
                changeShortcodeParams: function ( model ) {
                    var params;

                    window.WydeSlideView.__super__.changeShortcodeParams.call( this, model );
                    params = model.get( 'params' );
                    if ( _.isObject( params ) && _.isString( params.title ) && _.isString( params.slide_id ) ) {
                        $(".ui-tabs-nav a[href='#slide-" + params.slide_id + "']").text( params.title );
                    }
                },
                deleteShortcode: function ( e ) {
                    _.isObject( e ) && e.preventDefault();
                    var answer = confirm( window.i18nLocale.press_ok_to_delete_section ),
                        parent_id = this.model.get( 'parent_id' );
                    if ( true !== answer ) {
                        return false;
                    }
                    this.model.destroy();
                    if ( ! vc.shortcodes.where( { parent_id: parent_id } ).length ) {
                        var parent = vc.shortcodes.get( parent_id );
                        parent.destroy();
                        return false;
                    }
                    var params = this.model.get( 'params' ),
                        current_tab_index = $("a[href='#slide-" + params.slide_id + "']", this.$tabs ).parent().index();
                    $("a[href='#slide-" + params.slide_id + "']").parent().remove();
                    var tab_length = this.$tabs.find('.ui-tabs-nav li:not(.add_tab_block)').length;
                    if ( 0 < tab_length ) {
                        this.$tabs.tabs('refresh');
                    }
                    if ( current_tab_index < tab_length ) {
                        this.$tabs.tabs( "option", "active", current_tab_index );
                    } else if ( 0 < tab_length ) {
                        this.$tabs.tabs( "option", "active", tab_length - 1 );
                    }

                },
                cloneModel: function ( model, parent_id, save_order ) {
                    var new_order,
                        model_clone,
                        params,
                        tag;

                    new_order = _.isBoolean( save_order ) && true === save_order ? model.get( 'order' ) : parseFloat( model.get( 'order' ) ) + vc.clone_index;
                    params = _.extend( {}, model.get( 'params' ) );
                    tag = model.get( 'shortcode' );

                    if ( 'wyde_slide' === tag ) {
                        _.extend( params,
                            { slide_id: Date.now() + '-' + this.$tabs.find( '[data-element_type="wyde_slide"]' ).length + '-' + Math.floor( Math.random() * 11 ) } );
                    }

                    model_clone = Shortcodes.create( {
                        shortcode: tag,
                        parent_id: parent_id,
                        order: new_order,
                        cloned: true,
                        cloned_from: model.toJSON(),
                        params: params
                    } );

                    _.each( Shortcodes.where( { parent_id: model.id } ), function ( shortcode ) {
                        this.cloneModel( shortcode, model_clone.get( 'id' ), true );
                    }, this );
                    return model_clone;
                }
            } );
        }


        window.WydeTabsView = vc.shortcode_view.extend({
            new_tab_adding: false,
            events: {
                'click .add_tab': 'addTab',
                'click > .vc_controls .vc_control-btn-delete': 'deleteShortcode',
                'click > .vc_controls .vc_control-btn-edit': 'editElement',
                'click > .vc_controls .vc_control-btn-clone': 'clone'
            },
            initialize: function (params) {
                window.WydeTabsView.__super__.initialize.call(this, params);
                _.bindAll(this, 'stopSorting');
            },
            render: function () {
                window.WydeTabsView.__super__.render.call(this);
                this.$tabs = this.$el.find('.wpb_tabs_holder');
                this.createAddTabButton();
                return this;
            },
            ready: function (e) {
                window.WydeTabsView.__super__.ready.call(this, e);
            },
            createAddTabButton: function () {
                var new_tab_button_id = (+new Date() + '-' + Math.floor(Math.random() * 11));
                this.$tabs.append('<div id="new-tab-' + new_tab_button_id + '" class="new_element_button"></div>');
                this.$add_button = $('<li class="add_tab_block"><a href="#new-tab-' + new_tab_button_id + '" class="add_tab" title="' + window.i18nLocale.add_tab + '"></a></li>').appendTo(this.$tabs.find(".tabs_controls"));
            },
            addTab: function (e) {
                e.preventDefault();
                this.new_tab_adding = true;
                var tab_title = window.i18nLocale.tab,
                tabs_count = this.$tabs.find('[data-element_type="wyde_tab"]').length,
                tab_id = (+new Date() + '-' + tabs_count + '-' + Math.floor(Math.random() * 11));
                vc.shortcodes.create({ shortcode: 'wyde_tab', params: { title: tab_title, tab_id: tab_id }, parent_id: this.model.id });
                return false;
            },
            stopSorting: function (event, ui) {
                var shortcode;
                this.$tabs.find('ul.tabs_controls li:not(.add_tab_block)').each(function (index) {
                    var href = $(this).find('a').attr('href').replace("#", "");
                    // $('#' + href).appendTo(this.$tabs);
                    shortcode = vc.shortcodes.get($('[id="' + $(this).attr('aria-controls') + '"]').data('model-id'));
                    vc.storage.lock();
                    shortcode.save({ 'order': $(this).index() }); // Optimize
                });
                shortcode.save();
            },
            changedContent: function (view) {
                var params = view.model.get('params');
                if (!this.$tabs.hasClass('ui-tabs')) {
                    this.$tabs.tabs({
                        select: function (event, ui) {
                            return !$(ui.tab).hasClass('add_tab');
                        }
                    });
                    this.$tabs.find(".ui-tabs-nav").prependTo(this.$tabs);
                    this.$tabs.find(".ui-tabs-nav").sortable({
                        axis: (this.$tabs.closest('[data-element_type]').data('element_type') == 'wyde_tour' ? 'y' : 'x'),
                        update: this.stopSorting,
                        items: "> li:not(.add_tab_block)"
                    });
                }
                if (view.model.get('cloned') === true) {
                    var cloned_from = view.model.get('cloned_from'),
                    $tab_controls = $('.tabs_controls > .add_tab_block', this.$content),
	                $new_tab = $("<li><a href='#tab-" + params.tab_id + "'>" + params.title + "</a></li>").insertBefore($tab_controls);
                    this.$tabs.tabs('refresh');
                    this.$tabs.tabs("option", 'active', $new_tab.index());
                } else {
                    $("<li><a href='#tab-" + params.tab_id + "'>" + params.title + "</a></li>")
                    .insertBefore(this.$add_button);
                    this.$tabs.tabs('refresh');
                    this.$tabs.tabs("option", "active", this.new_tab_adding ? $('.ui-tabs-nav li', this.$content).length - 2 : 0);

                }
                this.new_tab_adding = false;
            },
            cloneModel: function (model, parent_id, save_order) {
                var shortcodes_to_resort = [],
                new_order = _.isBoolean(save_order) && save_order === true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
                model_clone,
                new_params = _.extend({}, model.get('params'));
                if (model.get('shortcode') === 'wyde_tab') _.extend(new_params, { tab_id: +new Date() + '-' + this.$tabs.find('[data-element-type="wyde_tab"]').length + '-' + Math.floor(Math.random() * 11) });
                model_clone = Shortcodes.create({ shortcode: model.get('shortcode'), id: vc_guid(), parent_id: parent_id, order: new_order, cloned: (model.get('shortcode') !== 'wyde_tab'), cloned_from: model.toJSON(), params: new_params });
                _.each(Shortcodes.where({ parent_id: model.id }), function (shortcode) {
                    this.cloneModel(shortcode, model_clone.get('id'), true);
                }, this);
                return model_clone;
            }
        });

        window.WydeSliderView = vc.shortcode_view.extend({
            new_tab_adding: false,
            events: {
                'click .add_tab': 'addTab',
                'click > .vc_controls .vc_control-btn-delete': 'deleteShortcode',
                'click > .vc_controls .vc_control-btn-edit': 'editElement',
                'click > .vc_controls .vc_control-btn-clone': 'clone'
            },
            initialize: function (params) {
                window.WydeSliderView.__super__.initialize.call(this, params);
                _.bindAll(this, 'stopSorting');
            },
            render: function () {
                window.WydeSliderView.__super__.render.call(this);
                this.$tabs = this.$el.find('.wpb_tabs_holder');
                this.createAddTabButton();
                return this;
            },
            ready: function (e) {
                window.WydeSliderView.__super__.ready.call(this, e);
            },
            createAddTabButton: function () {
                var new_tab_button_id = (+new Date() + '-' + Math.floor(Math.random() * 11));
                this.$tabs.append('<div id="new-tab-' + new_tab_button_id + '" class="new_element_button"></div>');
                this.$add_button = $('<li class="add_tab_block"><a href="#new-tab-' + new_tab_button_id + '" class="add_tab" title="' + window.i18nLocale.add_tab + '"></a></li>').appendTo(this.$tabs.find(".tabs_controls"));
            },
            addTab: function (e) {
                e.preventDefault();
                this.new_tab_adding = true;
                var tabs_count = this.$tabs.find('[data-element_type="wyde_slide"]').length,
                slide_id = (+new Date() + '-' + tabs_count + '-' + Math.floor(Math.random() * 11));
                var tab_title = "Slide "+ (tabs_count + 1);
                vc.shortcodes.create({ shortcode: 'wyde_slide', params: { title: tab_title, slide_id: slide_id }, parent_id: this.model.id });
                return false;
            },
            stopSorting: function (event, ui) {
                var shortcode;
                this.$tabs.find('ul.tabs_controls li:not(.add_tab_block)').each(function (index) {
                    var href = $(this).find('a').attr('href').replace("#", "");
                    // $('#' + href).appendTo(this.$tabs);
                    shortcode = vc.shortcodes.get($('[id="' + $(this).attr('aria-controls') + '"]').data('model-id'));
                    vc.storage.lock();
                    shortcode.save({ 'order': $(this).index() }); // Optimize
                });
                shortcode.save();
            },
            changedContent: function (view) {
                var params = view.model.get('params');
                if (!this.$tabs.hasClass('ui-tabs')) {
                    this.$tabs.tabs({
                        select: function (event, ui) {
                            return !$(ui.tab).hasClass('add_tab');
                        }
                    });
                    this.$tabs.find(".ui-tabs-nav").prependTo(this.$tabs);
                    this.$tabs.find(".ui-tabs-nav").sortable({
                        axis: 'x',
                        update: this.stopSorting,
                        items: "> li:not(.add_tab_block)"
                    });
                }
                if (view.model.get('cloned') === true) {
                    var cloned_from = view.model.get('cloned_from'),
                    $tab_controls = $('.tabs_controls > .add_tab_block', this.$content),
                    $new_tab = $("<li><a href='#slide-" + params.slide_id + "'>Slide " + $('.ui-tabs-nav li', this.$content).length + "</a></li>").insertBefore($tab_controls);
                    this.$tabs.tabs('refresh');
                    this.$tabs.tabs("option", 'active', $new_tab.index());
                } else {
                    $("<li><a href='#slide-" + params.slide_id + "'>Slide " + $('.ui-tabs-nav li', this.$content).length + "</a></li>")
                    .insertBefore(this.$add_button);
                    this.$tabs.tabs('refresh');
                    this.$tabs.tabs("option", "active", this.new_tab_adding ? $('.ui-tabs-nav li', this.$content).length - 2 : 0);

                }
                this.new_tab_adding = false;
            },
            cloneModel: function (model, parent_id, save_order) {
                var shortcodes_to_resort = [],
                new_order = _.isBoolean(save_order) && save_order === true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
                model_clone,
                new_params = _.extend({}, model.get('params'));
                if (model.get('shortcode') === 'wyde_slide') _.extend(new_params, { slide_id: +new Date() + '-' + this.$tabs.find('[data-element-type="wyde_slide"]').length + '-' + Math.floor(Math.random() * 11) });
                model_clone = Shortcodes.create({ shortcode: model.get('shortcode'), id: vc_guid(), parent_id: parent_id, order: new_order, cloned: (model.get('shortcode') !== 'wyde_slide'), cloned_from: model.toJSON(), params: new_params });
                _.each(Shortcodes.where({ parent_id: model.id }), function (shortcode) {
                    this.cloneModel(shortcode, model_clone.get('id'), true);
                }, this);
                return model_clone;
            }
        });
    }

    $.fn.dropit = function(method) {

        var methods = {

            init : function(options) {
                this.dropit.settings = $.extend({}, this.dropit.defaults, options);
                return this.each(function() {
                    var $el = $(this),
                         el = this,
                         settings = $.fn.dropit.settings;

                    // Hide initial submenus
                    $el.addClass('dropit')
                    .find('>'+ settings.triggerParentEl +':has('+ settings.submenuEl +')').addClass('dropit-trigger')
                    .find(settings.submenuEl).addClass('dropit-submenu').hide();

                    // Open on click
                    $el.on(settings.action, settings.triggerParentEl +':has('+ settings.submenuEl +') > '+ settings.triggerEl +'', function(){
                        // Close click menu's if clicked again
                        event.preventDefault();

                        
                        if(settings.action == 'click' && $(this).parents(settings.triggerParentEl).hasClass('dropit-open')){
                            settings.beforeHide.call(this);
                            $(this).parents(settings.triggerParentEl).removeClass('dropit-open').find(settings.submenuEl).hide();
                            settings.afterHide.call(this);
                            $(this).find(".dropit-arrow").removeClass("fa-angle-up").addClass("fa-angle-down");
                            return false;
                        }

                        // Hide open menus
                        settings.beforeHide.call(this);
                        $('.dropit-open').removeClass('dropit-open').find('.dropit-submenu').hide();
                        settings.afterHide.call(this);

                        // Open this menu
                        settings.beforeShow.call(this);
                        $(this).parents(settings.triggerParentEl).addClass('dropit-open').find(settings.submenuEl).show();
                        settings.afterShow.call(this);
                        $(this).find(".dropit-arrow").removeClass("fa-angle-down").addClass("fa-angle-up");

                        return false;
                    });

                    // Close if outside click
                    $(document).on('click', function(){

                        settings.beforeHide.call(this);
                        $('.dropit-open').removeClass('dropit-open').find('.dropit-submenu').hide();
                        settings.afterHide.call(this);
                        $(this).find(".dropit-arrow").removeClass("fa-angle-up").addClass("fa-angle-down");


                    });

                    $(".dropit-submenu").find(settings.triggerEl).on("click", function(event){
                        event.preventDefault();
                        settings.beforeHide.call(this);
                        $('.dropit-open').removeClass('dropit-open').find('.dropit-submenu').hide();
                        settings.afterHide.call(this);
                        settings.afterSelect.call(this, $el);
                        $(this).parents(settings.triggerParentEl).find(".dropit-arrow").removeClass("fa-angle-up").addClass("fa-angle-down");
                        return false;
                    });

                    // If hover
                    if(settings.action == 'mouseenter'){
                        $el.on('mouseleave', function(){
                            settings.beforeHide.call(this);
                            $(this).removeClass('dropit-open').find(settings.submenuEl).hide();
                            settings.afterHide.call(this);
                            $(this).find(".dropit-arrow").removeClass("fa-angle-up").addClass("fa-angle-down");
                        });
                    }

                    settings.afterLoad.call(this);
                });
            }

        };

        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error( 'Method "' +  method + '" does not exist in dropit plugin!');
        }
    };

    $.fn.dropit.defaults = {
        action: 'click', // The open action for the trigger
        submenuEl: 'ul', // The submenu element
        triggerEl: 'a', // The trigger element
        triggerParentEl: 'li', // The trigger parent element
        afterLoad: function(){}, // Triggers when plugin has loaded
        beforeShow: function(){}, // Triggers before submenu is shown
        afterShow: function(){}, // Triggers after submenu is shown
        beforeHide: function(){}, // Triggers before submenu is hidden
        afterHide: function(){}, // Triggers before submenu is hidden
        afterSelect: function(){} // Triggers before submenu is hidden
    };

    $.fn.dropit.settings = {};

    $(".wyde-icons").each(function () {
        var el = this;
        $(".list-icons", this).dropit({
            afterSelect: function (o) {
                $(".selected-value").html($(this).html());
                $(el).find("input.wyde_icons_field").val($("i", this).attr("class"));
            }
        });
    });


})(jQuery);