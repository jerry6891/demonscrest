(function ($) {
    "use strict";

    //Handles icon selector
    function pixflow_IconSelect() {
        'use strict';
        var $menuIcons = $('.px-input-icon');

        $menuIcons.each(function(key,val){

            var $input = $(this).find('input[name*="menu-item-icon"]'),
                value  = $input.attr('value'),
                $icons = $(this).find('.px-icon');


            //get the previous value and show it
            if(value.length){
                $(this).find('.px-icon[data-name='+value+']').addClass('selected');
            }

            $icons.click(function(){
                var $currentInput = $(this).siblings('input');
                $currentInput.attr('value',$(this).attr('data-name'));
                $icons.removeClass('selected');
                $(this).addClass('selected');
            });



        });
    }

    function pixflow_showIcons(){
        'use strict';
        var $iconsCheck = $('input[name *= "show_icon" ]');

        if( !$iconsCheck.length ) return;

        $iconsCheck.each(function(){

            var $next = $(this).parents('.field-show-icon').next('.field-icon');

            if ( $(this).is(':checked') )
                $next.slideDown('fast');
            else
                $next.slideUp('fast');


            $(this).click(function (){
                if ( $(this).is(':checked') )
                    $next.slideDown('fast');
                else
                    $next.slideUp('fast');
            })
        });


    }

    function pixflow_showMegaBg(){
        'use strict';
        var $megaCheck = $('input[name *= "megaOpt" ]');
        if( !$megaCheck.length ) return;
        $megaCheck.each(function(){
            var $next = $(this).parents('.field-mega-menu').find('.field-mega-menu-bg');
            if ( $(this).is(':checked') )
                $next.slideDown('fast');
            else
                $next.slideUp('fast');
            $(this).click(function (){
                if ( $(this).is(':checked') )
                    $next.slideDown('fast');
                else
                    $next.slideUp('fast');
            })
        });
    }

    //run icon select and show icon in ajax call
    function pixflow_menuUpdate(){
        'use strict';

        var $ul = $('.menu-edit  ul.menu');

        if ( ! $ul .length )
            return;

        ! function () {
            var ulContent;
            $(document).ajaxStop(function () {
                if(ulContent !== $ul.html()){
                    ulContent = $ul.html();
                    $ul.trigger('contentChanged');
                }
            });
        }();

        $ul.on('contentChanged',function(){

            pixflow_IconSelect();
            pixflow_showIcons();
            pixflow_showMegaBg();

        });
    }


    function pixflow_customShortcodesJs() {
        'use strict';
        try {
            if (_.isUndefined(window.vc)) {
                window.vc = {};
            }
        }catch (e){}

        //Backend
        try{
/*------------------------------Backend Accordion-----------------*/
            window.MdAccordionView = vc.shortcode_view.extend( {
                adding_new_tab: false,
                events: {
                    'click .add_tab': 'addTab',
                    'click > .vc_controls .column_delete, > .vc_controls .vc_control-btn-delete': 'deleteShortcode',
                    'click > .vc_controls .column_edit, > .vc_controls .vc_control-btn-edit': 'editElement',
                    'click > .vc_controls .column_clone,> .vc_controls .vc_control-btn-clone': 'clone'
                },
                render: function () {
                    window.MdAccordionView.__super__.render.call( this );
                    // check user role to add controls
                    if ( ! this.hasUserAccess() ) {
                        return this;
                    }
                    this.$content.sortable( {
                        axis: "y",
                        handle: "h3",
                        stop: function ( event, ui ) {
                            // IE doesn't register the blur when sorting
                            // so trigger focusout handlers to remove .ui-state-focus
                            ui.item.prev().triggerHandler( "focusout" );
                            $( this ).find( '> .wpb_sortable' ).each( function () {
                                var shortcode = $( this ).data( 'model' );
                                shortcode.save( { 'order': $( this ).index() } ); // Optimize
                            } );
                        }
                    } );
                    return this;
                },
                changeShortcodeParams: function ( model ) {
                    var params, collapsible;

                    window.MdAccordionView.__super__.changeShortcodeParams.call( this, model );
                    params = model.get( 'params' );
                    collapsible = _.isString( params.collapsible ) && params.collapsible === 'yes' ? true : false;
                    if ( this.$content.hasClass( 'ui-accordion' ) ) {
                        this.$content.accordion( "option", "collapsible", collapsible );
                    }
                },
                changedContent: function ( view ) {
                    if ( this.$content.hasClass( 'ui-accordion' ) ) {
                        this.$content.accordion( 'destroy' );
                    }
                    var collapsible = _.isString( this.model.get( 'params' ).collapsible ) && this.model.get( 'params' ).collapsible === 'yes' ? true : false;
                    this.$content.accordion( {
                        header: "h3",
                        navigation: false,
                        autoHeight: true,
                        heightStyle: "content",
                        collapsible: collapsible,
                        active: this.adding_new_tab === false && view.model.get( 'cloned' ) !== true ? 0 : view.$el.index()
                    } );
                    this.adding_new_tab = false;
                },
                addTab: function ( e ) {
                    e.preventDefault();
                    // check user role to add controls
                    if ( ! this.hasUserAccess() ) {
                        return false;
                    }
                    this.adding_new_tab = true;
                    vc.shortcodes.create( {
                        shortcode: 'md_accordion_tab',
                        params: { title: window.i18nLocale.section },
                        parent_id: this.model.id
                    } );
                },
                _loadDefaults: function () {
                    window.MdAccordionView.__super__._loadDefaults.call( this );
                }
            } );


            window.MdAccordionTabView = window.VcColumnView.extend( {
                events: {
                    'click > .vc_controls .vc_control-btn-delete': 'deleteShortcode',
                    'click > .vc_controls .vc_control-btn-prepend': 'addElement',
                    'click > .vc_controls .vc_control-btn-edit': 'editElement',
                    'click > .vc_controls .vc_control-btn-clone': 'clone',
                    'click > .wpb_accordion_content .vc_empty-container': 'prependElement'
                },
                setContent: function () {
                    this.$content = this.$el.find( '.wpb_accordion_content .vc_empty-container' );
                    this.$content.click(function(e){
                        $(this).closest('.wpb_md_accordion_tab').find('.vc_control-btn-prepend').click()
                    })
                },
                changeShortcodeParams: function ( model ) {
                    var params;

                    window.MdAccordionTabView.__super__.changeShortcodeParams.call( this, model );
                    params = model.get( 'params' );
                    if ( _.isObject( params ) && _.isString( params.title ) ) {
                        this.$el.find( 'h3 .md-accordion-tab-title' ).text( params.title );
                    }
                    if ( _.isObject( params ) && _.isString( params.icon ) ) {
                        this.$el.find( 'h3 .icon').removeAttr('class').addClass('icon').addClass(params.icon);
                    }
                },
                setEmpty: function () {
                    $( ' [data-element_type]', this.$el ).addClass( 'vc_empty-column' );
                    this.$content.addClass( 'vc_empty-container' );
                    this.$content.click(function(e){
                        $(this).closest('.wpb_md_accordion_tab').find('.vc_control-btn-prepend').click()
                    })
                },
                unsetEmpty: function () {
                    $( ' [data-element_type]', this.$el ).removeClass( 'vc_empty-column' );
                    this.$content.removeClass( 'vc_empty-container' );
                    this.$content.unbind('click');
                }
            } );

            /*------------------Backend Toggle ---------------------------*/

            window.MdToggleView = vc.shortcode_view.extend( {
                adding_new_tab: false,
                events: {
                    'click .add_tab': 'addTab',
                    'click > .vc_controls .column_delete, > .vc_controls .vc_control-btn-delete': 'deleteShortcode',
                    'click > .vc_controls .column_edit, > .vc_controls .vc_control-btn-edit': 'editElement',
                    'click > .vc_controls .column_clone,> .vc_controls .vc_control-btn-clone': 'clone'
                },
                render: function () {
                    window.MdToggleView.__super__.render.call( this );
                    // check user role to add controls
                    if ( ! this.hasUserAccess() ) {
                        return this;
                    }
                    this.$content.sortable( {
                        axis: "y",
                        handle: "h3",
                        stop: function ( event, ui ) {
                            // IE doesn't register the blur when sorting
                            // so trigger focusout handlers to remove .ui-state-focus
                            ui.item.prev().triggerHandler( "focusout" );
                            $( this ).find( '> .wpb_sortable' ).each( function () {
                                var shortcode = $( this ).data( 'model' );
                                shortcode.save( { 'order': $( this ).index() } ); // Optimize
                            } );
                        }
                    } );
                    return this;
                },
                changeShortcodeParams: function ( model ) {
                    var params, collapsible;

                    window.MdToggleView.__super__.changeShortcodeParams.call( this, model );
                    params = model.get( 'params' );
                    collapsible = _.isString( params.collapsible ) && params.collapsible === 'yes' ? true : false;
                    if ( this.$content.hasClass( 'ui-accordion' ) ) {
                        //this.$content.multiAccordion( "option", "collapsible", collapsible );
                    }
                },
                changedContent: function ( view ) {
                    var collapsible = _.isString( this.model.get( 'params' ).collapsible ) && this.model.get( 'params' ).collapsible === 'yes' ? true : false;

                    this.adding_new_tab = false;
                },
                addTab: function ( e ) {
                    e.preventDefault();
                    // check user role to add controls
                    if ( ! this.hasUserAccess() ) {
                        return false;
                    }
                    this.adding_new_tab = true;
                    vc.shortcodes.create( {
                        shortcode: 'md_toggle_tab',
                        params: { title: window.i18nLocale.section },
                        parent_id: this.model.id
                    } );
                },
                _loadDefaults: function () {
                    window.MdToggleView.__super__._loadDefaults.call( this );
                }
            } );

            window.MdToggleTabView = window.VcColumnView.extend( {
                events: {
                    'click > .vc_controls .vc_control-btn-delete': 'deleteShortcode',
                    'click > .vc_controls .vc_control-btn-prepend': 'addElement',
                    'click > .vc_controls .vc_control-btn-edit': 'editElement',
                    'click > .vc_controls .vc_control-btn-clone': 'clone',
                    'click > .wpb_accordion_content .vc_empty-container': 'prependElement'
                },
                setContent: function () {
                    this.$content = this.$el.find( '.wpb_toggle_content .vc_empty-container' );
                    this.$content.click(function(e){
                        $(this).closest('.wpb_md_toggle_tab').find('.vc_control-btn-prepend').click();

                    })
                    this.$el.find('.wpb_element_wrapper > h3').click(function(){
                        $(this).parent().find(' > .wpb_toggle_content ').slideToggle();
                        if ($(this).hasClass('ui-state-active')) {
                            $(this).removeClass('ui-state-active').find('.ui-icon-triangle-1-e').removeClass('ui-icon-triangle-1-e').addClass('ui-icon-triangle-1-s');
                        } else {
                            $(this).addClass('ui-state-active').find('.ui-icon-triangle-1-s').removeClass('ui-icon-triangle-1-s').addClass('ui-icon-triangle-1-e');
                        }
                    })
                },
                changeShortcodeParams: function ( model ) {
                    var params;

                    window.MdToggleTabView.__super__.changeShortcodeParams.call( this, model );
                    params = model.get( 'params' );
                    if ( _.isObject( params ) && _.isString( params.title ) ) {
                        this.$el.find( 'h3 .md-toggle-tab-title' ).text( params.title );
                    }
                    if ( _.isObject( params ) && _.isString( params.icon ) ) {
                        this.$el.find( 'h3 .icon').removeAttr('class').addClass('icon').addClass(params.icon);
                    }
                },
                setEmpty: function () {
                    $( ' [data-element_type]', this.$el ).addClass( 'vc_empty-column' );
                    this.$content.addClass( 'vc_empty-container' );
                    this.$content.click(function(e){
                        $(this).closest('.wpb_md_toggle_tab').find('.vc_control-btn-prepend').click()
                    })
                },
                unsetEmpty: function () {
                    $( ' [data-element_type]', this.$el ).removeClass( 'vc_empty-column' );
                    this.$content.removeClass( 'vc_empty-container' );
                    this.$content.unbind('click');
                }
            } );

            /*------------------Backend Business Toggle ---------------------------*/

            window.MdToggle2View = vc.shortcode_view.extend( {
                adding_new_tab: false,
                events: {
                    'click .add_tab': 'addTab',
                    'click > .vc_controls .column_delete, > .vc_controls .vc_control-btn-delete': 'deleteShortcode',
                    'click > .vc_controls .column_edit, > .vc_controls .vc_control-btn-edit': 'editElement',
                    'click > .vc_controls .column_clone,> .vc_controls .vc_control-btn-clone': 'clone'
                },
                render: function () {
                    window.MdToggle2View.__super__.render.call( this );
                    // check user role to add controls
                    if ( ! this.hasUserAccess() ) {
                        return this;
                    }
                    this.$content.sortable( {
                        axis: "y",
                        handle: "h3",
                        stop: function ( event, ui ) {
                            // IE doesn't register the blur when sorting
                            // so trigger focusout handlers to remove .ui-state-focus
                            ui.item.prev().triggerHandler( "focusout" );
                            $( this ).find( '> .wpb_sortable' ).each( function () {
                                var shortcode = $( this ).data( 'model' );
                                shortcode.save( { 'order': $( this ).index() } ); // Optimize
                            } );
                        }
                    } );
                    return this;
                },
                changeShortcodeParams: function ( model ) {
                    var params, collapsible;

                    window.MdToggle2View.__super__.changeShortcodeParams.call( this, model );
                    params = model.get( 'params' );
                    collapsible = _.isString( params.collapsible ) && params.collapsible === 'yes' ? true : false;
                    if ( this.$content.hasClass( 'ui-accordion' ) ) {
                        //this.$content.multiAccordion( "option", "collapsible", collapsible );
                    }
                },
                changedContent: function ( view ) {
                    var collapsible = _.isString( this.model.get( 'params' ).collapsible ) && this.model.get( 'params' ).collapsible === 'yes' ? true : false;

                    this.adding_new_tab = false;
                },
                addTab: function ( e ) {
                    e.preventDefault();
                    // check user role to add controls
                    if ( ! this.hasUserAccess() ) {
                        return false;
                    }
                    this.adding_new_tab = true;
                    vc.shortcodes.create( {
                        shortcode: 'md_toggle_tab2',
                        params: { title: window.i18nLocale.section },
                        parent_id: this.model.id
                    } );
                },
                _loadDefaults: function () {
                    window.MdToggle2View.__super__._loadDefaults.call( this );
                }
            } );

            window.MdToggleTab2View = window.VcColumnView.extend( {
                events: {
                    'click > .vc_controls .vc_control-btn-delete': 'deleteShortcode',
                    'click > .vc_controls .vc_control-btn-prepend': 'addElement',
                    'click > .vc_controls .vc_control-btn-edit': 'editElement',
                    'click > .vc_controls .vc_control-btn-clone': 'clone',
                    'click > .wpb_accordion_content .vc_empty-container': 'prependElement'
                },
                setContent: function () {
                    this.$content = this.$el.find( '.wpb_toggle_content .vc_empty-container' );
                    this.$content.click(function(e){
                        $(this).closest('.wpb_md_toggle_tab2').find('.vc_control-btn-prepend').click();

                    })
                    this.$el.find('.wpb_element_wrapper > h3').click(function(){
                        $(this).parent().find(' > .wpb_toggle_content ').slideToggle();
                        if ($(this).hasClass('ui-state-active')) {
                            $(this).removeClass('ui-state-active').find('.ui-icon-triangle-1-e').removeClass('ui-icon-triangle-1-e').addClass('ui-icon-triangle-1-s');
                        } else {
                            $(this).addClass('ui-state-active').find('.ui-icon-triangle-1-s').removeClass('ui-icon-triangle-1-s').addClass('ui-icon-triangle-1-e');
                        }
                    })
                },
                changeShortcodeParams: function ( model ) {
                    var params;

                    window.MdToggleTab2View.__super__.changeShortcodeParams.call( this, model );
                    params = model.get( 'params' );
                    if ( _.isObject( params ) && _.isString( params.title ) ) {
                        this.$el.find( 'h3 .md-toggle-tab2-title' ).text( params.title );
                    }
                    if ( _.isObject( params ) && _.isString( params.icon ) ) {
                        this.$el.find( 'h3 .icon').removeAttr('class').addClass('icon').addClass(params.icon);
                    }
                },
                setEmpty: function () {
                    $( ' [data-element_type]', this.$el ).addClass( 'vc_empty-column' );
                    this.$content.addClass( 'vc_empty-container' );
                    this.$content.click(function(e){
                        $(this).closest('.wpb_md_toggle_tab2').find('.vc_control-btn-prepend').click()
                    })
                },
                unsetEmpty: function () {
                    $( ' [data-element_type]', this.$el ).removeClass( 'vc_empty-column' );
                    this.$content.removeClass( 'vc_empty-container' );
                    this.$content.unbind('click');
                }
            } );
/*----------------------------Backend Tabs---------------------------*/
            window.MdTabsView = vc.shortcode_view.extend( {
                new_tab_adding: false,
                events: {
                    'click .add_tab': 'addTab',
                    'click > .vc_controls .vc_control-btn-delete': 'deleteShortcode',
                    'click > .vc_controls .vc_control-btn-edit': 'editElement',
                    'click > .vc_controls .vc_control-btn-clone': 'clone'
                },
                initialize: function ( params ) {
                    window.MdTabsView.__super__.initialize.call( this, params );
                    _.bindAll( this, 'stopSorting' );
                },
                render: function () {

                    window.MdTabsView.__super__.render.call( this );
                    this.$tabs = this.$el.find( '.wpb_tabs_holder' );
                    this.createAddTabButton();
                    return this;
                },
                ready: function ( e ) {
                    window.MdTabsView.__super__.ready.call( this, e );
                },
                createAddTabButton: function () {
                    var new_tab_button_id = (+ new Date() + '-' + Math.floor( Math.random() * 11 ));
                    this.$tabs.append( '<div id="new-tab-' + new_tab_button_id + '" class="new_element_button"></div>' );
                    this.$add_button = $( '<li class="add_tab_block"><a href="#new-tab-' + new_tab_button_id + '" class="add_tab" title="' + window.i18nLocale.add_tab + '"></a></li>' ).appendTo( this.$tabs.find( ".tabs_controls" ) );
                },
                addTab: function ( e ) {
                    e.preventDefault();
                    // check user role to add controls
                    if ( ! this.hasUserAccess() ) {
                        return false;
                    }
                    this.new_tab_adding = true;
                    var tab_title      = window.i18nLocale.tab,
                        tab_icon_class ='icon-cog',
                        tabs_count     = this.$tabs.find( '[data-element_type=md_tab]' ).length,
                        tab_id         = (+ new Date() + '-' + tabs_count + '-' + Math.floor( Math.random() * 11 ));
                    vc.shortcodes.create( {
                        shortcode: 'md_tab',
                        params: { title: tab_title, tab_id: tab_id},
                        parent_id: this.model.id
                    } );
                    return false;
                },
                stopSorting: function ( event, ui ) {
                    var shortcode;
                    this.$tabs.find( 'ul.tabs_controls li:not(.add_tab_block)' ).each( function ( index ) {
                        var href = $( this ).find( 'a' ).attr( 'href' ).replace( "#", "" );
                        shortcode = vc.shortcodes.get( $( '[id=' + $( this ).attr( 'aria-controls' ) + ']' ).data( 'model-id' ) );
                        vc.storage.lock();
                        shortcode.save( { 'order': $( this ).index() } ); // Optimize
                    } );
                    shortcode && shortcode.save();
                },
                changedContent: function ( view ) {
                    var params = view.model.get( 'params' );
                    if ( ! this.$tabs.hasClass( 'ui-tabs' ) ) {
                        this.$tabs.tabs( {
                            select: function ( event, ui ) {
                                return ! $( ui.tab ).hasClass( 'add_tab' );
                            }
                        } );
                        this.$tabs.find( ".ui-tabs-nav" ).prependTo( this.$tabs );
                        // check user role to add controls
                        if ( this.hasUserAccess() ) {
                            this.$tabs.find( ".ui-tabs-nav" ).sortable( {
                                axis: (this.$tabs.closest( '[data-element_type]' ).data( 'element_type' ) == 'vc_tour' ? 'y' : 'x'),
                                update: this.stopSorting,
                                items: "> li:not(.add_tab_block)"
                            } );
                        }
                    }

                    if ( view.model.get( 'cloned' ) === true ) {
                        var cloned_from = view.model.get( 'cloned_from' ),
                            $tab_controls = $( '.tabs_controls > .add_tab_block', this.$content ),
                            $new_tab = $( "<li><i class='left-icon "+params.tab_icon_class+"'></i><a href='#tab-" + params.tab_id + "'>" + params.title + "</a></li>" ).insertBefore( $tab_controls );
                        this.$tabs.tabs( 'refresh' );
                        this.$tabs.tabs( "option", 'active', $new_tab.index() );
                    } else {
                        $( "<li><i class='left-icon "+params.tab_icon_class+"'></i><a href='#tab-" + params.tab_id + "'>" + params.title + "</a></li>" )
                            .insertBefore( this.$add_button );
                        this.$tabs.tabs( 'refresh' );
                        this.$tabs.tabs( "option",
                            "active",
                            this.new_tab_adding ? $( '.ui-tabs-nav li', this.$content ).length - 2 : 0 );

                    }
                    this.new_tab_adding = false;
                },
                cloneModel: function ( model, parent_id, save_order ) {
                    var new_order,
                        model_clone,
                        params,
                        tag;

                    new_order = _.isBoolean( save_order ) && save_order === true ? model.get( 'order' ) : parseFloat( model.get( 'order' ) ) + vc.clone_index;
                    params = _.extend( {}, model.get( 'params' ) );
                    tag = model.get( 'shortcode' );

                    if ( tag === 'md_tab' ) {
                        _.extend( params,
                            { tab_id: + new Date() + '-' + this.$tabs.find( '[data-element-type=md_tab]' ).length + '-' + Math.floor( Math.random() * 11 ) } );
                    }

                    model_clone = Shortcodes.create( {
                        shortcode: tag,
                        id: vc_guid(),
                        parent_id: parent_id,
                        order: new_order,
                        cloned: (tag !== 'md_tab'), // todo review this by @say2me
                        cloned_from: model.toJSON(),
                        params: params
                    } );

                    _.each( Shortcodes.where( { parent_id: model.id } ), function ( shortcode ) {
                        this.cloneModel( shortcode, model_clone.get( 'id' ), true );
                    }, this );
                    return model_clone;
                }
            } );


            window.MdTabView = window.VcColumnView.extend( {
                events: {
                    'click > .vc_controls .vc_control-btn-delete': 'deleteShortcode',
                    'click > .vc_controls .vc_control-btn-prepend': 'addElement',
                    'click > .vc_controls .vc_control-btn-edit': 'editElement',
                    'click > .vc_controls .vc_control-btn-clone': 'clone',
                    'click > .wpb_element_wrapper > .vc_empty-container': 'addToEmpty'
                },
                render: function () {
                    var params = this.model.get( 'params' );

                    window.MdTabView.__super__.render.call( this );
                    /**
                     * @deprecated 4.4.3
                     * @see composer-atts.js vc.atts.tab_id.addShortcode
                     */
                    if ( ! params.tab_id) {
                        params.tab_id = (+ new Date() + '-' + Math.floor( Math.random() * 11 ));
                        this.model.save( 'params', params );
                    }
                    this.id = 'tab-' + params.tab_id;
                    this.$el.attr( 'id', this.id );
                    return this;
                },
                ready: function ( e ) {
                    window.MdTabView.__super__.ready.call( this, e );
                    this.$tabs = this.$el.closest( '.wpb_tabs_holder' );
                    var params = this.model.get( 'params' );
                    return this;
                },
                changeShortcodeParams: function ( model ) {
                    var params;

                    window.MdTabView.__super__.changeShortcodeParams.call( this, model );
                    params = model.get( 'params' );
                    if ( _.isObject( params ) && _.isString( params.title ) && _.isString( params.tab_id ) ) {

                        $( '.ui-tabs-nav [href="#tab-' + params.tab_id + '"]' ).text( params.title );

                        $( '.ui-tabs-nav [href="#tab-' + params.tab_id + ']"').prev('i.left-icon').removeAttr('class');
                        $( '.ui-tabs-nav [href="#tab-' + params.tab_id + '"]').prev().addClass( 'left-icon '+ params.tab_icon_class );
                    }
                },
                deleteShortcode: function ( e ) {
                    _.isObject( e ) && e.preventDefault();
                    var answer = confirm( window.i18nLocale.press_ok_to_delete_section ),
                        parent_id = this.model.get( 'parent_id' );
                    if ( answer !== true ) {
                        return false;
                    }
                    this.model.destroy();
                    if ( ! vc.shortcodes.where( { parent_id: parent_id } ).length ) {
                        var parent = vc.shortcodes.get( parent_id );
                        parent.destroy();
                        return false;
                    }
                    var params = this.model.get( 'params' ),
                        current_tab_index = $( '[href=#tab-' + params.tab_id + ']', this.$tabs ).parent().index();
                    $( '[href=#tab-' + params.tab_id + ']' ).parent().remove();
                    var tab_length = this.$tabs.find( '.ui-tabs-nav li:not(.add_tab_block)' ).length;
                    if ( tab_length > 0 ) {
                        this.$tabs.tabs( 'refresh' );
                    }
                    if ( current_tab_index < tab_length ) {
                        this.$tabs.tabs( "option", "active", current_tab_index );
                    } else if ( tab_length > 0 ) {
                        this.$tabs.tabs( "option", "active", tab_length - 1 );
                    }

                },
                cloneModel: function ( model, parent_id, save_order ) {
                    var new_order,
                        model_clone,
                        params,
                        tag;

                    new_order = _.isBoolean( save_order ) && save_order === true ? model.get( 'order' ) : parseFloat( model.get( 'order' ) ) + vc.clone_index;
                    params = _.extend( {}, model.get( 'params' ) );
                    tag = model.get( 'shortcode' );

                    if ( tag === 'md_tab' ) {
                        _.extend( params,
                            { tab_id: + new Date() + '-' + this.$tabs.find( '[data-element_type=md_tab]' ).length + '-' + Math.floor( Math.random() * 11 ) } );
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

            /*----------------------------Backend Modern Tabs---------------------------*/
            window.MdModernTabsView = vc.shortcode_view.extend( {
                new_tab_adding: false,
                events: {
                    'click .add_tab': 'addTab',
                    'click > .vc_controls .vc_control-btn-delete': 'deleteShortcode',
                    'click > .vc_controls .vc_control-btn-edit': 'editElement',
                    'click > .vc_controls .vc_control-btn-clone': 'clone'
                },
                initialize: function ( params ) {
                    window.MdModernTabsView.__super__.initialize.call( this, params );
                    _.bindAll( this, 'stopSorting' );
                },
                render: function () {

                    window.MdModernTabsView.__super__.render.call( this );
                    this.$tabs = this.$el.find( '.wpb_tabs_holder' );
                    this.createAddTabButton();
                    return this;
                },
                ready: function ( e ) {
                    window.MdModernTabsView.__super__.ready.call( this, e );
                },
                createAddTabButton: function () {
                    var new_tab_button_id = (+ new Date() + '-' + Math.floor( Math.random() * 11 ));
                    this.$tabs.append( '<div id="new-tab-' + new_tab_button_id + '" class="new_element_button"></div>' );
                    this.$add_button = $( '<li class="add_tab_block"><a href="#new-tab-' + new_tab_button_id + '" class="add_tab" title="' + window.i18nLocale.add_tab + '"></a></li>' ).appendTo( this.$tabs.find( ".tabs_controls" ) );
                },
                addTab: function ( e ) {
                    e.preventDefault();
                    // check user role to add controls
                    if ( ! this.hasUserAccess() ) {
                        return false;
                    }
                    this.new_tab_adding = true;
                    var tab_title      = window.i18nLocale.tab,
                        tab_icon_class ='icon-cog',
                        tabs_count     = this.$tabs.find( '[data-element_type=md_modernTab]' ).length,
                        tab_id         = (+ new Date() + '-' + tabs_count + '-' + Math.floor( Math.random() * 11 ));
                    vc.shortcodes.create( {
                        shortcode: 'md_modernTab',
                        params: { title: tab_title, tab_id: tab_id},
                        parent_id: this.model.id
                    } );
                    return false;
                },
                stopSorting: function ( event, ui ) {
                    var shortcode;
                    this.$tabs.find( 'ul.tabs_controls li:not(.add_tab_block)' ).each( function ( index ) {
                        var href = $( this ).find( 'a' ).attr( 'href' ).replace( "#", "" );
                        shortcode = vc.shortcodes.get( $( '[id=' + $( this ).attr( 'aria-controls' ) + ']' ).data( 'model-id' ) );
                        vc.storage.lock();
                        shortcode.save( { 'order': $( this ).index() } ); // Optimize
                    } );
                    shortcode && shortcode.save();
                },
                changedContent: function ( view ) {
                    var params = view.model.get( 'params' );

                    if ( ! this.$tabs.hasClass( 'ui-tabs' ) ) {
                        this.$tabs.tabs( {
                            select: function ( event, ui ) {
                                return ! $( ui.tab ).hasClass( 'add_tab' );
                            }
                        } );
                        this.$tabs.find( ".ui-tabs-nav" ).prependTo( this.$tabs );
                        // check user role to add controls
                        if ( this.hasUserAccess() ) {
                            this.$tabs.find( ".ui-tabs-nav" ).sortable( {
                                axis: (this.$tabs.closest( '[data-element_type]' ).data( 'element_type' ) == 'vc_tour' ? 'y' : 'x'),
                                update: this.stopSorting,
                                items: "> li:not(.add_tab_block)"
                            } );
                        }
                    }

                    if ( view.model.get( 'cloned' ) === true ) {
                        var cloned_from = view.model.get( 'cloned_from' ),
                            $tab_controls = $( '.tabs_controls > .add_tab_block', this.$content ),
                            $new_tab = $( "<li><a href='#tab-" + params.tab_id + "'><i class='left-icon "+params.tab_icon_class+"'></i><div class='modernTabTitle'>" + params.title + "</div></a></li>" ).insertBefore( $tab_controls );
                        this.$tabs.tabs( 'refresh' );
                        this.$tabs.tabs( "option", 'active', $new_tab.index() );
                    } else {
                        $( "<li><a href='#tab-" + params.tab_id + "'><i class='left-icon "+params.tab_icon_class+"'></i><div class='modernTabTitle'>" + params.title + "</div></a></li>" )
                            .insertBefore( this.$add_button );
                        this.$tabs.tabs( 'refresh' );
                        this.$tabs.tabs( "option",
                            "active",
                            this.new_tab_adding ? $( '.ui-tabs-nav li', this.$content ).length - 2 : 0 );

                    }
                    this.new_tab_adding = false;
                },
                cloneModel: function ( model, parent_id, save_order ) {
                    var new_order,
                        model_clone,
                        params,
                        tag;

                    new_order = _.isBoolean( save_order ) && save_order === true ? model.get( 'order' ) : parseFloat( model.get( 'order' ) ) + vc.clone_index;
                    params = _.extend( {}, model.get( 'params' ) );
                    tag = model.get( 'shortcode' );

                    if ( tag === 'md_modernTab' ) {
                        _.extend( params,
                            { tab_id: + new Date() + '-' + this.$tabs.find( '[data-element-type=md_modernTab]' ).length + '-' + Math.floor( Math.random() * 11 ) } );
                    }

                    model_clone = Shortcodes.create( {
                        shortcode: tag,
                        id: vc_guid(),
                        parent_id: parent_id,
                        order: new_order,
                        cloned: (tag !== 'md_modernTab'), // todo review this by @say2me
                        cloned_from: model.toJSON(),
                        params: params
                    } );

                    _.each( Shortcodes.where( { parent_id: model.id } ), function ( shortcode ) {
                        this.cloneModel( shortcode, model_clone.get( 'id' ), true );
                    }, this );
                    return model_clone;
                }
            } );


            window.MdModernTabView = window.VcColumnView.extend( {
                events: {
                    'click > .vc_controls .vc_control-btn-delete': 'deleteShortcode',
                    'click > .vc_controls .vc_control-btn-prepend': 'addElement',
                    'click > .vc_controls .vc_control-btn-edit': 'editElement',
                    'click > .vc_controls .vc_control-btn-clone': 'clone',
                    'click > .wpb_element_wrapper > .vc_empty-container': 'addToEmpty'
                },
                render: function () {
                    var params = this.model.get( 'params' );

                    window.MdModernTabView.__super__.render.call( this );

                    if ( ! params.tab_id) {
                        params.tab_id = (+ new Date() + '-' + Math.floor( Math.random() * 11 ));
                        this.model.save( 'params', params );
                    }
                    this.id = 'tab-' + params.tab_id;
                    this.$el.attr( 'id', this.id );
                    return this;
                },
                ready: function ( e ) {
                    window.MdModernTabView.__super__.ready.call( this, e );
                    this.$tabs = this.$el.closest( '.wpb_tabs_holder' );
                    var params = this.model.get( 'params' );
                    return this;
                },
                changeShortcodeParams: function ( model ) {
                    var params;

                    window.MdModernTabView.__super__.changeShortcodeParams.call( this, model );
                    params = model.get( 'params' );
                    var $html='';
                    if(params.tab_icon_class!='') {
                        $html += "<i class='left-icon " + params.tab_icon_class + "'></i>";
                    }else {
                        $html += "<i class='left-icon'></i>";
                    }
                    if(params.title!='') {
                        $html += "<div class='modernTabTitle'>" + params.title + '</div>';
                    }else {
                        $html += "<div class='modernTabTitle'></div>";
                    }

                    if ( _.isObject( params ) && _.isString( params.title ) && _.isString( params.tab_id ) ) {
                        $( '.ui-tabs-nav [href="#tab-' + params.tab_id + '"]' ).html( $html);
                    }
                },
                deleteShortcode: function ( e ) {
                    _.isObject( e ) && e.preventDefault();
                    var answer = confirm( window.i18nLocale.press_ok_to_delete_section ),
                        parent_id = this.model.get( 'parent_id' );
                    if ( answer !== true ) {
                        return false;
                    }
                    this.model.destroy();
                    if ( ! vc.shortcodes.where( { parent_id: parent_id } ).length ) {
                        var parent = vc.shortcodes.get( parent_id );
                        parent.destroy();
                        return false;
                    }
                    var params = this.model.get( 'params' ),
                        current_tab_index = $( '[href=#tab-' + params.tab_id + ']', this.$tabs ).parent().index();
                    $( '[href=#tab-' + params.tab_id + ']' ).parent().remove();
                    var tab_length = this.$tabs.find( '.ui-tabs-nav li:not(.add_tab_block)' ).length;
                    if ( tab_length > 0 ) {
                        this.$tabs.tabs( 'refresh' );
                    }
                    if ( current_tab_index < tab_length ) {
                        this.$tabs.tabs( "option", "active", current_tab_index );
                    } else if ( tab_length > 0 ) {
                        this.$tabs.tabs( "option", "active", tab_length - 1 );
                    }

                },
                cloneModel: function ( model, parent_id, save_order ) {
                    var new_order,
                        model_clone,
                        params,
                        tag;

                    new_order = _.isBoolean( save_order ) && save_order === true ? model.get( 'order' ) : parseFloat( model.get( 'order' ) ) + vc.clone_index;
                    params = _.extend( {}, model.get( 'params' ) );
                    tag = model.get( 'shortcode' );

                    if ( tag === 'md_modernTab' ) {
                        _.extend( params,
                            { tab_id: + new Date() + '-' + this.$tabs.find( '[data-element_type=md_modernTab]' ).length + '-' + Math.floor( Math.random() * 11 ) } );
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

            /*----------------------------Backend Horizontal Tabs---------------------------*/
            window.MdHorTabsView = vc.shortcode_view.extend( {
                new_tab_adding: false,
                events: {
                    'click .add_tab': 'addTab',
                    'click > .vc_controls .vc_control-btn-delete': 'deleteShortcode',
                    'click > .vc_controls .vc_control-btn-edit': 'editElement',
                    'click > .vc_controls .vc_control-btn-clone': 'clone'
                },
                initialize: function ( params ) {
                    window.MdHorTabsView.__super__.initialize.call( this, params );
                    _.bindAll( this, 'stopSorting' );
                },
                render: function () {
                    window.MdHorTabsView.__super__.render.call( this );
                    this.$tabs = this.$el.find( '.wpb_tabs_holder' );
                    this.createAddTabButton();
                    return this;
                },
                ready: function ( e ) {
                    window.MdHorTabsView.__super__.ready.call( this, e );
                },
                createAddTabButton: function () {
                    var new_tab_button_id = (+ new Date() + '-' + Math.floor( Math.random() * 11 ));
                    this.$tabs.append( '<div id="new-tab-' + new_tab_button_id + '" class="new_element_button"></div>' );
                    this.$add_button = $( '<li class="add_tab_block"><a href="#new-tab-' + new_tab_button_id + '" class="add_tab" title="' + window.i18nLocale.add_tab + '"></a></li>' ).appendTo( this.$tabs.find( ".tabs_controls" ) );
                },
                addTab: function ( e ) {
                    e.preventDefault();
                    // check user role to add controls
                    if ( ! this.hasUserAccess() ) {
                        return false;
                    }
                    this.new_tab_adding = true;
                    var tab_title      = window.i18nLocale.tab,
                        tab_icon ='icon-cog',
                        tabs_count     = this.$tabs.find( '[data-element_type=md_hor_tab]' ).length,
                        tab_id         = (+ new Date() + '-' + tabs_count + '-' + Math.floor( Math.random() * 11 ));
                    vc.shortcodes.create( {
                        shortcode: 'md_hor_tab',
                        params: { title: tab_title, tab_id: tab_id},
                        parent_id: this.model.id
                    } );
                    return false;
                },
                stopSorting: function ( event, ui ) {
                    var shortcode;
                    this.$tabs.find( 'ul.tabs_controls li:not(.add_tab_block)' ).each( function ( index ) {
                        var href = $( this ).find( 'a' ).attr( 'href' ).replace( "#", "" );
                        shortcode = vc.shortcodes.get( $( '[id=' + $( this ).attr( 'aria-controls' ) + ']' ).data( 'model-id' ) );
                        vc.storage.lock();
                        shortcode.save( { 'order': $( this ).index() } ); // Optimize
                    } );
                    shortcode && shortcode.save();
                },
                changedContent: function ( view ) {
                    var params = view.model.get( 'params' );
                    if ( ! this.$tabs.hasClass( 'ui-tabs' ) ) {
                        this.$tabs.tabs( {
                            select: function ( event, ui ) {
                                return ! $( ui.tab ).hasClass( 'add_tab' );
                            }
                        } );
                        this.$tabs.find( ".ui-tabs-nav" ).prependTo( this.$tabs );
                        // check user role to add controls
                        if ( this.hasUserAccess() ) {
                            this.$tabs.find( ".ui-tabs-nav" ).sortable( {
                                axis: (this.$tabs.closest( '[data-element_type]' ).data( 'element_type' ) == 'vc_tour' ? 'y' : 'x'),
                                update: this.stopSorting,
                                items: "> li:not(.add_tab_block)"
                            } );
                        }
                    }
                    if ( view.model.get( 'cloned' ) === true ) {
                        var cloned_from = view.model.get( 'cloned_from' ),
                            $tab_controls = $( '.tabs_controls > .add_tab_block', this.$content ),
                            $new_tab = $( "<li><a href='#tab-" + params.tab_id + "'><div class='horTabTitle'>" + params.title + "</div><i class='right-icon "+params.tab_icon+"'></i></a></li>" ).insertBefore( $tab_controls );
                        this.$tabs.tabs( 'refresh' );
                        this.$tabs.tabs( "option", 'active', $new_tab.index() );
                    } else {
                        $( "<li><a href='#tab-" + params.tab_id + "'><div class='horTabTitle'>" + params.title + "</div><i class='right-icon "+params.tab_icon+"'></i></a></li>" )
                            .insertBefore( this.$add_button );
                        this.$tabs.tabs( 'refresh' );
                        this.$tabs.tabs( "option",
                            "active",
                            this.new_tab_adding ? $( '.ui-tabs-nav li', this.$content ).length - 2 : 0 );
                    }
                    this.new_tab_adding = false;
                },
                cloneModel: function ( model, parent_id, save_order ) {
                    var new_order,
                        model_clone,
                        params,
                        tag;
                    new_order = _.isBoolean( save_order ) && save_order === true ? model.get( 'order' ) : parseFloat( model.get( 'order' ) ) + vc.clone_index;
                    params = _.extend( {}, model.get( 'params' ) );
                    tag = model.get( 'shortcode' );
                    if ( tag === 'md_hor_tab' ) {
                        _.extend( params,
                            { tab_id: + new Date() + '-' + this.$tabs.find( '[data-element-type=md_hor_tab]' ).length + '-' + Math.floor( Math.random() * 11 ) } );
                    }
                    model_clone = Shortcodes.create( {
                        shortcode: tag,
                        id: vc_guid(),
                        parent_id: parent_id,
                        order: new_order,
                        cloned: (tag !== 'md_hor_tab'),
                        cloned_from: model.toJSON(),
                        params: params
                    } );
                    _.each( Shortcodes.where( { parent_id: model.id } ), function ( shortcode ) {
                        this.cloneModel( shortcode, model_clone.get( 'id' ), true );
                    }, this );
                    return model_clone;
                }
            } );

            window.MdHorTabView = window.VcColumnView.extend( {
                events: {
                    'click > .vc_controls .vc_control-btn-delete': 'deleteShortcode',
                    'click > .vc_controls .vc_control-btn-prepend': 'addElement',
                    'click > .vc_controls .vc_control-btn-edit': 'editElement',
                    'click > .vc_controls .vc_control-btn-clone': 'clone',
                    'click > .wpb_element_wrapper > .vc_empty-container': 'addToEmpty'
                },
                render: function () {
                    var params = this.model.get( 'params' );
                    window.MdHorTabView.__super__.render.call( this );
                    if ( ! params.tab_id) {
                        params.tab_id = (+ new Date() + '-' + Math.floor( Math.random() * 11 ));
                        this.model.save( 'params', params );
                    }
                    this.id = 'tab-' + params.tab_id;
                    this.$el.attr( 'id', this.id );
                    return this;
                },
                ready: function ( e ) {
                    window.MdHorTabView.__super__.ready.call( this, e );
                    this.$tabs = this.$el.closest( '.wpb_tabs_holder' );
                    var params = this.model.get( 'params' );
                    return this;
                },
                changeShortcodeParams: function ( model ) {
                    var params;
                    window.MdHorTabView.__super__.changeShortcodeParams.call( this, model );
                    params = model.get( 'params' );
                    var $html='';
                    if(params.title!='') {
                        $html += "<div class='horTabTitle'>" + params.title + '</div>';
                    }else {
                        $html += "<div class='horTabTitle'></div>";
                    }
                    if(params.tab_icon!='') {
                        $html += "<i class='right-icon " + params.tab_icon + "'></i>";
                    }else {
                        $html += "<i class='right-icon'></i>";
                    }
                    if ( _.isObject( params ) && _.isString( params.title ) && _.isString( params.tab_id ) ) {
                        $( '.ui-tabs-nav [href="#tab-' + params.tab_id + '"]' ).html( $html);
                    }
                },
                deleteShortcode: function ( e ) {
                    _.isObject( e ) && e.preventDefault();
                    var answer = confirm( window.i18nLocale.press_ok_to_delete_section ),
                        parent_id = this.model.get( 'parent_id' );
                    if ( answer !== true ) {
                        return false;
                    }
                    this.model.destroy();
                    if ( ! vc.shortcodes.where( { parent_id: parent_id } ).length ) {
                        var parent = vc.shortcodes.get( parent_id );
                        parent.destroy();
                        return false;
                    }
                    var params = this.model.get( 'params' ),
                        current_tab_index = $( '[href=#tab-' + params.tab_id + ']', this.$tabs ).parent().index();
                    $( '[href=#tab-' + params.tab_id + ']' ).parent().remove();
                    var tab_length = this.$tabs.find( '.ui-tabs-nav li:not(.add_tab_block)' ).length;
                    if ( tab_length > 0 ) {
                        this.$tabs.tabs( 'refresh' );
                    }
                    if ( current_tab_index < tab_length ) {
                        this.$tabs.tabs( "option", "active", current_tab_index );
                    } else if ( tab_length > 0 ) {
                        this.$tabs.tabs( "option", "active", tab_length - 1 );
                    }
                },
                cloneModel: function ( model, parent_id, save_order ) {
                    var new_order,
                        model_clone,
                        params,
                        tag;
                    new_order = _.isBoolean( save_order ) && save_order === true ? model.get( 'order' ) : parseFloat( model.get( 'order' ) ) + vc.clone_index;
                    params = _.extend( {}, model.get( 'params' ) );
                    tag = model.get( 'shortcode' );
                    if ( tag === 'md_hor_tab' ) {
                        _.extend( params,
                            { tab_id: + new Date() + '-' + this.$tabs.find( '[data-element_type=md_hor_tab]' ).length + '-' + Math.floor( Math.random() * 11 ) } );
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

            /*----------------------------Backend Horizontal Tabs 2---------------------------*/
            window.MdHorTabs2View = vc.shortcode_view.extend( {
                new_tab_adding: false,
                events: {
                    'click .add_tab': 'addTab',
                    'click > .vc_controls .vc_control-btn-delete': 'deleteShortcode',
                    'click > .vc_controls .vc_control-btn-edit': 'editElement',
                    'click > .vc_controls .vc_control-btn-clone': 'clone'
                },
                initialize: function ( params ) {
                    window.MdHorTabs2View.__super__.initialize.call( this, params );
                    _.bindAll( this, 'stopSorting' );
                },
                render: function () {
                    window.MdHorTabs2View.__super__.render.call( this );
                    this.$tabs = this.$el.find( '.wpb_tabs_holder' );
                    this.createAddTabButton();
                    return this;
                },
                ready: function ( e ) {
                    window.MdHorTabs2View.__super__.ready.call( this, e );
                },
                createAddTabButton: function () {
                    var new_tab_button_id = (+ new Date() + '-' + Math.floor( Math.random() * 11 ));
                    this.$tabs.append( '<div id="new-tab-' + new_tab_button_id + '" class="new_element_button"></div>' );
                    this.$add_button = $( '<li class="add_tab_block"><a href="#new-tab-' + new_tab_button_id + '" class="add_tab" title="' + window.i18nLocale.add_tab + '"></a></li>' ).appendTo( this.$tabs.find( ".tabs_controls" ) );
                },
                addTab: function ( e ) {
                    e.preventDefault();
                    // check user role to add controls
                    if ( ! this.hasUserAccess() ) {
                        return false;
                    }
                    this.new_tab_adding = true;
                    var tab_title      = window.i18nLocale.tab,
                        tab_icon ='icon-cog',
                        tabs_count     = this.$tabs.find( '[data-element_type=md_hor_tab2]' ).length,
                        tab_id         = (+ new Date() + '-' + tabs_count + '-' + Math.floor( Math.random() * 11 ));
                    vc.shortcodes.create( {
                        shortcode: 'md_hor_tab2',
                        params: { title: tab_title, tab_id: tab_id},
                        parent_id: this.model.id
                    } );
                    return false;
                },
                stopSorting: function ( event, ui ) {
                    var shortcode;
                    this.$tabs.find( 'ul.tabs_controls li:not(.add_tab_block)' ).each( function ( index ) {
                        var href = $( this ).find( 'a' ).attr( 'href' ).replace( "#", "" );
                        shortcode = vc.shortcodes.get( $( '[id=' + $( this ).attr( 'aria-controls' ) + ']' ).data( 'model-id' ) );
                        vc.storage.lock();
                        shortcode.save( { 'order': $( this ).index() } ); // Optimize
                    } );
                    shortcode && shortcode.save();
                },
                changedContent: function ( view ) {
                    var params = view.model.get( 'params' );
                    if ( ! this.$tabs.hasClass( 'ui-tabs' ) ) {
                        this.$tabs.tabs( {
                            select: function ( event, ui ) {
                                return ! $( ui.tab ).hasClass( 'add_tab' );
                            }
                        } );
                        this.$tabs.find( ".ui-tabs-nav" ).prependTo( this.$tabs );
                        // check user role to add controls
                        if ( this.hasUserAccess() ) {
                            this.$tabs.find( ".ui-tabs-nav" ).sortable( {
                                axis: (this.$tabs.closest( '[data-element_type]' ).data( 'element_type' ) == 'vc_tour' ? 'y' : 'x'),
                                update: this.stopSorting,
                                items: "> li:not(.add_tab_block)"
                            } );
                        }
                    }
                    if ( view.model.get( 'cloned' ) === true ) {
                        var cloned_from = view.model.get( 'cloned_from' ),
                            $tab_controls = $( '.tabs_controls > .add_tab_block', this.$content ),
                            $new_tab = $( "<li><a href='#tab-" + params.tab_id + "'><div class='horTabTitle'>" + params.title + "</div><i class='right-icon "+params.tab_icon+"'></i></a></li>" ).insertBefore( $tab_controls );
                        this.$tabs.tabs( 'refresh' );
                        this.$tabs.tabs( "option", 'active', $new_tab.index() );
                    } else {
                        $( "<li><a href='#tab-" + params.tab_id + "'><div class='horTabTitle'>" + params.title + "</div><i class='right-icon "+params.tab_icon+"'></i></a></li>" )
                            .insertBefore( this.$add_button );
                        this.$tabs.tabs( 'refresh' );
                        this.$tabs.tabs( "option",
                            "active",
                            this.new_tab_adding ? $( '.ui-tabs-nav li', this.$content ).length - 2 : 0 );
                    }
                    this.new_tab_adding = false;
                },
                cloneModel: function ( model, parent_id, save_order ) {
                    var new_order,
                        model_clone,
                        params,
                        tag;
                    new_order = _.isBoolean( save_order ) && save_order === true ? model.get( 'order' ) : parseFloat( model.get( 'order' ) ) + vc.clone_index;
                    params = _.extend( {}, model.get( 'params' ) );
                    tag = model.get( 'shortcode' );
                    if ( tag === 'md_hor_tab2' ) {
                        _.extend( params,
                            { tab_id: + new Date() + '-' + this.$tabs.find( '[data-element-type=md_hor_tab2]' ).length + '-' + Math.floor( Math.random() * 11 ) } );
                    }
                    model_clone = Shortcodes.create( {
                        shortcode: tag,
                        id: vc_guid(),
                        parent_id: parent_id,
                        order: new_order,
                        cloned: (tag !== 'md_hor_tab2'),
                        cloned_from: model.toJSON(),
                        params: params
                    } );
                    _.each( Shortcodes.where( { parent_id: model.id } ), function ( shortcode ) {
                        this.cloneModel( shortcode, model_clone.get( 'id' ), true );
                    }, this );
                    return model_clone;
                }
            } );

            window.MdHorTab2View = window.VcColumnView.extend( {
                events: {
                    'click > .vc_controls .vc_control-btn-delete': 'deleteShortcode',
                    'click > .vc_controls .vc_control-btn-prepend': 'addElement',
                    'click > .vc_controls .vc_control-btn-edit': 'editElement',
                    'click > .vc_controls .vc_control-btn-clone': 'clone',
                    'click > .wpb_element_wrapper > .vc_empty-container': 'addToEmpty'
                },
                render: function () {
                    var params = this.model.get( 'params' );
                    window.MdHorTab2View.__super__.render.call( this );
                    if ( ! params.tab_id) {
                        params.tab_id = (+ new Date() + '-' + Math.floor( Math.random() * 11 ));
                        this.model.save( 'params', params );
                    }
                    this.id = 'tab-' + params.tab_id;
                    this.$el.attr( 'id', this.id );
                    return this;
                },
                ready: function ( e ) {
                    window.MdHorTab2View.__super__.ready.call( this, e );
                    this.$tabs = this.$el.closest( '.wpb_tabs_holder' );
                    var params = this.model.get( 'params' );
                    return this;
                },
                changeShortcodeParams: function ( model ) {
                    var params;
                    window.MdHorTab2View.__super__.changeShortcodeParams.call( this, model );
                    params = model.get( 'params' );
                    var $html='';
                    if(params.title!='') {
                        $html += "<div class='horTabTitle'>" + params.title + '</div>';
                    }else {
                        $html += "<div class='horTabTitle'></div>";
                    }
                    if(params.tab_icon!='') {
                        $html += "<i class='right-icon " + params.tab_icon + "'></i>";
                    }else {
                        $html += "<i class='right-icon'></i>";
                    }
                    if ( _.isObject( params ) && _.isString( params.title ) && _.isString( params.tab_id ) ) {
                        $( '.ui-tabs-nav [href="#tab-' + params.tab_id + '"]' ).html( $html);
                    }
                },
                deleteShortcode: function ( e ) {
                    _.isObject( e ) && e.preventDefault();
                    var answer = confirm( window.i18nLocale.press_ok_to_delete_section ),
                        parent_id = this.model.get( 'parent_id' );
                    if ( answer !== true ) {
                        return false;
                    }
                    this.model.destroy();
                    if ( ! vc.shortcodes.where( { parent_id: parent_id } ).length ) {
                        var parent = vc.shortcodes.get( parent_id );
                        parent.destroy();
                        return false;
                    }
                    var params = this.model.get( 'params' ),
                        current_tab_index = $( '[href=#tab-' + params.tab_id + ']', this.$tabs ).parent().index();
                    $( '[href=#tab-' + params.tab_id + ']' ).parent().remove();
                    var tab_length = this.$tabs.find( '.ui-tabs-nav li:not(.add_tab_block)' ).length;
                    if ( tab_length > 0 ) {
                        this.$tabs.tabs( 'refresh' );
                    }
                    if ( current_tab_index < tab_length ) {
                        this.$tabs.tabs( "option", "active", current_tab_index );
                    } else if ( tab_length > 0 ) {
                        this.$tabs.tabs( "option", "active", tab_length - 1 );
                    }
                },
                cloneModel: function ( model, parent_id, save_order ) {
                    var new_order,
                        model_clone,
                        params,
                        tag;
                    new_order = _.isBoolean( save_order ) && save_order === true ? model.get( 'order' ) : parseFloat( model.get( 'order' ) ) + vc.clone_index;
                    params = _.extend( {}, model.get( 'params' ) );
                    tag = model.get( 'shortcode' );
                    if ( tag === 'md_hor_tab2' ) {
                        _.extend( params,
                            { tab_id: + new Date() + '-' + this.$tabs.find( '[data-element_type=md_hor_tab2]' ).length + '-' + Math.floor( Math.random() * 11 ) } );
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

        }catch (e){}
    }

    function pixflow_media_upload() {
        'use strict';
        var custom_uploader;

            $(document).on("click", '.custom_media_button',function(e) {

            e.preventDefault();

            //If the uploader object has already been created, reopen the dialog
            if (custom_uploader) {
                custom_uploader.open();
                return;
            }

            //Extend the wp.media object
            custom_uploader = wp.media.frames.file_frame = wp.media({
                title: admin_var.chooseImage,
                button: {
                    text: admin_var.chooseImage
                },
                multiple: false
            });

            //When a file is selected, grab the URL and set it as the text field's value
            custom_uploader.on('select', function() {
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                $('.custom_media_url').val(attachment.url);
            });

            //Open the uploader dialog
            custom_uploader.open();

            return true;
        });
    }

    function pixflow_singlePageLayout (){
        'use strict';
        var $body, $obj,$popup;

        if ( $(window.top.document.getElementsByTagName('body')).hasClass('wp-customizer') ){
            $body = $('iframe#vc_inline-frame').contents().find('body');
            $popup = $('body');
            $obj = $('iframe#vc_inline-frame').contents();
        }else{
            $popup = $body = $obj = $('body');
        }

        if ( !$body.hasClass('single-portfolio') && !$body.hasClass('post-type-portfolio')) {
            $obj.find('.wpb_switch-to-composer').click(function(){
                var $this = $(this);
                if( $this.parent('.composer-switch').hasClass('vc_backend-status') ){
                    $this.text(admin_var.classicMode);
                }else{
                    $this.text(admin_var.backendEditor);
                }
            }).click();

            $obj.find('.vc_default-templates .wpb_row,.vc_default-templates .vc_row-fluid').append(''+
            '<a class="vc_templates-image vc_templates-blank" href="#"><span>'+admin_var.yourStyle+'</span></a>');

            $obj.find('.vc_templates-blank').click(function(){
                if($(this).hasClass('vc_templates-blank')) {
                    $obj.find('#vc_no-content-add-element').click();
                }
                if($(this).attr('target')){
                    $(this).removeAttr('target');
                }
            });

            if($('.composer-switch , .vc_backend-status').length>0)
            {
                $('.composer-switch , .vc_backend-status').append('<a href="'+admin_var.customizerUrl+'" class="wpb_switch-to-customizer">'+admin_var.massiveBuilder+'</a>');
            }
            else
            {
                if(admin_var.showButton == 'yes'){
                    $("#titlediv").append('<div class="composer-switch vc_backend-status"><a href="'+admin_var.customizerUrl+'" class="wpb_switch-to-customizer">'+admin_var.massiveBuilder+'</a><div>');
                }
            }



            return;
        }


        $obj.find('.vc_welcome-header').html('<h5>'+admin_var.portfolioPostLayout+'</h5>');
        $obj.find('.vc_welcome-header h5').after('<h3>'+admin_var.welcomeMsg+'</h3>');

        $obj.find('.vc_welcome-header h3').after('<div class="portfolio-templates">' +
        '<div class="clearfix">' +
        '<div class="md-template-button custom" data-name="custom">' +
        '<span class="image"></span><span class="name">'+admin_var.yourStyle+'</span>' +
        '</div>' +
        ' </div>' +
        '</div>');

        $obj.find('#vc_no-content-add-element,#vc_templates-more-layouts').css({opacity:0,zIndex:-999});

        $obj.find('.portfolio-templates .md-template-button').click(function(){
           var clicked = $(this).attr('data-name');
            clicked = clicked[0].toUpperCase()+clicked.slice(1);

            $obj.find('.portfolio-templates .md-template-button').removeClass('clicked');
            $(this).addClass('clicked');
            if ($(this).hasClass('custom')){
                $obj.find('#vc_no-content-add-element').click();
            }else {
                $body.find('#vc_templates-more-layouts').click();
                $popup.find('.vc_templates-panel').css('opacity', 0);
                $popup.find('#vc_ui-panel-templates .vc_ui-list-bar-item-trigger:contains(' + clicked + ')').click();
                $popup.find('.vc_templates-panel .vc_ui-close-button').click();
            }
        });


        $('#poststuff #postbox-container-1 #postimagediv').after('<div class="thumbnail-point"></div>');
        $('.wpb_switch-to-composer').click(function(){
            var $this = $(this);
            if( $this.parent('.composer-switch').hasClass('vc_backend-status') ){
                $this.text(admin_var.classicMode);
            }else{
                $this.text(admin_var.backendEditor);
            }
        }).click();



        $('.composer-switch , .vc_backend-status').append('<a href="'+admin_var.customizerUrl+'" class="wpb_switch-to-customizer">'+admin_var.massiveBuilder+'</a><a class="templates">'+admin_var.changeLayout+'</a>');
        $obj.find('.vc_default-templates .wpb_row,.vc_default-templates .vc_row-fluid').append(''+
        '<a class="vc_templates-image vc_templates-blank" href="#"><span>'+admin_var.yourStyle+'</span></a>');
        $('.post-type-portfolio .vc_templates-image').click(function(){
            $('.post-type-portfolio .vc_templates-image').removeClass('clicked');
            $(this).addClass('clicked');
            if($(this).hasClass('vc_templates-blank')) {
                $('#vc_no-content-add-element').click();
            }


        });
        $('.templates').click(function(){
            if ($('.vc_not-empty').length) {
                var r = confirm(admin_var.changeLayoutMsg);
                if (r == true) {
                    $('[data-model-id]').each(function () {
                        try {
                            vc.shortcodes.get($(this).attr('data-model-id')).destroy();
                            $('.post-type-portfolio .vc_templates-image').removeClass('clicked');
                        } catch (e) {
                        }
                    })
                } else {
                }
            }else{
                $('#vc_templates-more-layouts').click();
                $('[data-model-id]').each(function () {
                    try {
                        vc.shortcodes.get($(this).attr('data-model-id')).destroy();
                        $('.post-type-portfolio .vc_templates-image').removeClass('clicked');
                    } catch (e) {
                    }
                })
            }
        });
    }

    function pixflow_classic_mode(){
        'use strict';
        var currentPage=location.pathname.substring(location.pathname.lastIndexOf("/") + 1),
            $composerBtn = jQuery('.wpb_switch-to-composer'),
            $postPage = $('.post-type-post'),
            $switch = $('.composer-switch');

            if($composerBtn.length<1){
                return;
            }
        if (! $switch.hasClass('vc_backend-status') && !$postPage.length ){
            $composerBtn.click();
            $composerBtn.html(admin_var.classicMode);
        } else if( $switch.hasClass('vc_backend-status') && $postPage.length ) {
            $composerBtn.click();
            $composerBtn.html(admin_var.backendEditor);
        }
    }

    function pixflow_addMassivePanel(){
        'use strict';
        var $welcomePanel = $('#welcome-panel');

        if( ! $welcomePanel.length )
            return;

        var rand_num = Math.floor((Math.random() * 3) + 1);
        var rand_class = 'massive-panel-bg' + rand_num ;
        $welcomePanel.before('' +
             '<div class="massive-panel ' + rand_class + '">' +
                '<img src="' + admin_var.loading_gif +'" style="display:none !important" />' +
                '<div class="massive-pannel-menu" >' +
                '<nav class="pannel-menu" ><ul class="flex-rtl">' +
                '<li><a href="https://themeforest.net/downloads" target="_blank">Update Theme </a> </li>' +
                '<li><a href="http://themeforest.net/cart/add_items?ref=pixflow&item_ids=13739153" target="_blank" >Buy  new lisence</a> </li>' +
                '<li><a href="http://massivedynamic.co/pages/changelog.php" target="_blank" >Changelog</a> </li>' +
                '<li class="md-last-list" style=" display: flex;"><a href="#" >get help</a>' +
                '<span class="md-icon-img"></span>' +
                '<div class="dropdown" > '+
                '<nav class="dropdow-pannel-menu" ><ul>' +
                '<li><a target="_blank" href="https://help.massivedynamic.co/hc/en-us/requests/new" >Open Ticket </a> </li>' +
                '<li><a target="_blank" href="http://massivedynamic.co/documentation/" >Documentation</a> </li>' +
                '<li><a target="_blank" href="https://www.youtube.com/watch?v=DiMk1rA4MpY&list=PLyCm9JEF2J5eyRHCcOH_2VzU79gOrp7GU" >Video TutS</a> </li>' +
                '<li><a target="_blank" href="https://themeforest.net/downloads" >Rate us</a> </li>' +
                '</ul></nav>' +
                '</div>' +
                '</li>' +
                '</ul></nav>' +
                '</div>' +
                '<div class="massive-pannel-content">' +
                '<div class="left-side">' +
                '<div class="massive-logo" ><img src="' + admin_var.img_banner_logo + '" /></div>' +
                '<p class="description"> VISIONARY LIVE <br> WEBSITE BUILDER ' + '<br><img class="pixflow-logo" src="' + admin_var.img_pixflow_logo + '" />' + '</p>' +
                '</div>' +
                '<div class="right-side" >' +
                '<a href="' + admin_var.start_link + '" class="customizer-start-link customizer-link" > Start Now </a>' +
                '<a href="' +  admin_var.customizer_link  + '" class="customizer-setting-link customizer-link" > setting & layout </a>' +
                '</div>' +
                '</div>' +
                '<div class="massive-licenes" title="Theme Unlocked" >' + '<img src="'+ admin_var.license_icon +'" />' +'</div>' +
             '</div>');
    }
    function pixflow_metabox(){
        'use strict';
        if($('.post-format').length<1){
            return;
        }

        if($('#post-format-gallery').attr('checked')=='checked'){
            $('#featuredgallerydiv').css('display','block');
            $('#section-video').css('display','none');
            $('#section-audio').css('display','none');
            $('#blog_meta_box_video_url').css('display','none');
            $('#blog_meta_box_audio_url').css('display','none');
        }
        else if($('#post-format-audio').attr('checked')=='checked'){
            $('#featuredgallerydiv').css('display','none');
            $('#blog_meta_box_video_url').css('display','none');
            $('#blog_meta_box_audio_url').css('display','block');
            $('#section-video').css('display','none');
            $('#section-audio').css('display','block');
        }
        else if($('#post-format-video').attr('checked')=='checked'){
            $('#featuredgallerydiv').css('display','none');
            $('#blog_meta_box_video_url').css('display','block');
            $('#blog_meta_box_audio_url').css('display','none');
            $('#section-video').css('display','block');
            $('#section-audio').css('display','none');
        }else{
            $('#featuredgallerydiv').css('display','none');
            $('#section-video').css('display','none');
            $('#section-audio').css('display','none');
            $('#blog_meta_box_video_url').css('display','none');
            $('#blog_meta_box_audio_url').css('display','none');
        }

        $('.post-format').click(function(){

            if($(this).attr('value')=='gallery'){
                $('#featuredgallerydiv').css('display','block');
                $('#section-video').css('display','none');
                $('#section-audio').css('display','none');
                $('#blog_meta_box_video_url').css('display','none');
                $('#blog_meta_box_audio_url').css('display','none');
            }
            if($(this).attr('value')=='audio'){
                $('#featuredgallerydiv').css('display','none');
                $('#section-video').css('display','none');
                $('#section-audio').css('display','block');
                $('#blog_meta_box_video_url').css('display','none');
                $('#blog_meta_box_audio_url').css('display','block');
            }
            if($(this).attr('value')=='video'){
                $('#featuredgallerydiv').css('display','none');
                $('#section-video').css('display','block');
                $('#section-audio').css('display','none');
                $('#blog_meta_box_video_url').css('display','block');
                $('#blog_meta_box_audio_url').css('display','none');
            }
            if($(this).attr('value')=='0' || $(this).attr('value')=='quote') {
                $('#featuredgallerydiv').css('display','none');
                $('#section-video').css('display','none');
                $('#section-audio').css('display','none');
                $('#blog_meta_box_video_url').css('display','none');
                $('#blog_meta_box_audio_url').css('display','none');
            }
        });

    }

    function pixflow_featuredGallery(){
        'use strict';
        function pixflow_fixBackButton() {
            'use strict';
            setTimeout(function(){

                jQuery('.media-menu a:first-child').text('? '+admin_var.editSelection).addClass('button').addClass('button-large').addClass('button-primary');

            },0);

        }

        function pixflow_ajaxUpdateTempMetaData() {
            'use strict';
            jQuery.ajax({
                type : "post",
                dataType : "json",
                url : myAjax.ajaxurl,
                data : {
                    action: "pixflow_fg_update_temp",
                    fg_post_id: jQuery("#fg_perm_metadata").data("post_id"),
                    fg_temp_noncedata: jQuery("#fg_temp_noncedata").val(),
                    fg_temp_metadata: jQuery("#fg_perm_metadata").val()
                },
                success: function(response) {
                    if (response == "error") {
                        alert(admin_var.updateErr);
                    }
                }
            });

        }
        // Uploading files
        if (jQuery('#fg_removeall').hasClass('premp6')) {
            var button = '<button class="media-modal-icon"></button>';
        } else {
            var button = '<button>?</button>';
        }
        jQuery('#fg_select').on('click', function (event) {
            event.preventDefault();
            // If the media frame already exists, reopen it.
            if (file_frame) {
                file_frame.open();
                pixflow_fixBackButton();
                return;
            }
            // Create the media frame.
            var file_frame = wp.media.frame = wp.media({
                frame: "post",
                state: "featured-gallery",
                library: {type: 'image'},
                button: {text: "Edit Image Order"},
                multiple: true
            });
            // Create Featured Gallery state. This is essentially the Gallery state, but selection behavior is altered.
            file_frame.states.add([
                new wp.media.controller.Library({
                    id: 'featured-gallery',
                    title: 'Select Images for Gallery',
                    priority: 20,
                    toolbar: 'main-gallery',
                    filterable: 'uploaded',
                    library: wp.media.query(file_frame.options.library),
                    multiple: file_frame.options.multiple ? 'reset' : false,
                    editable: true,
                    allowLocalEdits: true,
                    displaySettings: true,
                    displayUserSettings: true
                }),
            ]);
            file_frame.on('open', function () {
                var selection = file_frame.state().get('selection');
                var library = file_frame.state('gallery-edit').get('library');
                var ids = jQuery('#fg_perm_metadata').val();
                if (ids) {
                    var idsArray = ids.split(',');
                    idsArray.forEach(function (id) {
                        var attachment = wp.media.attachment(id);
                        attachment.fetch();
                        selection.add(attachment ? [attachment] : []);
                    });
                    file_frame.setState('gallery-edit');
                    idsArray.forEach(function (id) {
                        var attachment = wp.media.attachment(id);
                        attachment.fetch();
                        library.add(attachment ? [attachment] : []);
                    });
                }
            });

            file_frame.on('ready', function () {
                jQuery('.media-modal').addClass('no-sidebar');
                pixflow_fixBackButton();
            });

            // When an image is selected, run a callback.
            file_frame.on('update', function () {
                var imageIDArray = [];
                var imageHTML = '';
                var metadataString = '',
                images = file_frame.state().get('library');
                images.each(function (attachment) {
                    imageIDArray.push(attachment.attributes.id);
                    imageHTML += '<li>' + button + '<img id="' + attachment.attributes.id + '" src="' + attachment.attributes.url + '"></li>';
                });
                metadataString = imageIDArray.join(",");
                if (metadataString) {
                    jQuery("#fg_perm_metadata").val(metadataString);
                    jQuery("#featuredgallerydiv ul").html(imageHTML);
                    jQuery('#fg_select').text(admin_var.editSelection);
                    jQuery('#fg_removeall').addClass('visible');
                    setTimeout(function () {
                        pixflow_ajaxUpdateTempMetaData();
                    }, 0);
                }
            });

            // Finally, open the modal
            file_frame.open();
        });

        jQuery('#featuredgallerydiv ul').on('click', 'button', function (event) {
            event.preventDefault();

            if (confirm('Are you sure you want to remove this image?')) {

                var removedImage = jQuery(this).parent().children('img').attr('id');

                var oldGallery = jQuery("#fg_perm_metadata").val();

                var newGallery = oldGallery.replace(',' + removedImage, '').replace(removedImage + ',', '').replace(removedImage, '');

                jQuery(this).parent('li').remove();

                jQuery("#fg_perm_metadata").val(newGallery);

                if (newGallery == "") {

                    jQuery('#fg_select').text(admin_var.selectImage);

                    jQuery('#fg_removeall').removeClass('visible');

                }

                pixflow_ajaxUpdateTempMetaData();

            }

        });

        jQuery('#fg_removeall').on('click', function (event) {

            event.preventDefault();

            if (confirm('Are you sure you want to remove all images?')) {

                jQuery("#featuredgallerydiv ul").html("");

                jQuery("#fg_perm_metadata").val("");

                jQuery('#fg_removeall').removeClass('visible');

                jQuery('#fg_select').text(admin_var.selectImage);

                pixflow_ajaxUpdateTempMetaData();

            }

        });
    }


    $('.remove-megaMenu-attachment').click(function(){
        var $this=$(this),
            menuId=$this.attr('id').split("-");

        $('#image-'+menuId[1]).remove();
        $('#attachment-'+menuId[1]).remove();

        $('#input-attachment-'+menuId[1]).val('1');
    });

    function pixflow_vcBackendIcons(){
        var regex = /(http.*)x=([0-9-]+)[|]y=([0-9-]+)/i;
        $('#vc_ui-panel-add-element li.wpb-layout-element-button').each(function(){
            var icon = $(this).find('a i.vc_element-icon').css('background-image');
            if(icon.search(regex)!=-1){
                var match = regex.exec(icon);
                var url = match[1], x = match[2], y= match[3];
                icon = 'background: transparent url(' + url + ') '+x+'px '+y+'px no-repeat;';
            }else {
                icon = 'background: transparent ' + icon + '15px center no-repeat;';
            }
            $(this).find('a i.vc_element-icon').attr('style',icon);
        })
    }


    $(document).ready(function () {
        pixflow_IconSelect();
        pixflow_showIcons();
        pixflow_showMegaBg();
        pixflow_menuUpdate();
        pixflow_customShortcodesJs();
        pixflow_media_upload();
        pixflow_addMassivePanel();
        pixflow_metabox();
        pixflow_featuredGallery();
        pixflow_vcBackendIcons();
    });

    $(window).load(function(){

        pixflow_singlePageLayout();
        pixflow_classic_mode();

    });
})(jQuery);
