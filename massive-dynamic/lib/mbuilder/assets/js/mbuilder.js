/**
 * *********************
 * * mBuilder Composer *
 * *********************
 * mBuilder is a visual editor for shortcodes and makes working with shortcodes more easier and fun.
 * It is added as a part of Massive Dynamic since V3.0.0 and designed to work with customizer. Enjoy Editing ;)
 *
 * @summary mBuilder provides some functionality for editing shortcodes in customizer.
 *
 * @author PixFlow
 *
 * @version 1.0.0
 * @requires jQuery, jQuery.ui
 *
 * @class
 * @classdesc initialize all of the mBuilder features.
 */
var mBuilder = function () {
    // All shortcode attributes and contents stored in models, and should update after editing
    if (typeof mBuilderModels == 'undefined') {
        mBuilderModels = {};
        mBuilderModels.models = {}
    }
    this.models = mBuilderModels;
    this.lock = false;
    // All available shortcodes
    this.shortcodes = mBuilderShortcodes;

    this.settingPanel = null;

    // Defines droppable areas for drop shortcodes
    this.droppables = '' +
        '.vc_column_container,' +
        '.wpb_accordion_content,' +
        '.wpb_toggle_content,' +
        '.wpb_tour_tabs_wrapper,' +
        '.wpb_tab';

    // Container shortcodes
    this.containers = {
        'md_accordion_tab': '> .wpb_accordion_section > .wpb_accordion_content',
        'md_toggle_tab': '> .wpb_accordion_section > .wpb_toggle_content',
        'md_toggle_tab2': '> .wpb_accordion_section > .wpb_toggle_content',
        'md_tab': '> .wpb_tab',
        'md_tabs': "> .wpb_tabs > .wpb_wrapper",
        'md_modernTab': "> .wpb_tab",
        'md_modernTabs': "> .wpb_tabs > .wpb_wrapper",
        'vc_column': '> .wpb_column > .vc_column-inner> .wpb_wrapper',
        'vc_column_inner': '> .wpb_column > .vc_column-inner> .wpb_wrapper',
        'md_hor_tab': "> .wpb_tab",
        'md_hor_tabs': "> .wpb_tabs > .wpb_wrapper",
        'md_hor_tab2': "> .wpb_tab",
        'md_hor_tabs2': "> .wpb_tabs > .wpb_wrapper",
    };

    //Tab Shortcodes
    this.tabs = {
        'md_tabs': ['md_tab', '<li data-model="md_tabs"><a href="#"><i class="left-icon icon-cog"></i><span>Tab</span></a></li>'],
        'md_modernTabs': ['md_modernTab', '<li data-model="md_modernTabs"><a href="#"><i class="left-icon icon-cog"></i><div class="modernTabTitle">Tab</div></a></li>'],
        'md_hor_tabs': ['md_hor_tab', '<li data-model="md_hor_tabs"><a href="#"><i class="right-icon icon-cog"></i><div class="horTabTitle">Tab</div><i class="right-icon icon-angle-right"></i></a></li>'],
        'md_hor_tabs2': ['md_hor_tab2', '<li data-model="md_hor_tabs2"><a href="#"><i class="right-icon icon-cog"></i><div class="horTabTitle">Tab</div></a></li>'],
    };
    //Full shortcodes
    this.fullShortcodes = [
        'md_team_member_classic',
        'vc_empty_space',
        'md_button',
        'md_call_to_action',
        'md_imagebox_full',
        'md_portfolio_multisize',
        'md_showcase',
        'md_blog',
        'md_blog_carousel',
        'md_client_normal',
        'md_instagram',
        'md_blog_masonry',
        'md_process_steps',
        'md_teammember2',
        'pixflow_subscribe',
        'md_pricetabel',
        'md_google_map',
        'md_masterslider',
        'md_rev_slider',
        'md_blog_classic',
        'vc_facebook',
        'vc_tweetmeme',
        'vc_pinterest',
        'vc_gmaps',
        'vc_round_chart',
        'vc_line_chart',
        'md_product_categories',
        'md_products',
        'md_textbox',
        'md_full_button',
        'md_testimonial_classic',
        'md_client_carousel',
        'md_fancy_text',
        'md_iconbox_side',
        'md_iconbox_side2',
        'md_slider',
        'md_testimonial_carousel',
        'md_modern_subscribe',
        'md_double_slider',
        'md_skill_style2',
        'md_slider_carousel',
        'md_slider',
        'md_text_box'
    ];

    //used in shortcodeTag method
    this.compiledTags = [];


    var isLocal = $.ui.tabs.prototype._isLocal;
    $.ui.tabs.prototype._isLocal = function (anchor) {
        return true;
    };
    
    this.save_callback_function = null;
    this.make_links_target_self();
    this.tinymceString = this.makeTinymceString();
    this.fontName;
    this.renderControls();
    this.addEvents();
    this.setSortable();
    this.mediaPanel();
    this.multiMediaPanel();
    this.googleFontPanel();
    this.shortcode_panel_functionality();
    this.set_parents();
    this.preview_mode();
    this.on_before_unload();
};


/**
 * @summary makes shortcodes sortable.
 *
 * @since 1.0.0
 */
mBuilder.prototype.setSortable = function () {
    "use strict";
    var t = this,
        lastObj = null;
    var fly = null;
    $('.mBuilder-overlay').remove();
    var d = $('<div class="mBuilder-overlay-holder "></div>').appendTo('body'),
        overlay = $('<div class="mBuilder-overlay"></div>').appendTo('body'),
        direction = 'down',
        overEmpty = false,
        overs = $,
        helper;
    overlay.click(function () {
        d.css('width', '');
        overlay.css('display', 'none');
    });

    $('.mBuilder-element:not(.vc_row,.mBuilder-vc_column)').draggable({
        zIndex: 999999,
        helper: 'clone',
        appendTo: '.layout',
        delay: 300,
        containment: [$('.layout').offset().left, $('.layout').offset().top, $('.layout').offset().left+$('.layout').width(), $('.layout').offset().top+$('.layout').height()],
        scroll: false,
        items: ":not(.disable-sort)",
        start: function (event, ui) {
            $('.layout').css('overflow','hidden');
            ui.helper.css({
                width: $(this).width(),
                height: $(this).height()
            });

            clearInterval(fly);
            var that = this;

            if ($(this).hasClass("mBuilder-md_portfolio_multisize")) {
                ui.helper.addClass("portfolio-draged");
            }


            setTimeout(function () {
                overs = $('.mBuilder-element:not(.vc_row,.mBuilder-vc_column),.vc_empty-element')
                    .not(ui.helper)
                    .not(ui.helper.find('.mBuilder-element:not(.vc_row,.mBuilder-vc_column),.vc_empty-element'))
                    .not($(that).find('.mBuilder-element:not(.vc_row,.mBuilder-vc_column),.vc_empty-element'));
            }, 100);
            $(this).addClass('ui-sortable-helper');
            overlay.css('display', 'block');
        },
        drag: function (event, ui) {
            t.removeColumnSeparator();
            t.createColumnSeparator('all');
            clearInterval(fly);
            if (event.clientY < 100) {
                fly = setInterval(function () {
                    if($(window).scrollTop()==0){
                        clearInterval(fly);
                    }
                    $(window).scrollTop($(window).scrollTop() - 50)
                }, 50);
            } else if (event.clientY > $(window).height() - 50) {
                fly = setInterval(function () {
                    if($(window).scrollTop()>=$(document).height()-$(window).height()){
                        clearInterval(fly);
                    }
                    $(window).scrollTop($(window).scrollTop() + 50)
                }, 50);
            }
            var el = null;
            overs.each(function () {
                if (
                    $(this).css('display') != 'none' &&
                    event.pageY > $(this).offset().top && event.pageY < $(this).offset().top + $(this).outerHeight() &&
                    event.pageX > $(this).offset().left && event.pageX < $(this).offset().left + $(this).outerWidth()
                ) {
                    el = this;
                }
            });

            if (el) {

                overEmpty = false;
                var obj = $(el);
                if (el != this && obj.length && !obj.hasClass('vc_empty-element')) {

                    if (t.containers[obj.attr('data-mbuilder-el')] && !obj.find('.mBuilder-element').length) {
                        overEmpty = true;
                    } else {
                        d.css({border: '', borderTop: '4px solid #8fcbff'});
                    }
                } else {
                    overEmpty = true;

                }
                var objTop = obj.offset().top,
                    objLeft = obj.offset().left,
                    objHeight = obj.outerHeight(),
                    objWidth = obj.outerWidth(),
                    objHalf = objTop + objHeight / 2;
                if (lastObj) {
                    lastObj.css({'transform': ''})
                }
                if (!overEmpty) {
                    if (event.pageY < objHalf) {
                        obj.not('.vc_row').css({'transform': 'translateY(5px)'});
                        d.css({'top': objTop, 'left': objLeft, width: objWidth, height: 5, background: ''});
                        direction = 'up';
                    } else {
                        obj.not('.vc_row').css({'transform': 'translateY(-5px)'});
                        d.css({'top': objTop + objHeight, 'left': objLeft, width: objWidth, height: 5, background: ''});
                        direction = 'down';
                    }
                } else {
                    d.css({
                        'top': objTop,
                        'left': objLeft,
                        height: objHeight,
                        width: objWidth,
                        background: 'rgba(136,206,255,0.4)',
                        border: 'solid 2px #8fcbff'
                    });
                }
                lastObj = obj;
            } else {
                if (lastObj) {
                    lastObj.css({'transform': ''})
                }
                lastObj = null;
                d.css({width: '', border: ''});
            }
        },
        stop: function (event, ui) {
            $('.layout').css('overflow','');
            t.removeColumnSeparator();
            try {

                if (ui.helper.hasClass("portfolio-draged")) {
                    ui.helper.removeClass("portfolio-draged");
                }

                clearInterval(fly);
                $(this).removeClass('ui-sortable-helper');
                if (!lastObj || !lastObj.length) {
                    d.css({'width': '', border: ''});
                    setTimeout(function () {
                        overlay.css('display', 'none');
                    }, 300);
                    return;
                }
                if (direction == 'up') {
                    if (lastObj.hasClass('vc_empty-element')) {
                        var p = lastObj.find('.wpb_wrapper');
                    } else if (t.containers[lastObj.attr('data-mbuilder-el')] && overEmpty) {
                        var p = lastObj.find(t.containers[lastObj.attr('data-mbuilder-el')]);
                    } else {
                        var p = lastObj.prev('.insert-between-placeholder');
                        if (!p.length) {
                            var p = lastObj.parent().closest('.mBuilder-element').prev('.insert-between-placeholder');
                        }
                    }
                } else {
                    if (lastObj.hasClass('vc_empty-element')) {
                        var p = lastObj.find('.wpb_wrapper');
                    } else if (t.containers[lastObj.attr('data-mbuilder-el')] && overEmpty) {
                        var p = lastObj.find(t.containers[lastObj.attr('data-mbuilder-el')]);
                    } else {
                        var p = lastObj.next('.insert-between-placeholder');
                        if (!p.length) {
                            var p = lastObj.parent().closest('.mBuilder-element').next('.insert-between-placeholder');
                        }
                    }
                }
                var placeholder = p.get(0);
                if (placeholder != null) {
                    if ($(this).closest('.vc_column_container').find('.mBuilder-element').not($(this).find('.mBuilder-element')).length < 2 && lastObj.get(0) != this) {
                        $(this).closest('.vc_column_container').addClass('vc_empty-element');
                    }
                    if (lastObj.hasClass('vc_empty-element')) {
                        $(this).appendTo(placeholder);
                        lastObj.removeClass('vc_empty-element')
                    } else {
                        if (!$(this).find(placeholder).length) {
                            if (t.containers[lastObj.attr('data-mbuilder-el')] && overEmpty) {
                                p.html('');
                                $(this).appendTo(placeholder);
                            } else {
                                $(this).insertAfter(placeholder);
                            }
                        }
                    }
                    setTimeout(function () {
                        t.createPlaceholders();
                    }, 100)
                    $('body').addClass('changed');
                }
                d.css({'width': '', border: ''});
                setTimeout(function () {
                    overlay.css('display', 'none');
                }, 300);
            } catch (e) {
                console.log(e);
                d.css({'width': '', border: ''});
                setTimeout(function () {
                    overlay.css('display', 'none');
                }, 300);
            }
        }
    });

    // Row movement
    $(".content-container").sortable({
        cursor: "move",
        delay: 100,
        cancel: ".disable-sort",
        handle: ".mBuilder_row_move",
        update: function (event, ui) {
            $('body').addClass('changed');
            t.createPlaceholders();
        }
    });
    $(".content-container").disableSelection();
};


/**
 * @summary add shortcode controllers for edit,delete,clone and etc.
 *
 * @since 1.0.0
 */
mBuilder.prototype.renderControls = function () {
    var t = this;
    var countTiny = 0;
    t.getEditorFonts() ;

    $('body').addClass('compose-mode');

    var settingSvg = '<span class="mdb-settingsvg" ></span>',

        duplicateSvg = '<span class="mdb-duplicatesvg" ></span>',

        deleteSvg = '<span class="mdb-deletesvg" ></span>',

        leftAlignSvg = '<span class="mdb-leftalignsvg" ></span>',

        centerAlignSvg = '<span class="mdb-centeralignsvg" ></span>',

        rightAlignSvg = '<span class="mdb-rightalignsvg" ></span>',

        optionSvg = '<span class="mdb-optionsvg" ></span>',

        col1_1Svg = '<span class="mdb-col1-1svg" ></span>',

        col1_2Svg = '<span class="mdb-col1-2svg" ></span>',

        col1_3Svg = '<span class="mdb-col1-3svg" ></span>',

        col1_4Svg = '<span class="mdb-col1-4svg" ></span>',

        col2_4Svg = '<span class="mdb-col2-4svg" ></span>',

        col3_4Svg = '<span class="mdb-col3-4svg" ></span>',

        col3_9Svg = '<span class="mdb-col3-9svg" ></span>',

        layoutSvg = '<span class="mdb-layoutsvg" ></span>',

        moveSvg = '<span class="mdb-movesvg" ></span>',

        rowSettingSvg = '<span class="mdb-rowsettingsvg" ></span>';


    $('.mBuilder-element').not('.vc_row, .vc_row_inner,.mBuilder-vc_row_inner,.mBuilder-vc_column,.mBuilder-vc_column_inner').each(function () {

        var $this = $(this);

        if (!$this.find('.mBuilder_controls').first().length) {
            if ($this.hasClass('mBuilder-md_tabs') || $this.hasClass('mBuilder-md_toggle') ||
                $this.hasClass('mBuilder-md_accordion') || $this.hasClass('mBuilder-md_modernTabs') ||
                $this.hasClass('mBuilder-md_hor_tabs') || $this.hasClass('mBuilder-md_toggle2') ||
                $this.hasClass('mBuilder-md_hor_tabs2')) {

                var html = '<div class="mBuilder_controls tabs-family sc-control " >' +
                    '<div class="mBuilder_move">' + moveSvg + '</div>' +
                    '<div class="sc-setting setting">' + settingSvg + '</div>' +
                    '<div class="sc-delete">' + deleteSvg + '</div>' +
                    '</div>';
                $this.append(html);

            } else if ($this.hasClass('mBuilder-md_tab') || $this.hasClass('mBuilder-md_toggle_tab') ||
                $this.hasClass('mBuilder-md_accordion_tab') || $this.hasClass('mBuilder-md_modernTab') ||
                $this.hasClass('mBuilder-md_hor_tab') || $this.hasClass('mBuilder-md_toggle_tab2') ||
                $this.hasClass('mBuilder-md_hor_tab2')) {
                var html = '<div class="mBuilder_controls tab sc-control " >' +
                    '<div class="sc-setting setting">' + settingSvg + '</div>' +
                    '<div class="sc-delete">' + deleteSvg + '</div>';
                /*if ($this.hasClass('mBuilder-md_accordion_tab') ||
                 $this.hasClass('mBuilder-md_toggle_tab2') || $this.hasClass('mBuilder-md_toggle_tab')) {
                 html += '<div class="sc-duplicate">' + duplicateSvg + '</div>';
                 }*/
                html += '</div>';
                $this.append(html);

            } else {
                var el = $this.attr('data-mbuilder-el'),
                    fullClass = '';
                if (t.fullShortcodes.indexOf(el) != -1) {
                    fullClass = 'md-full-shortcode-gizmo';
                }
                var $elem = $this;
                if ($this.find('.gizmo-container').length) {
                    $elem = $this.find('.gizmo-container').first();
                }

                var html = '<div class="mBuilder_controls sc-control " >' +
                    '<span class="handel top-left"></span>' +
                    '<span class="handel top-right"></span>' +
                    '<span class="handel bottom-left"></span>' +
                    '<span class="handel bottom-right"></span>' +
                    '<div class="settings-holder">' +
                    '<div class="sc-setting setting">' + settingSvg + '</div>' +
                    '<div class="sc-option">' +
                    '<div class="options-holder ' + fullClass + '">' +
                    '<a href="#" class="animation" data-tabName="Animation">' + mBuilderValues.animationText + '</a>' +
                    '<a href="#" class="sc-duplicate"><span>' + mBuilderValues.duplicateText + '</span></a>' +
                    '<a href="#" class="sc-delete"><span>' + mBuilderValues.deleteText + '</span></a>' +
                    '<a href="#" class="sc-alignment">' +
                    '<span class="left">' + leftAlignSvg + '</span>' +
                    '<span class="center">' + centerAlignSvg + '</span>' +
                    '<span class="right">' + rightAlignSvg + '</span>' +
                    '</a>' +
                    '</div>' +
                    '<a href="#" class="setting options-button">' + optionSvg + '</a>' +
                    '</div>' +
                    '</div>';


                html += '</div>'
                $elem.append(html);
            }
        }

        if (t.shortcodes[$this.attr('data-mbuilder-el')] && t.shortcodes[$this.attr('data-mbuilder-el')].as_parent) {
            if (!$this.find(' > .mBuilder_controls [data-control="add_section"]').length) {
                var btn = $('<span class="vc_btn-content"><span class="icon"></span></span>');
                var link = $('<a class="vc_control-btn" title="Add new Section" data-control="add_section" href="#" target="_blank"></a>');
                link.append(btn);
                $this.find(' > .mBuilder_controls').append(link);
                var child = t.shortcodes[$(this).attr('data-mbuilder-el')].as_parent['only'];
                btn.click(function () {
                    t.buildShortcode(this, child);
                })
            }
        }
    });


    $('.mBuilder-element.vc_row,.vc_row.vc_inner').each(function () {
        var $this = $(this);
        if (!$this.find('> .mBuilder_row_controls ').length) {
            $this.find('>.wrap').after(''+
                '<div class="mBuilder_row_controls">'+
                    '<div href="#" class="mBuilder_row_move">' + moveSvg + '</div>'+
                    '<div class="mBuilder_setting_panel">'+
                        '<a href="#" class="title">' + rowSettingSvg + '<span>' + mBuilderValues.rowText + '</span></a>'+
                        '<div class="mBuilder_container">'+
                            '<a href="#" class="mBuilder_row_setting"><span>' + mBuilderValues.settingText + '</span></a>'+
                            '<a href="#" class="mBuilder_row_delete"><span>' + mBuilderValues.deleteText + '</span></a>'+
                            '<a href="#" class="mBuilder_row_duplicate"><span>' + mBuilderValues.duplicateText + '</span></a>'+
                        '</div>'+
                    '</div>'+
                    '<div class="mBuilder_row_layout">'+
                        '<a href="#" class="title">' + layoutSvg + '<span>' + mBuilderValues.layoutText + '</span></a>'+
                        '<div class="mBuilder_container"><div class="holder">'+
                            '<span class="col" data-colSize="12/12">' + col1_1Svg + '</span><span class="separator"></span> '+
                            '<span class="col" data-colSize="6/12+6/12">' + col1_2Svg + '</span><span class="separator"></span> '+
                            '<span class="col" data-colSize="4/12+4/12+4/12">' + col1_3Svg + '</span><span class="separator"></span>'+
                            '<span class="col" data-colSize="3/12+3/12+3/12+3/12">' + col1_4Svg + '</span><span class="separator"></span>'+
                            '<span class="col" data-colSize="2/12+8/12+2/12">' + col2_4Svg + '</span><span class="separator"></span>'+
                            '<span class="col" data-colSize="10/12+2/12">' + col3_4Svg + '</span><span class="separator"></span>'+
                            '<span class="col" data-colSize = "3/12+9/12">' + col3_9Svg + '</span>'+
                            '<hr>'+
                            '<label>' + mBuilderValues.customColText + '</label><input placeholder="12/12" name="cols" value=""><span class="submit">&#8594;</span>'+
                        '</div></div>'+
                    '</div>'+
                '</div>'+
            '');
        }
        var layoutValue='';
        $this.find('>.wrap >.mBuilder-vc_column').each(function(){

            var row_str = $(this).attr("class");
            var array_row_str = row_str.match(/col-sm-([0-9]+)/);

            layoutValue += (array_row_str[1] + '/12+');

        });

        $this.find('> .mBuilder_row_controls input[name="cols"]').val(layoutValue.substr(0,layoutValue.length-1));

        if (!$this.hasClass('vc_inner')) {
            if (!$this.find('> .row_border ').length) {
                $this.append('<div class="row_border top"></div><div class="row_border right"></div><div class="row_border left"></div><div class="row_border bottom"></div>');
            }
        }
    });

    $('.mBuilder-vc_column,.mBuilder-vc_column_inner').each(function () {
        var itemClass = ($(this).hasClass('mBuilder-vc_column')) ? 'element-vc_column' : 'element-vc_column_inner';

        if (!$(this).find('> .vc_column_container > .mbuilder-column-options').length) {
            $(this).find(' > .vc_column_container').append('<div class=\"mbuilder-column-options ' + itemClass + '\" >' +
                '<span class="mdb-menu"></span>' +
                '<div class="extra-option">' +
                '<span class="col-title">Column Setting</span>'+
                '<a href="#" class="design" data-tabName = "Design">Design</a>' +
                '<a href="#" class="spacing" data-tabName = "Spacing">Spacing</a>' +
                '<a href="#" class="responsive" data-tabName = "Responsive">Responsive</a>' +
                '</div>'+
                '</a>'+
                '</div>'
            );
        }
    });

    for (var i in this.fullShortcodes) {
        $('.mBuilder-element[data-mbuilder-el="' + this.fullShortcodes[i] + '"]').find('.vc_control-btn-align').remove();
        $('.mBuilder-element[data-mbuilder-el="' + this.fullShortcodes[i] + '"]').find('.vc_control-btn-edit').css('left', -99);
        $('.mBuilder-element[data-mbuilder-el="' + this.fullShortcodes[i] + '"]').find('.vc_control-btn-clone').css('left', 3);
        $('.mBuilder-element[data-mbuilder-el="' + this.fullShortcodes[i] + '"]').find('.vc_control-btn-delete').css('left', 105);
    }


    this.createPlaceholders();
};


/**
 * @summary add event to shortcode controllers for edit,delete,clone and etc.
 *
 * @since 1.0.0
 */

mBuilder.prototype.addEvents = function () {
    var t = this,
        $bodyGizmoOff = $('body:not(.gizmo-off)'),
        $body = $('body');

    $(document).ready(function(){
        var window_height = $(window).height() - 50 ;
        $('#layoutcontainer').css('height' , window_height  + 'px')
    });

    $('body').on('click','a[href="#"]',function(e){
        e.preventDefault();
    });

    $('.builder-save .save').click(function (e) {
        e.preventDefault();
        t.saveContent();
    });

    // Inline Editor For Title
    var $FontName = '' ;
    $bodyGizmoOff.on('click', '.mce-menu-item.mce-menu-item-normal.mce-stack-layout-item.mce-pixflow-editor-font' , function (e) {
        var $FontVar = $(this).find('span').text().trim()  ,
            selectText = t.get_selection_html().trim() ,
            parent = tinymce.activeEditor.selection.getNode() ,
            style = $(parent).attr('style');
        if(typeof style == 'undefined'){
            style = '' ;
        }
        var div = document.createElement('span');
        div.innerHTML = selectText ;
       if(div.childElementCount > 0 ){
            $(div).find('*').each(function(){
                $(this).css('font-family' , $FontName );
                $(this).css('font-weight' , $FontVar);
            });
        }
        if(parent.childElementCount > 0 && $(parent).text().trim() ==  selectText.trim()){
            $(parent).attr('style' , style + 'font-weight: '+ $FontVar +';font-family: '+ $FontName );
        }else{
            tinyMCE.execCommand('mceInsertContent',false,'<span style="' + style + 'font-weight: '+ $FontVar +';font-family: '+ $FontName +'">'+ div.outerHTML.trim() +'</span>');
        }
        if($FontVar.search('px') !== -1 || $FontVar == $FontName ){
          return ;
        }
        else{
          WebFont.load({
              google: {
                  families: [ $FontName + ':' + $FontVar  ]
              }
          });
            if ( $('.pixFlow-selected').find('p').last().html() == '<br data-mce-bogus="1">' ){
                $('.pixFlow-selected').find('p').last().remove();
            }
        }
        $('.mce-tinymce').filter(function(){
            if($(this).css('display').toLowerCase().indexOf('block') == 0 ) {
                $(this).find('.mce-btn-group:nth-child(3)').find('span').text($FontName.substr(0, 8));
            }
        });
    });

    $bodyGizmoOff.on('hover', '.mce-selected' , function(e){
      $FontName = $(this).find('span').text().trim() ;
        return ;
    });
    $bodyGizmoOff.on('click' , '.pixFlow-selected' , function(e){
       var $el = document.elementFromPoint(e.clientX , e.clientY);
        $('.mce-tinymce').filter(function(){
            if($(this).css('display').toLowerCase().indexOf('block') == 0 ) {
                $(this).find('.mce-btn-group:nth-child(3)').find('span').text($($el).css('font-family').substr(0, 8));
            }
        });
    });


    $('.pixflow-shortcodes-panel').click(function(e){
        e.stopPropagation();
    });

    $(document).mousedown(function(e){
        try{
            $(".content-container").disableSelection();
            $('body:not(.gizmo-off) .ui-draggable').draggable("enable");
        }catch(e){
            console.log(e);
        }
        if( $('.text-selected').length && $('.pixFlow-selected').length){
            if( $('body').hasClass('changed') == false ){
                $('body').addClass('changed');
            }
            $('.pixFlow-selected').blur();
            t.remove_tinymce();
        }
    });

    $bodyGizmoOff.on('mousedown', '.inline-editor , .inline-editor-title', function (e) {
        e.stopPropagation();
    });
    $bodyGizmoOff.on('mousedown', '.text-selected , .mce-tinymce , .pixFlow-selected , .mce-panel , .mce-widget', function (e) {
        e.stopPropagation();
    });

    $bodyGizmoOff.on('click', '.inline-editor , .inline-editor-title', function (e) {
        e.stopPropagation();
        $selected_el = $(this);
        if ($selected_el.find('.defulttext').length) {
            $selected_el.find('.defulttext').remove();
        }
        if($selected_el.hasClass('text-selected')){
            e.stopPropagation();
            return ;
        }
        if ($('.text-selected').length) {
            $('.text-selected').removeClass('text-selected');
        }
        $selected_el.addClass('text-selected');
        $(".content-container").enableSelection();
        $('body:not(.gizmo-off) .ui-draggable').draggable("option", "disabled", true);
        if($selected_el.hasClass('inline-editor')){
            t.calltinymcecontent('.text-selected');
        }else if($selected_el.hasClass('inline-editor-title')){
            t.calltinymcetitle('.text-selected');
        }
        setTimeout(function(){
             if( !$selected_el.hasClass('pixFlow-selected') ){
              t.remove_tinymce();
             }
        } , 250);
    });

    $bodyGizmoOff.on('mouseleave'  , '.pixflow-shortcodes-panel' , function(){
        t.calc_time_out();
    });
    $bodyGizmoOff.on('mouseenter'  , '.pixflow-shortcodes-panel' , function(){
        t.remove_time_out();
    });
    // Add hover mode for coulmn
    $bodyGizmoOff.on('mouseenter', '.vc_row', function () {
        t.createColumnSeparator($(this).attr('id'));

    });
    $bodyGizmoOff.on('mouseleave', '.vc_row', function () {
        t.removeColumnSeparator();
    });
    $(window).on('scroll', function () {
        if($('.pixFlow-selected').length){
            $('.inline-editor-title , .inline-editor').trigger('blur');
            t.remove_tinymce();
        }
    });
    $body.on('click', '.inline-editor-title', function (e) {
        e.stopPropagation();
        closeAll();
        $(this).addClass('do-save');
    });

    $body.on('click', '.add-title', function () {
        var $this = $(this);
        $this.closest('.no-title').removeClass('no-title');
        $this.closest('.md-text').find('.without-title').removeClass('without-title');
    });

    $body.on('click', '.add-content', function () {
        var $this = $(this);
        $this.closest('.no-text').removeClass('no-text');
        $this.closest('.md-text').find('.without-content').removeClass('without-content');
    });

    $(document).click(function (e) {
        closeAll();
    });

    function closeAll(notMe) {
        $('.mBuilder-element').removeClass('onTop');
        var $activeElems = $('.active-gizmo').not(notMe);
        $activeElems.each(function () {
            var $this = $(this),
                $innerRow = $this.closest('.mBuilder-vc_row_inner'),
                $container = $this.find('>.mBuilder_container, > .options-holder, > .extra-option');

            $this.removeClass('active-gizmo');

            TweenMax.to($this.find('.options-holder'), .2,
                {
                    scale: .9, opacity: 0, delay: .4, onComplete: function () {
                    TweenMax.set($container, {height: 0, zIndex: -333});
                }
                });
            $this.closest('div[class*=mBuilder-vc_column]').removeClass('upper_zIndex');

            if ($this.hasClass('mBuilder_setting_panel') || $this.hasClass('mBuilder_row_layout')) {
                $this.closest('div[class*=vc_row]').removeClass('upper_zIndex');
            }

            $this.find('>.mBuilder_container, .options-holder').removeClass('open');

            if ($this.hasClass('mBuilder_row_layout')) {
                $this.find('input').focus();
            }

            if ($innerRow.length) {
                $innerRow.removeClass('upper_inner_row_zIndex');
                $innerRow.parents('.vc_row').removeClass('upper_inner_row_zIndex');
                $innerRow.siblings('.mBuilder-element').removeClass('lower_inner_row_zIndex')
            }

        });
    }

    // Row Layout
    $body.on('click', '.mBuilder-element .mBuilder_row_layout .col,.mBuilder-element .mBuilder_row_layout .submit', function (e) {
        e.stopPropagation();
        var row = $(this).closest('.vc_row'),
            value = $(this).attr('data-colSize');
        if ($(this).hasClass('submit')) {
            value = $(this).prev().val();
        }
        $(this).closest('.mBuilder_row_layout').find('input[name="cols"]').val(value);
        t.changeRowLayout(value, row);

    });


    // Edit Element
    $body.on('click', '.mBuilder-element .extra-option > a,.mBuilder-element .mBuilder_row_setting,.mBuilder-element .sc-setting ,.mBuilder-element .animation', function (e) {
        e.stopPropagation();
        var params = t.getModelParams($(this).closest('.mBuilder-element').attr('data-mBuilder-id')),
            el_id = $(this).closest('.mBuilder-element').attr('data-mBuilder-id'),
            that = this;
        if (params == null) {
            params = [];
            params['attr'] = '';
            params['content'] = '';
            params['type'] = $(this).closest('.mBuilder-element').attr('data-mbuilder-el');
        }
        t.mBuilder_shortcodeSetting(t.shortcodes[params['type']].name + ' Settings', '', '<div class="mbuilder-spinner"></div>', 'Update', function () {
        }, 'Close', function () {
        });
        $.ajax({
            type: 'post',
            url: mBuilderValues.ajax_url,
            data: {
                action: 'mBuilder_settingPanel',
                nonce: mBuilderValues.ajax_nonce,
                attr: params['attr'],
                content: params['content'],
                type: params['type'],
                mbuilder_editor: true
            },
            success: function (response) {
                t.htmlSource = response ;
                t.mBuilder_shortcodeSetting(t.shortcodes[params['type']].name + ' Settings', 'dont-show', '<div class="mbuilder-spinner"></div><div class="waitforload" style="display: none" >' + response + '</div>' , 'Update', function () {
                        if (params['type'] == 'vc_column' || params['type'] == 'vc_column_inner') {
                            var css = '{';
                            $('#mBuilder-form #mBuilderSpacing .column-design-css input,#mBuilder-form #mBuilderDesign .column-design-css input').each(function () {
                                if ($(this).closest('.column-design-css').hasClass('column-design-prefix-px')) {
                                    var prefix = 'px';
                                } else {
                                    var prefix = '';
                                }
                                if ($(this).parent().hasClass('mBuilder-upload-img')) {
                                    if ($(this).val() != '' && $(this).val() != 'undefined') {
                                        var val = $(this).parent().css('background-image');
                                    }else{
                                        return;
                                    }
                                } else {
                                    var val = $(this).val()
                                }
                                css += $(this).attr('name').replace(/_/g, '-') + ':' + val + prefix + ';';
                            });
                            $('#mBuilder-form #mBuilderDesignOptions .column-design-css select').each(function () {
                                css += $(this).attr('name').replace(/_/g, '-') + ':' + $(this).val() + ';';
                            });
                            css += '}';
                            css = css.replace(/["]/g, '``');
                            var cssInput = $('<input type="hidden" name="css">');
                            cssInput.val(css).appendTo($('#mBuilder-form #mBuilderSpacing'));
                        }

                        if (params['type'] == 'md_text') {
                            if (!($('.mBuilder-element[data-mBuilder-id=' + el_id + ']').find(".md-text-title").hasClass('title-slider'))) {
                                var tinyTitle = $('.mBuilder-element[data-mBuilder-id=' + el_id + ']').find(".md-text-title").html();
                                $("textarea[name='md_text_title1']").html(tinyTitle);
                                var tinyText = $('.mBuilder-element[data-mBuilder-id=' + el_id + ']').find(".md-text-content").html();
                                $(".dont-show textarea").html(tinyText);
                            }
                        }

                        $.fn.serializeObject = function () {
                            var o = {};
                            var a = this.serializeArray();
                            $.each(a, function () {
                                if ($('input[name="' + this.name + '"], textarea[name="' + this.name + '"]').hasClass('mbuilder-skip') || this.value == '' && !$('input[name="' + this.name + '"]').hasClass('simple-textbox') && $('[name="' + this.name + '"]').prop('tagName') != 'TEXTAREA') {
                                    return true;
                                }
                                if (this.value == 'Array') {
                                    this.value = '';
                                }
                                if (o[this.name] !== undefined) {
                                    if (!o[this.name].push) {
                                        o[this.name] = [o[this.name]];
                                    }
                                    o[this.name].push(this.value || '');
                                } else {
                                    o[this.name] = this.value || '';
                                }
                                if($('input[name="'+this.name+'"]').hasClass('md-base64')){
                                    o[this.name] = 'pixflow_base64'+t.b64EncodeUnicode(o[this.name]);
                                }
                                if($('textarea[name="'+this.name+'"]').hasClass('textarea_raw_html')){
                                    o[this.name] = t.b64EncodeUnicode(o[this.name]);
                                }
                            });
                            return o;
                        };
                        var formData = $('#mBuilder-form').serializeObject();
                        var regex = /align="(.*?)"/;
                        if (t.models.models[el_id].attr) {
                            var res = t.models.models[el_id].attr.match(regex);
                        } else {
                            var res = null;
                        }
                        if (res != null) {
                            formData.align = res[1];
                        }
                        var isTab = false;
                        if (t.tabs[params['type']] || params['type'] == 'vc_row_inner') isTab = true;

                        if (params['type'] == 'vc_column' || params['type'] == 'vc_column_inner') {
                            if (params['attr'] && params['attr'] != '' && params['attr'].match(/^(width=['"].*?['"])|.*? width=['"].*?['"]/g)) {
                                formData.width = params['attr'].match(/^(width=['"].*?['"])| width=['"].*?['"]/g);
                                formData.width = formData.width[formData.width.length - 1].replace(/(width=)|(['"])|(undefined)|( )/g, '');
                            }
                        }
                        t.updateShortcode(el_id, params['type'], formData, undefined, isTab);
                        cssInput && cssInput.remove();
                    },
                    'Close', function () {
                        $('.setting-panel-close').click();
                    }
                );
                var isLocal = $.ui.tabs.prototype._isLocal;
                $.ui.tabs.prototype._isLocal = function (anchor) {
                    return true;
                };
                $('#mBuilderTabs').tabs();
                $('.setting-panel-wrapper .setting-panel-container').removeClass('dont-show');
                setTimeout(function () {
                    t.dependencyInjection();
                }, 1);
                var name = $(that).attr('data-tabName');
                $('.waitforload').find('a[href="#mBuilder'+name+'"]').click();
            }
        });
    });

    // Delete Element
    $body.on('click', '.mBuilder-element .mBuilder_row_delete,.mBuilder-element .sc-delete', function (e) {
        var el_id = $(this).closest('.mBuilder-element').attr('data-mBuilder-id');
        $(this).parents('.mBuilder_controls').addClass('active-gizmo');
        e.stopPropagation();

        var $elem = $('div[data-mbuilder-id=' + el_id + ']');
        if ($elem.hasClass('mBuilder-md_button')) {
            deleteFunc(el_id);
        } else if (!$(this).closest('.mBuilder_controls').find('.deleteMessage').length) {

            //close option panel on click
            var $this = $(this),
                $optionsHolder = $(this).closest('.mBuilder_container, .options-holder');

            var deleteBox = '<div class="deleteMessage"><p>' + mBuilderValues.deleteDescText + '</p><a class="deleteBtn">' + mBuilderValues.deleteText + '</a></div>';

            TweenMax.to($optionsHolder, .2, {
                scale: .9, opacity: 0, onComplete: function () {
                    TweenMax.set($optionsHolder, {height: 0, zIndex: -333});
                    //add delete alertBox

                    var $parent = $this.closest('.mBuilder_controls.sc-control,.mBuilder_row_controls');

                    $parent.after(deleteBox);

                    var $deleteMsgBox = $parent.siblings('.deleteMessage'),
                        $deleteBtn = $deleteMsgBox.find('.deleteBtn');


                    //deletBox Animation
                    // for tab
                    if ($elem.hasClass('mBuilder-md_tab') || $elem.hasClass('mBuilder-md_modernTab')) {

                        var left = parseInt($elem.find(' > .mBuilder_controls.tab ').css('left'));
                        left += 44;
                        $elem.find(' > .deleteMessage').css({'left': left})

                    } else if ($elem.hasClass('mBuilder-md_hor_tab') || $elem.hasClass('mBuilder-md_hor_tab2')) {
                        var top = parseInt($elem.find(' > .mBuilder_controls.tab ').css('top'));
                        top += 44;
                        $elem.find(' > .deleteMessage').css({'top': top})
                    } else if ($elem.hasClass('vc_row')) {
                        if ($('body .vc_row').first().attr('id') == $elem.attr('id')) {
                            top = '40%';
                            $elem.find(' > .deleteMessage').css({'top': top})
                        }
                    }

                    TweenMax.to($deleteMsgBox, .2, {opacity: 1, bottom: '30px'});

                    if ($parent.hasClass('sc-control')) {
                        $parent.addClass('deleteEffect')
                    } else {
                        $parent.siblings('.wrap,.sc-control').addClass('deleteEffect')
                    }

                    $deleteBtn.click(function () {
                        deleteFunc(el_id);
                    })

                    $(document).click(function (e) {
                        e.stopPropagation();
                        TweenMax.to($deleteMsgBox, .3, {
                            opacity: 0, bottom: '20px', onComplete: function () {
                                $deleteMsgBox.remove();
                            }
                        });
                        $deleteMsgBox.parents('.mBuilder_controls').removeClass('active-gizmo');

                        if ($parent.hasClass('sc-control')) {
                            $parent.removeClass('deleteEffect')
                        } else {
                            $deleteMsgBox.siblings('.wrap').removeClass('deleteEffect');

                        }
                    });

                }
            });
            $optionsHolder.removeClass('open');
            toggle = -1;

        }

        function deleteFunc(el_id) {
            t.deleteModel(el_id);

            var p = $('div[data-mbuilder-id=' + el_id + ']').parent().closest('.mBuilder-element');

            // for tab
            var $elem = $('div[data-mbuilder-id=' + el_id + ']');
            if ($elem.hasClass('mBuilder-md_tab') || $elem.hasClass('mBuilder-md_modernTab')
                || $elem.hasClass('mBuilder-md_hor_tab') || $elem.hasClass('mBuilder-md_hor_tab2')) {
                var id = $elem.children('.wpb_tab ').attr('id');
                $('a[href="#' + id + '"]').parent().remove();
                $(window).resize();

            }

            $('div[data-mbuilder-id=' + el_id + ']').remove();
            if (p.attr('data-mbuilder-el') == 'vc_column' || p.attr('data-mbuilder-el') == 'vc_column_inner') {
                if (!p.find('.mBuilder-element').length) {
                    p.find('.wpb_column').addClass('vc_empty-element');
                }
            }

            t.createPlaceholders();
            $('body').css('height',$('body').css('height'));
            $('body').css('height','auto');
        }
    });

    // Copy Element
    $body.on('click', '.mBuilder_row_duplicate,.mBuilder-element .sc-duplicate,.tab .sc-duplicate', function (e) {
        e.stopPropagation();

        TweenMax.set($(this).closest('.options-holder'), {height: 0, zIndex: -333, scale: .9, opacity: 0});
        closeAll();

        var t = builder;
        var el = $(this).closest('.mBuilder-element'),
            el_id = el.attr('data-mBuilder-id');
        do {
            var newID = Math.floor(100 + (Math.random() * 300) + 1);
        }
        while (t.models.models.hasOwnProperty(newID));
        t.models.models[newID] = JSON.parse(JSON.stringify(t.models.models[el_id]));
        var $container = $('div[data-mbuilder-id=' + el_id + ']'),
            $containerPlaceholder = $container.next('.insert-between-placeholder,.insert-after-row-placeholder'),
            $newContainer = $container.clone().attr('data-mbuilder-id', newID),
            $newContainerPlaceholder = $containerPlaceholder.clone();
        $containerPlaceholder.after($newContainer);
        $newContainer.after($newContainerPlaceholder);
        if ($(this).hasClass('mBuilder_row_duplicate')) {
            var el = $('div[data-mBuilder-id=' + newID + ']');
            el.find('.mBuilder-element').each(function () {
                var child_id = $(this).attr('data-mBuilder-id');
                do {
                    var newID = Math.floor(100 + (Math.random() * 300) + 1);
                }
                while (t.models.models.hasOwnProperty(newID));
                t.models.models[newID] = JSON.parse(JSON.stringify(t.models.models[child_id]));
                $(this).attr('data-mBuilder-id', newID);
            });
        }
        t.renderControls();
        t.setSortable();
        var $newElm = $('div[data-mbuilder-id=' + newID + ']');
        if ($('div[data-mbuilder-id=' + el_id + ']').data('mbuilder-el') == 'md_text' || $('div[data-mbuilder-id=' + el_id + ']').hasClass('vc_row')) {
            t.remove_tinymce();
            $newElm.find('.inline-editor').addClass('inline-editor-call').removeClass('mce-content-body mce-edit-focus').attr('id', '');
            $newElm.find('.inline-editor-title').addClass('inline-editor-title-call').removeClass('mce-content-body mce-edit-focus').attr('id', '');
            setTimeout(function () {
                t.calltinymcecontent('.inline-editor-call');
                t.calltinymcetitle('.inline-editor-title-call');
                $('div[data-mbuilder-id=' + newID + ']').find('.inline-editor').removeClass('inline-editor-call');
                $('div[data-mbuilder-id=' + newID + ']').find('.inline-editor-title').removeClass('inline-editor-title-call');
            }, 100);
        }

    });

    // Element Alignments
    $body.on('click', '.mBuilder-element .sc-alignment span', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var element = $(this).closest('.mBuilder-element');
        var id = element.attr('data-mbuilder-id');

        var regex = /(align=".*?")/g;
        t.models.models[id].attr = t.models.models[id].attr.replace(regex, '');
        if ($(this).hasClass('left') || $(this).hasClass('mdb-leftalignsvg')) {
            e.preventDefault();
            t.models.models[id].attr += ' align="left"';
            element.find('[class *= "md-align-"]')
                .removeClass('md-align-right')
                .removeClass('md-align-center')
                .addClass('md-align-left')
        }
        if ($(this).hasClass('center') || $(this).hasClass('mdb-centeralignsvg')) {
            e.preventDefault();
            t.models.models[id].attr += ' align="center"';
            element.find('[class *= "md-align-"]')
                .removeClass('md-align-right')
                .removeClass('md-align-left')
                .addClass('md-align-center')
        }
        if ($(this).hasClass('right') || $(this).hasClass('mdb-rightalignsvg')) {
            e.preventDefault();
            t.models.models[id].attr += ' align="right"';
            element.find('[class *= "md-align-"]')
                .removeClass('md-align-center')
                .removeClass('md-align-left')
                .addClass('md-align-right')
        }
    });

    // Hover on delete shortcode button
    $body.on({
        mouseenter: function () {
            $(this).closest('.mBuilder_controls').addClass('delete_hover');
        },
        mouseleave: function () {
            $(this).closest('.mBuilder_controls').removeClass('delete_hover');
        }
    }, '.mBuilder-element .sc-delete');

    // open and close setting drop down menu
    $body.on('click', '.mBuilder_row_controls .mBuilder_setting_panel,.mBuilder_row_layout,.mbuilder-column-options,.sc-option', function (e) {
        e.stopPropagation();
        var $this = $(this),
            $innerRow = $this.closest('.mBuilder-vc_row_inner'),
            $container = $this.find('>.mBuilder_container, > .options-holder, > .extra-option');

        if (!$container.hasClass('open')) {
            closeAll(this);

            if ($this.closest('.gizmo-container').length) {
                $this.closest('.gizmo-container').addClass('active-gizmo');
            } else if ($this.hasClass('sc-option')) {
                $this.closest('.mBuilder_controls').addClass('active-gizmo');
            }

            $this.closest('.mBuilder-element').addClass('onTop');

            TweenMax.set($container, {height: 'auto', zIndex: 333});
            TweenMax.to($container, .2,
                {scale: 1, opacity: 1});

            $this.closest('div[class*=mBuilder-vc_column]').addClass('upper_zIndex');

            if ($this.hasClass('mBuilder_setting_panel') || $this.hasClass('mBuilder_row_layout')) {
                $this.closest('div[class*=vc_row]').addClass('upper_zIndex');
            }

            $this.find('>.mBuilder_container, .options-holder').removeClass('open');

            /* inner Row */
            if ($innerRow.length) {
                $innerRow.addClass('upper_inner_row_zIndex');
                $innerRow.parents('.vc_row').addClass('upper_inner_row_zIndex');
                $innerRow.siblings('.mBuilder-element').addClass('lower_inner_row_zIndex')
            }

        } else {
            closeAll();
        }

        if ($this.hasClass('mBuilder_row_layout')) {
            $this.find('input').focus();
        }

        // set sc-option position for first row
        var $thisScOptionPositionY = parseInt(e.clientY) - 100;
        var $thisScOptionOpen = parseInt($("div.options-holder.open").height());

        if ($thisScOptionPositionY <= $thisScOptionOpen) {
            $(".vc_row .sc-option .options-holder").css({'top': '48px', 'z-index': '99999999'});
        }
        else {
            $(".vc_row .sc-option .options-holder").css({'top': ($thisScOptionOpen * (-1)) - 7, 'z-index': '99999999'});
        }
        // END set sc-option position for first row

    });
    var time = [];
    $body.on('mouseenter', '.mBuilder_row_controls .mBuilder_setting_panel,.mBuilder_row_layout, .mbuilder-column-options ', function (e) {
        e.stopPropagation();
        clearTimeout(time[$(this)]);
    });

    $body.on('mouseleave', '.mBuilder_row_controls .mBuilder_setting_panel,.mBuilder_row_layout, .mbuilder-column-options', function (e) {
        e.stopPropagation();
        var $this = $(this),
            $innerRow = $this.closest('.mBuilder-vc_row_inner'),
            $container =$this.find('>.mBuilder_container, > .options-holder, > .extra-option') ;
        clearTimeout(time[$this]);
        time[$this] = setTimeout(function () {
            TweenMax.to($container, .3,
                {
                    scale: .9, opacity: 0, delay: .2, onComplete: function () {
                    TweenMax.set($container, {height: 0, zIndex: -333});
                }
                });
            $this.closest('div[class*=mBuilder-vc_column]').removeClass('upper_zIndex');

            if ($this.hasClass('mBuilder_setting_panel') || $this.hasClass('mBuilder_row_layout')) {
                $this.closest('div[class*=vc_row]').removeClass('upper_zIndex');
            }

            $container.removeClass('open');

            if ($this.hasClass('mBuilder_row_layout')) {
                $this.find('input').focus();
            }

            if ($innerRow.length) {
                $innerRow.removeClass('upper_inner_row_zIndex');
                $innerRow.parents('.vc_row').removeClass('upper_inner_row_zIndex');
                $innerRow.siblings('.mBuilder-element').removeClass('lower_inner_row_zIndex')
            }

            toggle = -1;
            closeAll($this);
        }, 500);

    });

    // open shortcode setting panel on double click
    $body.on('dblclick', '.sc-control', function () {
        $(this).find('.sc-setting').click();

    });

}


/**
 * @summary creates placeholders and droppable areas.
 *
 * @since 1.0.0
 */

mBuilder.prototype.createPlaceholders = function () {
    $('.insert-between-placeholder').remove();
    $('.insert-after-row-placeholder').remove();
    var containers = '';
    for (i in this.shortcodes) {
        if (this.shortcodes[i].as_parent && this.shortcodes[i].as_parent.only)
            containers += "[data-mbuilder-el='" + this.shortcodes[i].as_parent.only + "'],";
    }
    containers = containers.slice(0, -1);
    $('<div/>').addClass('insert-between-placeholder').insertAfter($('.mBuilder-element').not('.vc_row').not(containers));
    $('.mBuilder-vc_column').each(function () {
        $('<div/>').addClass('insert-between-placeholder').insertBefore($(this).find('.wpb_wrapper:first-of-type .mBuilder-element:first-of-type').not(containers));
    });

    $('.insert-between-placeholder').each(function () {
        $(this).attr('data-index', $('div').index(this));
    });

    var rows = $('.vc_row').not('.vc_inner');
    $('<div/>').addClass('insert-after-row-placeholder').insertAfter(rows);
    $('<div/>').addClass('insert-after-row-placeholder').prependTo('.content-container');

    if (!
            $('.mBuilder-element').length) {
        var content = $('<div><p>This page is empty. Drag a shortcode here.</p></div>'),
            btn = $('<span id="p-btn-addshortcode">Add Shortcode</span>');
        $('.insert-after-row-placeholder').html(content);
        content.append(btn);
        btn.click(function (e) {
            e.stopPropagation();
            $('.pixflow-add-element-button').click();
        });

        $('.insert-after-row-placeholder').first().addClass('blank-page');
    } else {
        $('.insert-after-row-placeholder').first().removeClass('blank-page').off('click');
    }

    pixflow_footerPosition();
};


/**
 * @summary remove from models object.
 *
 * @param {integer} id
 * @since 1.0.0
 */

mBuilder.prototype.deleteModel = function (id) {
    var t = this;
    for (var index in t.models.models) {
        var $el = $('div[data-mBuilder-id=' + index + ']'),
            $parent = $el.parent().closest('.mBuilder-element');
        if ($parent.length) {
            var parentId = $parent.attr('data-mBuilder-id');
            t.models.models[index].parentId = parentId;
        }
    }
    delete t.models.models[id];
    for(var element_num in t.models.models ) {
        var elements = t.models.models[element_num] ;
        if (elements['parentId'] == id) {
            t.deleteModel(element_num);
        }
    }
    $('body').addClass('changed');

};


/**
 * @summary apply row layout changes.
 *
 * @param {string} exp - layout expression example: (3/12)+(3/12)+(3/12)+(3/12)
 * @param {object} row - jQuery Object
 * @since 1.0.0
 */

mBuilder.prototype.changeRowLayout = function (exp, row) {
    var t = this;
    if (exp.match(/([0-9]+)\/12/g)) {
        var columns = exp.match(/([0-9]+)\/12/g);
        var sum = 0;
        for (i in columns) {
            var size = parseInt(columns[i].replace('/12', ''));
            sum += size;
        }
        if (sum > 12) {
            alert('Sum of all columns is greater than 12 columns.');
            return;
        } else if (sum < 12) {
            alert('Sum of all columns is less than 12 columns.');
            return;
        }
        var i = 0;
        row.find('[data-mbuilder-el="vc_column"],[data-mbuilder-el="vc_column_inner"]').first()
            .siblings('[data-mbuilder-el="vc_column"],[data-mbuilder-el="vc_column_inner"]').addBack().each(function () {
            if (columns[i]) {
                var size = columns[i].replace('/12', '');
                $(this).find('> .vc_column_container').removeClass(function (index, css) {
                    return (css.match(/(^|\s)col-sm-[0-9]+/g) || []).join(' ');
                }).addClass('col-sm-' + size);
                $(this).removeClass(function (index, css) {
                    return (css.match(/(^|\s)col-sm-[0-9]+/g) || []).join(' ');
                }).addClass('col-sm-' + size);

                if(t.models.models[$(this).attr('data-mbuilder-id')].attr == undefined){
                    t.models.models[$(this).attr('data-mbuilder-id')].attr = '';
                }
                if (t.models.models[$(this).attr('data-mbuilder-id')].attr && t.models.models[$(this).attr('data-mbuilder-id')].attr != '' && builder.models.models[$(this).attr('data-mbuilder-id')].attr.match(/^(width=)|.*? width=/g)) {
                    t.models.models[$(this).attr('data-mbuilder-id')].attr = t.models.models[$(this).attr('data-mbuilder-id')].attr.replace(/[^-_]width=["'].*?["']/g, ' width="' + columns[i] + '"');
                } else {
                    t.models.models[$(this).attr('data-mbuilder-id')].attr += ' width="' + columns[i] + '"';
                }
                i++;
            } else {
                var el_id = $(this).attr('data-mbuilder-id'),
                    $el = $('div[data-mBuilder-id=' + el_id + ']'),
                    $lastCol = row.find('> .wrap > .mBuilder-vc_column, > .wrap > .mBuilder-vc_column_inner').eq(columns.length - 1).find('.vc_column-inner > .wpb_wrapper');
                $el.find('.vc_column-inner > .wpb_wrapper > .mBuilder-element').each(function () {
                    var $obj = $(this).appendTo($lastCol);
                    $obj.after('<div class="insert-between-placeholder" data-index=""></div>');
                });
                t.deleteModel(el_id);
                $(this).remove();
            }
        });

        if (i < columns.length) {
            if (!t.lock) {
                var j = i;
                t.lock = true;
                for (i; i < columns.length; i++) {
                    if (row.hasClass('vc_inner')) {
                        t.buildShortcode(row, 'vc_column_inner', {width: columns[i]}, function () {
                            j++;
                            if (j == columns.length) {
                                t.lock = false;
                            }
                        });
                    } else {
                        t.buildShortcode(row, 'vc_column', {width: columns[i]}, function () {
                            j++;
                            if (j == columns.length) {
                                t.lock = false;
                            }
                        });
                    }
                }
            }
        }

    } else {
        alert('You entered wrong pattern, try premade patterns instead.');
    }
};

/**
 * @summary open shortcode setting panel.
 *
 * @param {string} title
 * @param {string} customClass
 * @param {string} text
 * @param {string} btn1
 * @param {function} callback1 - optional
 * @param {string} btn2 - optional
 * @param {function} callback2 - optional
 * @param {function} closeCallback - optional
 * @since 1.0.0
 */
mBuilder.prototype.mBuilder_shortcodeSetting = function (title, customClass, text, btn1, callback1, btn2, callback2, closeCallback) {
    "use strict";
    var t = this;
    if ($('.setting-panel-wrapper').length) {
        $('.setting-panel-wrapper .setting-panel-title').html(title);
        $('.setting-panel-wrapper .setting-panel-text').html(text);
        $('.setting-panel-wrapper .setting-panel-container').attr('class', '').addClass('setting-panel-container ' + customClass);
        $('.setting-panel-wrapper .setting-panel-btn1').html(btn1);
        $('.setting-panel-wrapper .setting-panel-btn2').html(btn2);
        var $messageBox = $('.setting-panel-wrapper'),
            $btn1;
    } else {
        var $messageBox = $('' +
                '<div class="setting-panel-wrapper">' +
                '   <div class="setting-panel-container ' + customClass + '">' +
                '       <div class="setting-panel-close"/>' +
                '       <div class="setting-panel-title">' + title + '</div>' +
                '       <div class="setting-panel-text">' + text + '</div>' +
                '       <button class="setting-panel-btn1">' + btn1 + '</button>' +
                '   </div>' +
                '</div>').appendTo('body'),
            $btn1;
    }
    $messageBox.animate({opacity: 1}, 200);
    $messageBox.find('.setting-panel-container').draggable();
    this.settingPanel = $messageBox;
    $btn1 = $messageBox.find('.setting-panel-btn1');
    $btn1.off('click');
    $btn1.click(function (e) {
        e.preventDefault();
        if (typeof callback1 == 'function') {
            callback1();
        }
    });
    if (btn2) {
        if ($messageBox.find('.setting-panel-btn2').length) {
            var $btn2 = $messageBox.find('.setting-panel-btn2');
        } else {
            var $btn2 = $('<button class="setting-panel-btn2">' + btn2 + '</button>').insertBefore($btn1);
        }
        $btn2.off('click');
        $btn2.click(function (e) {
            e.preventDefault();
            if (typeof callback2 == 'function') {
                callback2();
            }
        });
    }

    var $close = $messageBox.find('.setting-panel-close');
    $close.off('click');
    $close.click(function (e) {
        e.preventDefault();
        if (typeof closeCallback == 'function') {
            closeCallback();
        }
        t.mBuilder_closeShortcodeSetting();
    });
};


/**
 * @summary close shortcode setting panel.
 *
 * @since 1.0.0
 */

mBuilder.prototype.mBuilder_closeShortcodeSetting = function () {
    "use strict";
    $('.sp-container').remove();
    $('.setting-panel-wrapper').fadeOut(300, function () {
        $(this).remove();
    })
};


/**
 * @summary get Model
 *
 * @param {integer} id - model ID
 *
 * @return {object} - model
 * @since 1.0.0
 */

mBuilder.prototype.getModelParams = function (id) {
    return this.models.models[id];
};


/**
 * @summary Add Shortcode Panel to the customizer side
 *
 * @since 1.0.0
 */

var clear_shortcodes_panel_animation_open;
var clear_shortcodes_panel_animation_close;
mBuilder.prototype.shortcode_panel_functionality = function () {
    var t = this;

    t.add_nicescroll();
    t.search_shortcode();

    $('.pixflow-shortcodes-panel .pixflow-add-element-button').click(function () {
        clearTimeout(clear_shortcodes_panel_animation_open);
        clearTimeout(clear_shortcodes_panel_animation_close);
        if( $('.active-preview').length ){
            $('.builder-preview').click();
        }
        $(this).toggleClass('close-element-button');
        t.shortcode_panel_animation();
    });

    t.shortcode_panel_drop_shortcodes();

};
mBuilder.prototype.shortcode_panel_drop_shortcodes = function(){
    var t = this;
    var shortcode = null;
    var placeholder = null;

    var lastObj = null;

    var d = $('<div class="mBuilder-drag-overlay"></div>').appendTo('body'),
        direction = 'down',
        overEmpty = false;
    $('.pixflow-shortcodes-panel .shortcodes').draggable({
        appendTo: "body",
        helper: "clone",
        zIndex: 9999999,
        cursorAt: {top: 20, left: 50},
        start: function (event, ui) {
            t.createColumnSeparator('all');
            t.shortcode_panel_animation();

            clearInterval(fly);
            shortcode = $(this).attr('id');
            $(this).css('visibility', 'hidden')

        },
        drag: function (event, ui) {

            shortcode_panel_drag_fly(event);

            if(open_shortcode_panel_on_sides(event)){
                return true;
            }

            var el = get_hovered_element(ui.helper,event.clientX, event.clientY);

            if (el) {
                if (el == d.get(0)) return true;

                if(is_over_shortcode_panel(el)){
                    return true;
                }
                close_shortcode_panel_on_drag();

                overEmpty = false;

                var obj = $(el).closest('.mBuilder-element,.vc_inner ,.content-container');
                if (obj.hasClass('content-container') && $('.content-container').find('.blank-page').length) {
                    obj = $(el).closest('.mBuilder-element,.vc_inner');
                }
                if(check_over_page(obj)){
                    return true;
                }
                if(!check_over_shortcode(obj)){
                    var is_over_row = check_over_rows(obj);
                    if(is_over_row[0]){
                        obj = is_over_row[1];
                    }else{
                        if(!check_over_empty_page(el)){
                            if (lastObj) {
                                lastObj.css({'transform': ''})
                            }
                            lastObj = null;
                            d.css({width: '', border: ''});
                            return true;
                        }
                    }
                }
                if(!obj.length){
                    return true;

                }
                var objTop = obj.offset().top,
                    objLeft = obj.offset().left,
                    objHeight = obj.outerHeight(),
                    objWidth = obj.outerWidth(),
                    objHalf = objTop + objHeight / 2;
                if (lastObj) {
                    lastObj.css({'transform': ''})
                }
                if (!overEmpty) {
                    if (event.clientY + $(window).scrollTop() -50 < objHalf) {
                        obj.not('.vc_row').css({'transform': 'translateY(5px)'});
                        d.css({'top': objTop, 'left': objLeft, width: objWidth, height: 5, background: ''});
                        direction = 'up';
                    } else {
                        obj.not('.vc_row').css({'transform': 'translateY(-5px)'});
                        d.css({
                            'top': objTop + objHeight,
                            'left': objLeft,
                            width: objWidth,
                            height: 5,
                            background: ''
                        });
                        direction = 'down';
                    }
                } else {
                    d.css({
                        'top': objTop,
                        'left': objLeft,
                        height: objHeight,
                        width: objWidth,
                        background: 'rgba(136,206,255,0.4)',
                        border: 'solid 2px #8fcbff'
                    });
                }
                lastObj = obj;
            } else {
                if (lastObj) {
                    lastObj.css({'transform': ''})
                }
                lastObj = null;
                d.css({width: '', border: ''});
            }
        },
        stop: function (event, ui) {
            t.clear_shortcodes_panel_input();
            t.removeColumnSeparator();
            clearInterval(fly);
            t.shortcode_panel_animation();
            try {
                $('.pixflow-shortcodes-panel').getNiceScroll().resize();
            } catch (e) {}
            $(this).css('visibility', 'visible')
            if (!lastObj || !lastObj.length) {
                return;
            }
            lastObj.css({'transform': ''})

            if (direction == 'up') {
                if (lastObj.hasClass('vc_row') && !lastObj.hasClass('vc_inner')) {
                    if (lastObj.prev('.insert-after-row-placeholder').length) {
                        var p = lastObj.prev('.insert-after-row-placeholder');
                    } else {
                        var p = lastObj.prev().prev('.insert-after-row-placeholder');
                    }
                } else if (lastObj.hasClass('blank-page')) {

                    var p = lastObj;
                } else if (lastObj.hasClass('vc_empty-element')) {
                    var p = lastObj.closest('.vc_column_container');
                } else if (t.containers[lastObj.attr('data-mbuilder-el')] && overEmpty) {
                    var p = lastObj.find(t.containers[lastObj.attr('data-mbuilder-el')]);
                } else {
                    var p = lastObj.prev('.insert-between-placeholder');
                    if (!p.length) {
                        var p = lastObj.parent().closest('.mBuilder-element').prev('.insert-between-placeholder');
                    }
                }
            } else {

                if (lastObj.hasClass('vc_row') && !lastObj.hasClass('vc_inner')) {
                    var p = lastObj.next('.insert-after-row-placeholder');
                } else if (lastObj.hasClass('blank-page')) {

                    var p = lastObj;
                } else if (lastObj.hasClass('vc_empty-element')) {
                    var p = lastObj.closest('.vc_column_container');
                } else if (t.containers[lastObj.attr('data-mbuilder-el')] && overEmpty) {
                    var p = lastObj.find(t.containers[lastObj.attr('data-mbuilder-el')]);
                } else {
                    var p = lastObj.next('.insert-between-placeholder');
                    if (!p.length) {
                        var p = lastObj.parent().closest('.mBuilder-element').next('.insert-between-placeholder');
                    }
                }
            }
            placeholder = p.get(0);
            d.css({'width': '', border: ''});
            if (lastObj.hasClass('content-container')) {
                placeholder = $('.insert-after-row-placeholder').last();
                t.buildShortcode(placeholder, 'vc_row', {}, function (response) {
                    if($('body').hasClass('one_page_scroll')){
                        pixflow_one_page_for_customizer();
                    }
                    if (shortcode == 'vc_row') {
                        return;
                    }
                    t.buildShortcode(response.find('.vc_column_container'), shortcode);
                });
            }
            else {
                if (placeholder != null) {
                    if (p.hasClass('insert-after-row-placeholder')) {
                        t.buildShortcode(placeholder, 'vc_row', {}, function (response) {
                            if($('body').hasClass('one_page_scroll')){
                                pixflow_one_page_for_customizer();
                            }
                            if (shortcode == 'vc_row') {
                                return;
                            }
                            t.buildShortcode(response.find('.vc_column_container'), shortcode);
                        });
                    } else {
                        if (shortcode == 'vc_row') {
                            shortcode = 'vc_row_inner';
                        }
                        t.buildShortcode(placeholder, shortcode);
                    }
                }
            }
        }
    });

    function check_over_empty_page(el){
        obj = $(el).closest('.blank-page');
        if (obj.length) {
            lastObj = obj;
            var objTop = obj.offset().top + 100,
                objLeft = obj.offset().left + 100,
                objHeight = obj.outerHeight() - 200,
                objWidth = obj.outerWidth() - 200;
            d.css({
                'top': objTop,
                'left': objLeft,
                height: objHeight,
                width: objWidth,
                background: 'rgba(136,206,255,0.4)',
                border: 'solid 2px #8fcbff'
            });
            return true;
        }
        return false;
    }

    function check_over_rows(obj){
        if (obj.hasClass('mBuilder-vc_column') || obj.hasClass('mBuilder-vc_column_inner')) {
            if (obj.find('> .vc_empty-element').length) {
                obj = obj.find('> .vc_empty-element');
                overEmpty = true;
            } else {
                if (!obj.hasClass('mBuilder-vc_column_inner')) {
                    d.css({border: '', borderTop: '4px solid #43dc9d'});
                } else {
                    d.css({border: '', borderTop: '4px solid #8fcbff'});
                    obj = obj.closest('.vc_inner');
                }
            }
            return [true,obj];
        } else if (obj.hasClass('vc_row')) {
            if (!obj.hasClass('vc_inner')) {
                d.css({border: '', borderTop: '4px solid #43dc9d'});
            } else {
                d.css({border: '', borderTop: '4px solid #8fcbff'});
                obj = obj.closest('.vc_inner');
            }
            return [true,obj];
        }
        return [false,obj];
    }
    
    function check_over_shortcode(obj){
        if (obj.length
            && !obj.hasClass('vc_row') && !obj.hasClass('mBuilder-vc_column')
            && !obj.hasClass('mBuilder-vc_column_inner')) {
            if (t.containers[obj.attr('data-mbuilder-el')]) {
                if (!obj.find('.mBuilder-element').length) {
                    overEmpty = true;
                } else {
                    d.css({border: '', borderTop: '4px solid #8fcbff'});
                }
            } else {
                d.css({border: '', borderTop: '4px solid #8fcbff'});
            }
            return true;
        }
        return false;
    }

    function check_over_page(obj){
        if (obj.hasClass('content-container') && $('.vc_row').length) {
            lastObj = obj;
            d.css({
                border: '',
                height: '0px',
                left: $('.vc_row').last().offset().left + 'px',
                borderTop: '4px solid #43dc9d',
                top: $('.vc_row').last().offset().top + $('.vc_row').last().outerHeight() + 'px',
                width: obj.width()
            });
            return true;
        }
        return false;
    }

    function is_over_shortcode_panel(el){
        if($(el).closest('.pixflow-shortcodes-panel').length){
            overEmpty = true;
            lastObj = null;
            d.css({width: '', border: ''});
            return true;
        }
    }

    function close_shortcode_panel_on_drag(){
        if(!$('.pixflow-shortcodes-panel').hasClass('close')){
            $('.pixflow-shortcodes-panel').addClass('close');
        }
    }

    function get_hovered_element(helper,x,y){
        helper.css('display','none');
        var el = document.elementFromPoint(x, y);
        helper.css('display','');
        return el;
    }


    ////////////////////////////////////
    function open_shortcode_panel_on_sides(event){
        if(event.clientX < 5){
            $('.pixflow-shortcodes-panel').removeClass('close');
            overEmpty = true;
            if (lastObj) {
                lastObj.css({'transform': ''})
            }
            lastObj = null;
            d.css({width: '', border: ''});
            return true;
        }
        return false;
    }

    ///////////////////////////////////
    var fly = null;
    function shortcode_panel_drag_fly(event){
        clearInterval(fly);
        if (event.clientY < 100) {
            fly = setInterval(function () {
                if($(window).scrollTop()==0){
                    clearInterval(fly);
                }
                $(window).scrollTop($(window).scrollTop() - 50)
            }, 50);
        } else if (event.clientY > $(window).height() - 50) {
            fly = setInterval(function () {
                if($(window).scrollTop()>=$(document).height()-$(window).height()){
                    clearInterval(fly);
                }
                $(window).scrollTop($(window).scrollTop() + 50)
            }, 50);
        }
    }
}

mBuilder.prototype.add_nicescroll = function(){
    $('.pixflow-shortcodes-container').niceScroll({
        horizrailenabled: false,
        cursorcolor: "rgba(204, 204, 204, 0.2)",
        cursorborder: "1px solid rgba(204, 204, 204, 0.2)",
        cursorwidth: "2px",
        enablescrollonselection: false
    });
};

mBuilder.prototype.search_shortcode = function() {
    var typing_in_search_box_time,
        typing_interval_done = 500,
        $shortcodes_category = $('.pixflow-shortcodes-panel .category-container'),
        $shortcodes = $('.category-container .shortcodes'),
        first_search_value = "";
    $('.pixflow-search-shortcode').keyup(function (e) {

        var search_value = $(this).val().toLowerCase();

        if (first_search_value != search_value) {
            $shortcodes.removeClass('active');
            $shortcodes_category.removeClass('show');

            clearTimeout(typing_in_search_box_time);
            typing_in_search_box_time = setTimeout(function () {
                if (search_value != "") {
                    $('.category-container .shortcodes[data-name*="' + search_value + '"]').addClass('active');
                    $('.category-container .shortcodes[data-name*="' + search_value + '"]').parents('.category-container').addClass('show')
                } else {
                    $shortcodes.addClass('active');
                    $shortcodes_category.addClass('show');
                }
            }, typing_interval_done);
        }
        first_search_value = search_value;
    });

};

mBuilder.prototype.shortcode_panel_animation = function() {
    $('.pixflow-shortcodes-panel').toggleClass('close');
};


/**
 * @summary build shortcode in the placeholder that given.
 *
 * @param {object | string} placeholder - placeholder to drop shortcode.
 * @param {string} shortcode - shortcode type
 * @param {Object} atts - attributes of the shortcode
 * @param {function} callback - a callback function to call after build shortcode
 * @since 1.0.0
 */

mBuilder.prototype.buildShortcode = function (placeholder, shortcode, atts, callback) {
    if (placeholder && shortcode) {
        var t = this,
            atts = atts;
        var loaderHtml = $('<div class="showbox-shotcode">' +
            '<div class="loader-shotcode">' +
            '<svg class="circular-shotcode" viewBox="25 25 50 50">' +
            '<circle class="path-shotcode" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>' +
            '</svg>' +
            '</div></div>');
        if (placeholder.prevObject) {
            var shortcodeloader = $(placeholder.prevObject).find('.vc_column-inner');
            $(loaderHtml).prependTo($(shortcodeloader));
        }
        else if ($(placeholder).hasClass('insert-between-placeholder')) {
            $(placeholder).css({
                'display': 'block',
                'height': '35px'
            }).append(loaderHtml);
        }
        else {
            $(loaderHtml).prependTo($(placeholder));
        }
        $.ajax({
            type: 'post',
            url: mBuilderValues.ajax_url,
            data: {
                action: 'mBuilder_buildShortcode',
                nonce: mBuilderValues.ajax_nonce,
                shortcode: shortcode,
                act: 'build',
                attrs: JSON.stringify(atts),
                mbuilder_editor: true
            },
            success: function (response) {
                if ($(placeholder).hasClass('insert-between-placeholder')) {
                    $(placeholder).css({
                        'display': 'none',
                        'height': '0px'
                    });
                }
                $('.showbox-shotcode').remove();
                var attrs = '';
                $.each(atts, function (index, value) {
                    attrs = attrs + ' ' + index + '="' + value + '"';
                });
                attrs = attrs.trim();
                response = t.setSettings(response, shortcode, placeholder, attrs);
                var id = response['id'];
                response = $(response['shortcode']);
                if ($(placeholder).hasClass('vc_column_container') || $(placeholder).hasClass('vc_row') || $(placeholder).hasClass('vc_row_inner') || $(t.droppables).filter($(placeholder)).length) {
                    if ($(placeholder).hasClass('vc_row') || $(placeholder).hasClass('vc_row_inner')) {
                        $(placeholder).find('>.wrap').append(response);
                    } else if ($(placeholder).find('>.vc_column-inner>.wpb_wrapper').length) {
                        $(placeholder).find('>.vc_column-inner>.wpb_wrapper').append(response);
                    } else {
                        if (!$(placeholder).find('.mBuilder-element').length) {
                            $(placeholder).html('');
                        }
                        $(placeholder).append(response);
                    }

                    $(placeholder).removeClass('vc_empty-element');
                } else if ($(placeholder).hasClass('vc_btn-content')) {
                    if (t.tabs[$(placeholder).closest('.mBuilder-element').attr('data-mbuilder-el')]) {
                        var tab = $(t.tabs[$(placeholder).closest('.mBuilder-element').attr('data-mbuilder-el')][1]);
                        $(placeholder).closest('.mBuilder-element').find('ul').first().append(tab);
                        var unique = Math.floor(Math.random() * 1000000);
                        tab.find('a').attr('href', '#tab-' + unique);
                        response.find('.wpb_tab').first().attr('id', 'tab-' + unique);
                    }
                    if ($(placeholder).closest('.mBuilder-element').find('ul.px_tabs_nav').parent().length) {
                        $(placeholder).closest('.mBuilder-element').find('ul.px_tabs_nav').first().parent().append(response);
                    } else {
                        $(placeholder).closest('.mBuilder-element').find('.wpb_wrapper').first().append(response);
                    }
                    t.updateShortcode($(placeholder).closest('.mBuilder-element').attr('data-mbuilder-id'), $(placeholder).closest('.mBuilder-element').attr('data-mbuilder-el'), t.models.models[$(placeholder).closest('.mBuilder-element').attr('data-mbuilder-id')].attr, undefined, true);
                } else {
                    $(placeholder).before(response);
                    $(placeholder).siblings('.mBuilder-element').not('.vc_row, .vc_row_inner').each(function () {
                    });
                }
                t.createPlaceholders();
                t.specialShortcodes(shortcode, response);
                t.renderControls();
                t.setSortable();
                $(window).resize();
                if (typeof callback == 'function') {
                    callback(response);
                }
                $('body').addClass('changed');
            }
        })
    }
};


/**
 * @summary create shortcode model and add it to the models object
 *
 * @param {string} response - HTML response after build shortcode
 * @param {string} type - shortcode type
 * @param {string | object} parent - parent selector or jQuery object
 * @param {string} atts - attributes of the shortcode
 * @param {string} content - content of the shortcode
 *
 * @return {object} - model ID and HTML of the shortcode
 * @since 1.0.0
 */

mBuilder.prototype.setSettings = function (response, type, parent, atts, content) {
    var rand,
        inModels = true,
        t = this;
    parent = $(parent);
    if (parent.hasClass && parent.hasClass('insert-between-placeholder')) {
        parent = parent.closest('.mBuilder-element').attr('data-mbuilder-id');
    } else {
        parent = parent.attr('data-mbuilder-id');
    }
    var istab = false;
    for (var i in t.tabs) {
        if (t.tabs[i][0] == type) {
            istab = true;
        }
    }
    if (istab) {
        var unique = Math.floor(Math.random() * 1000000);
        atts += ' tab_id=\'' + unique + '\'';
    }

    if (type == 'md_text' && !content) {
        content = $(response).find('.md-text-content').html();
    }
    while (inModels) {
        rand = parseInt(Math.random() * 10000);
        if (typeof this.models.models[rand] == 'undefined') {
            t.models.models[rand] = {
                attr: atts,
                content: content,
                parentId: parent,
                type: type
            };
            inModels = false;
        }
    }

    var o = $(response).clone();
    o.find('.mBuilder-element').each(function () {
        var r = t.setSettings($(this)[0].outerHTML, $(this).attr('data-mBuilder-el'), $(this).parent().closest('.mBuilder-element'));
        $(r['shortcode']).insertAfter($(this));
        $(this).remove();
    });
    var result = [];
    var m_builder_element = o.filter('div').first().attr('data-mbuilder-id', rand);

    o.each(function(){
        if($(this)[0] == m_builder_element[0]) return;
        $(this).removeAttr('data-mbuilder-id').prependTo(m_builder_element);
    });
    result['shortcode'] = m_builder_element[0].outerHTML;
    result['id'] = rand;

    o.remove();
    return result;
};


/**
 * @summary update shortcode model and rebuild it after edit
 *
 * @param {integer} id - ID of shortcode model
 * @param {string} shortcode - shortcode type
 * @param {string | object} attr - attributes of the shortcode
 * @param {string} content - content of the shortcode
 * @since 1.0.0
 */

mBuilder.prototype.updateShortcode = function (id, shortcode, attr, content, asParent) {
    // Update elems object
    var t = this,
        attrs = oldClasses = '';
    if (typeof attr == 'object') {
        $.each(attr, function (index, value) {
            if (index == 'content') {
                return true;
            }
            value = value.replace(new RegExp('"', 'g'), "'");
            attrs = attrs + index + '=' + '"' + value + '" ';
        });
    } else {
        attrs = attr;
    }
    if (!content) {
        var content = '';
        if (shortcode == 'vc_row') {
            content = $('[data-mbuilder-id="' + id + '"]').find('> .wrap').html();
        } else if (t.shortcodes[shortcode] && t.shortcodes[shortcode].as_parent && !t.tabs[shortcode]) {
            content = $('[data-mbuilder-id="' + id + '"]').find('> .wpb_content_element > .wpb_wrapper').html();
        } else if (t.containers[shortcode]) {
            content = $('[data-mbuilder-id="' + id + '"]').find(t.containers[shortcode]).html();
        } else {
            content = attr['content'];
        }

        t.models.models[id]['content'] = attr['content'];
    } else {
        t.models.models[id]['content'] = content;
    }
    t.models.models[id]['attr'] = attrs;
    oldClasses = $('[data-mbuilder-id="' + id + '"]').attr('class');

    attrs = typeof attr == 'object' ? JSON.stringify(attr) : attr;
    // Build shortcode
    $.ajax({
        type: 'post',
        url: mBuilderValues.ajax_url,
        data: {
            action: 'mBuilder_buildShortcode',
            nonce: mBuilderValues.ajax_nonce,
            shortcode: shortcode,
            act: 'rebuild',
            id: id,
            content: content,
            attrs: attrs,
            mbuilder_editor: true
        },
        success: function (response) {
            var container = $('.mBuilder-element[data-mbuilder-id=' + id + ']');
            html = document.createElement('div');
            html.innerHTML = response ;
            for(var i = 0 ; i < html.children.length ; i ++){
                html.children[i].setAttribute('data-mbuilder-id', id);
            }
            var parent = container.parent().closest('.mBuilder-element');
            if (asParent || (
                    parent.length &&
                    t.shortcodes[parent.attr('data-mbuilder-el')] &&
                    t.shortcodes[parent.attr('data-mbuilder-el')].as_parent &&
                    t.shortcodes[parent.attr('data-mbuilder-el')].as_parent.only == container.attr('data-mbuilder-el')
                )
            ) {
                var parentId = parent.attr('data-mbuilder-id');
                var type = parent.attr('data-mbuilder-el');
                $.ajax({
                    type: 'post',
                    url: mBuilderValues.ajax_url,
                    data: {
                        action: 'mBuilder_doShortcode',
                        nonce: mBuilderValues.ajax_nonce,
                        shortcode: t.shortcodeTag(parent, false),
                        mbuilder_editor: true
                    },
                    success: function (response) {
                        try {
                            parent.replaceWith(response);
                            var id = $(response).find('[data-mbuilder-el="' + shortcode + '"]').first().attr('data-mbuilder-id');
                            t.specialShortcodes(shortcode, $('[data-mbuilder-id="' + id + '"]'));
                            for (var i in t.shortcodes) {
                                if (t.shortcodes[i].as_parent && t.shortcodes[i].as_parent.only == shortcode) {
                                    t.specialShortcodes(i, $('[data-mbuilder-id="' + id + '"]').closest('[data-mbuilder-el="' + i + '"]'));
                                }
                            }
                            t.renderControls();
                            t.setSortable();
                            $(window).resize();
                        } catch (e) {
                            console.log(e);
                        }
                    }
                })
            } else {
                try {
                    container.replaceWith(html.innerHTML);
                    html = '' ;
                    html = $(response);
                    t.specialShortcodes(shortcode, html);
                    t.renderControls();
                    $(window).resize();
                } catch (e) {
                    console.log(e);
                }
            }
            var $mbuilderId = $('[data-mbuilder-id="' + id + '"]');
            if ($mbuilderId.data('mbuilder-el') == 'md_text') {
                $mbuilderId.attr('class', oldClasses);
            }
            if($mbuilderId.hasClass('vc_row') && $('body').hasClass('one_page_scroll')){
                pixflow_one_page_for_customizer();
            }
            t.setSortable();
            $('body').addClass('changed');
            var $mbuilderId = $('[data-mbuilder-id="' + id + '"]');
            $mbuilderId.find('.md-text-title').removeClass('mce-content-body do-save').removeAttr('id');
            $mbuilderId.find('.md-text-content').removeClass('mce-content-body do-save').removeAttr('id');
            t.calltinymcecontent();
            t.calltinymcetitle();

        }
    })

};

/**
 * @summary generate shortcodeTag from DOM element
 *
 * @param {object} obj - DOM element | jQuery element
 * @param {bool} onlyChildren - if true it returns just children shortcodeTags
 * @param {int} depth - used in recursive calls
 *
 * @return {string} - shortcodeTag
 * @since 1.0.0
 */
mBuilder.prototype.shortcodeTag = function (obj, onlyChildren, depth) {
    var t = this,
        el = $(obj),
        id = el.attr('data-mbuilder-id');

    if (!el.length) {
        return '';
    }
    if (!depth) {
        depth = 0;
    }
    var model = t.models.models[id];
    model.attr = model.attr != undefined ? model.attr : '';
    model.content = model.content != undefined ? model.content : '';
    if (!onlyChildren) {
        var tag = '[' + model.type + ' ' + model.attr + ' mbuilder-id="' + id + '"]' + model.content;
    }
    depth++;

    el.find('.mBuilder-element').each(function () {
        for (var i in t.compiledTags) {
            if (t.compiledTags[i] == this) return;
        }
        tag += t.shortcodeTag(this, false, depth);
    });
    t.compiledTags.push(el.get(0));
    depth--;

    if (!onlyChildren) {
        tag += '[/' + model.type + ']';
    }
    if (depth == 0) {
        t.compiledTags = [];
    }
    return tag;
};

/**
 * @summary save contents and shortcodes to the database
 *
 * @since 1.0.0
 */

mBuilder.prototype.saveContent = function () {
    var t = this;
    $('body').addClass('content-saving');
    // Set Parents
    this.set_parents();
    // Calculate orders
    $('.mBuilder-element').each(function () {
        var $el = $(this),
            id = $el.attr('data-mBuilder-id');

        var order = 1;
        $el.siblings(".mBuilder-element").addBack().each(function () {
            t.models.models[$(this).attr('data-mbuilder-id')]['order'] = order++;
        });
    });
    var models = {};
    for(i in t.models.models){
        if(t.models.models[i]!=null){
            models[i] = t.models.models[i];
        }
    }
    this.save_button_animation_start();
    $.ajax({
        type: 'post',
        url: mBuilderValues.ajax_url,
        data: {
            action: 'mBuilder_saveContent',
            nonce: mBuilderValues.ajax_nonce,
            models: JSON.stringify(models),
            id: $('meta[name="post-id"]').attr('content'),
            mbuilder_editor: true
        },
        success: function (response) {
            $('body').removeClass('content-saving changed');
            t.save_button_animation_end();
            if(typeof t.save_callback_function == 'function') {
                t.save_callback_function();
            }
        }
    });
};


/**
 * @summary Apply dependencies to the shortcode setting panel
 *
 * @since 1.0.0
 */
var dependChange = [] ;
mBuilder.prototype.dependencyInjection = function () {
    var tabs = this.settingPanel.find('#mBuilderTabs > ul li');
    this.settingPanel.find('[data-mBuilder-dependency]').each(function () {
        var json = JSON.parse($(this).attr('data-mBuilder-dependency'));
        var el = $(this);
        if(typeof json.element == 'undefined')
            return ;
        var depend = $('[name=' + json.element + ']');
        dependChange.push(depend);
        if (depend.attr('type') != 'hidden') {

            //
            if (typeof json.value != 'object') {
                json.value = [json.value];
            }
            if ($.inArray($(depend).val(), json.value) != -1 && $(depend).closest('.vc_column').css('display') == 'block') {
                el.css('display', 'block');
            } else {
                el.css('display', 'none');
            }
            el.find('select,input').trigger('change');
            tabs.each(function () {
                var id = $(this).attr('aria-controls');
                var result = false;
                var element = document.getElementById(id);
                $(element).find('>.vc_column').each(function () {
                    if ($(this).css('display') == 'block') {
                        result = true;
                        return false;
                    }
                });
                if (!result) {
                    $(this).css('display', 'none')
                } else {
                    $(this).css('display', 'block')
                }
            });
            //
            depend.change(function () {
                if (typeof json.value != 'object') {
                    json.value = [json.value];
                }
                if ($.inArray($(this).val(), json.value) != -1 && $(this).closest('.vc_column').css('display') == 'block') {
                    el.css('display', 'block');
                } else {
                    el.css('display', 'none');
                }
                el.find('select,input').trigger('change');
                tabs.each(function () {
                    var id = $(this).attr('aria-controls');
                    var result = false;
                    var element = document.getElementById(id);
                    $(element).find('>.vc_column').each(function () {
                        if ($(this).css('display') == 'block') {
                            result = true;
                            return false;
                        }
                    });
                    if (!result) {
                        $(this).css('display', 'none')
                    } else {
                        $(this).css('display', 'block')
                    }
                });
            });

        } else {

            if (typeof json.value != 'object') {
                json.value = [json.value];
            }
            if ($.inArray(depend.val(), json.value) != -1 && depend.closest('.vc_column').css('display') == 'block') {
                el.css('display', 'block');
            } else {
                el.css('display', 'none');
            }
            el.find('select,input').trigger('change');
            tabs.each(function () {
                var id = $(this).attr('aria-controls');
                var result = false;
                var element = document.getElementById(id);
                $(element).find('>.vc_column').each(function () {
                    if ($(this).css('display') == 'block') {
                        result = true;
                        return false;
                    }
                });
                if (!result) {
                    $(this).css('display', 'none')
                } else {
                    $(this).css('display', 'block')
                }
            });

            depend.siblings('[data-name=' + depend.attr('name') + ']').change(function () {
                setTimeout(function(){
                    if (typeof json.value != 'object') {
                        json.value = [json.value];
                    }
                    if ($.inArray(depend.val(), json.value) != -1 && depend.closest('.vc_column').css('display') == 'block') {
                        el.css('display', 'block');
                    } else {
                        el.css('display', 'none');
                    }
                    el.find('select,input').trigger('change');
                    tabs.each(function () {
                        var id = $(this).attr('aria-controls');
                        var result = false;
                        var element = document.getElementById(id);
                        $(element).find('>.vc_column').each(function () {
                            if ($(this).css('display') == 'block') {
                                result = true;
                                return false;
                            }
                        });
                        if (!result) {
                            $(this).css('display', 'none')
                        } else {
                            $(this).css('display', 'block')
                        }
                    });
                },1);

            });
        }
    });
    setTimeout(function(){
        $('.mbuilder-spinner').remove();
        $('.waitforload').css('display' , 'block');
    } , 50 );
};



/**
 * @summary media panel for the image controller in the shortcode setting panel
 *
 * @since 1.0.0
 */

mBuilder.prototype.mediaPanel = function () {
    // Set all variables to be used in scope
    var frame;

    // ADD IMAGE LINK
    $('body').on('click', '.mBuilder-upload-img.single', function (event) {

        event.preventDefault();
        $(this).addClass('active-upload');
        // If the media frame already exists, reopen it.
        if (frame) {
            frame.open();
            return;
        }

        // Create a new media frame
        frame = window.top.wp.media({
            title: 'Select or Upload Media Of Your Chosen Persuasion',
            button: {
                text: 'Use this media'
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });

        var t = this;
        // When an image is selected in the media frame...
        frame.on('select', function () {
            var $this = $('.mBuilder-upload-img.single.active-upload');
            // Get media attachment details from the frame state
            var attachment = frame.state().get('selection').first().toJSON();

            // Send the attachment URL to our custom image input field.
            $this.css('background-image', 'url("' + attachment.url + '")').css('background-size', 'contain');

            // Send the attachment id to our hidden input
            $this.find('input').val(attachment.id);

            $this.find('.remove-img').removeClass('mBuilder-hidden');
            $('.mBuilder-upload-img.single').removeClass('active-upload');

        });

        // Finally, open the modal on click
        frame.open();
    });

    // DELETE IMAGE LINK
    $('body').on('click', '.mBuilder-upload-img.single .remove-img', function (event) {

        event.preventDefault();
        event.stopPropagation();
        // Clear out the preview image

        $(this).closest('.mBuilder-upload-img').css({'background-image': '', 'background-size': ''});

        $(this).parent().removeClass('has-img');
        $(this).addClass('mBuilder-hidden');

        // Delete the image id from the hidden input
        $(this).siblings('input').val('');

    });
};

/**
 * @summary set parents for models and delete extra models
 *
 * @since 1.0.0
 */
mBuilder.prototype.set_parents = function () {
    var t = this;
    for (var index in t.models.models) {
        var $el = $('div[data-mBuilder-id=' + index + ']'),
            $parent = $el.parent().closest('.mBuilder-element');
        if (!$el.length) {
            delete(t.models.models[index]);
        }
        if ($parent.length) {
            var parentId = $parent.attr('data-mBuilder-id');
            t.models.models[index].parentId = parentId;
        }
    }
};

/**
 * @summary multi media panel for the multi image controller in the shortcode setting panel
 *
 * @since 1.0.0
 */

mBuilder.prototype.multiMediaPanel = function () {
    // Set all variables to be used in scope
    var frame;

    // ADD IMAGE LINK
    $('body').on('click', '.mBuilder-upload-imgs .mBuilder-upload-img', function (event) {

        event.preventDefault();

        // If the media frame already exists, reopen it.
        /* if (false) {
         frame.open();
         return;
         }*/

        // Create a new media frame
        frame = window.top.wp.media({
            title: 'Select or Upload Media Of Your Chosen Persuasion',
            button: {
                text: 'Use this media'
            },
            multiple: 'add'  // Set to true to allow multiple files to be selected
        });

        var t = this,
            $container = $(t).parent();
        // When an image is selected in the media frame...
        frame.on('select', function () {

            // Get media attachment details from the frame state
            var attachment = frame.state().get('selection').toJSON();
            var attachments = '';
            $container.find('.mBuilder-upload-img').remove();
            for (var i = 0; i < attachment.length; i++) {
                if(attachment[i]['id'] == ''){
                    continue;
                }
                attachments = attachments + attachment[i]['id'] + ',';
                if (attachment[i]['id'] != "" )
                    $container.append('<div data-id="' + attachment[i]['id'] + '" class="mBuilder-upload-img has-img" style="background-image: url(' + attachment[i].url + ')"><span class="remove-img">X</span></div>');
            }
            $container.append('<div class="mBuilder-upload-img"><span class="remove-img mBuilder-hidden">X</span></div>');
            attachments = attachments.slice(0, -1);
            // Send the attachment id to our hidden input
            $container.find('input').val(attachments);
        });

        // Finally, open the modal on click
        frame.on('open',function() {
            var selection = frame.state().get('selection');
            var ids = $container.find('input').val().split(',');
            ids.forEach(function(id) {
               var attachment = wp.media.attachment(id);
                attachment.fetch();
                selection.add( attachment ? [ attachment ] : [] );
            });
        });

        frame.open();
    });




    // DELETE IMAGE LINK
    $('body').on('click', '.mBuilder-upload-imgs .mBuilder-upload-img .remove-img', function (event) {
        var t = this,
            $container = $(t).parent().parent(),
            $this = $(this).parent();
        event.preventDefault();
        event.stopPropagation();
        // Delete the image id from the hidden input
        var val = $container.find('input').val(),
            valarr = val.split(","),
            index = valarr.indexOf($this.attr('data-id'));
        if (index > -1) {
            valarr.splice(index, 1);
        }
        $container.find('input').val(valarr.join());
        // Clear out the preview image
        $this.remove();
    });
};


/**
 * @summary Google Font Controller in the Shortcode setting panel
 *
 * @since 1.0.0
 */

mBuilder.prototype.googleFontPanel = function () {
    function generateInputVal(paramName) {
        var $fontFamily = $('.google-fonts-families[data-input="' + paramName + '"]'),
            $fontStyle = $('.google-fonts-styles[data-input="' + paramName + '"]'),
            $input = $('input[name="' + paramName + '"]'),
            fontFamily = 'font_family:' + encodeURIComponent($fontFamily.val()),
            fontStyle = 'font_style:' + encodeURIComponent($fontStyle.val());
        $input.val(fontFamily + '|' + fontStyle);
    }

    $('body').on('change', '.google-fonts-families', function (event) {
        // check if event  doesn't call from jquery
        if (!event.originalEvent) {
            return;
        }
        var $this = $(this);
        $('.google-fonts-styles[data-input="' + $this.attr("data-input") + '"]').html('<option>Loading...</option>');
        $.ajax({
            type: 'post',
            url: mBuilderValues.ajax_url,
            data: {
                action: 'pixflow_loadFontStyles',
                nonce: mBuilderValues.ajax_nonce,
                fontKey: $this.find(":selected").attr('data-font-id'),
                value: '',
                mbuilder_editor: true
            },
            success: function (response) {
                $('.google-fonts-styles[data-input="' + $this.attr("data-input") + '"]').html(response);
                generateInputVal($this.attr('data-input'));
            }
        });
    });
    $('body').on('change', '.google-fonts-styles', function (event) {
        generateInputVal($(this).attr('data-input'));
    });
};


/**
 * @summary call user functions that sets to call after each shortcode build or rebuild
 *
 * @param {string} type - shortcode type
 * @param {object} obj - jQuery object of shortcode
 * @since 1.0.0
 */

mBuilder.prototype.specialShortcodes = function (type, obj) {
    if (typeof this[type + "Shortcode"] == 'function') {
        this[type + "Shortcode"](obj);
    }
    obj.parents('.mBuilder-element[data-mbuilder-el="md_accordion_tab"]').find('h3.ui-state-active').siblings('.wpb_accordion_content').css('height', '');
    obj.parents('.mBuilder-element[data-mbuilder-el="md_toggle_tab"]').find('h3.ui-state-active').siblings('.wpb_toggle_content').css('height', '');
    obj.parents('.mBuilder-element[data-mbuilder-el="md_toggle_tab2"]').find('h3.ui-state-active').siblings('.wpb_toggle_content').css('height', '');
};

mBuilder.prototype.md_portfolio_multisizeShortcode = function (obj) {
    'use strict';

    var $portfolioItem = $('.portfolio-multisize .isotope .item'),
        panelSetting = '<div class="portfolio-panel-setting">' +
            '                   <div class="tooltip">SET IMAGE SIZE</div>'+
            '                   <div class="setting-holder">' +
            '                       <div class="state"></div>'+
            '                       <span data-size="thumbnail-small" class="small-size portfolio-size"></span>' +
            '                       <span data-size="thumbnail-medium" class="average-size portfolio-size"></span>' +
            '                       <span data-size="thumbnail-large" class="large-size portfolio-size"></span>' +
            '                   </div>' +
            '               </div>';

    $portfolioItem.append(panelSetting);
    $portfolioItem.find('.portfolio-size').each(function (index, value) {
        $(this).attr('data-item_id', $(this).parent().parent().attr('data-item_id'));
    });

    $portfolioItem.hover(function () {
        var $this =$(this),
            position = parseInt($this.css('padding-top'))+10;

        $this.find('.portfolio-panel-setting').css({top: position,right:position, opacity: '1'});
    }, function () {
        var $this =$(this),
            position = parseInt($this.css('padding-top'));

        $(this).find('.portfolio-panel-setting').css({top: position,right:position+10, opacity: '0'});

    });


    $portfolioItem.find('.portfolio-panel-setting').hover(function(){
        $(this).addClass('hovering');
        TweenMax.fromTo($(this).find('.tooltip'),.3, {top:-60,opacity:0}, {top:-33,opacity:1});
    },function(){
        $(this).removeClass('hovering');
        TweenMax.fromTo($(this).find('.tooltip'),.3, {top:-33,opacity:1}, {top:-70,opacity:0});
    });

    $portfolioItem.find('.portfolio-panel-setting span').click(function () {
        if ($(this).hasClass('small-size')) {
            $(this).parents('.item').removeClass('thumbnail-medium thumbnail-large').addClass('thumbnail-small');
            $(this).siblings('.state').removeClass('active-average active-large').addClass('active-small');
            $(this).siblings().removeClass('current');
            $(this).addClass('current');
            pixflow_portfolioMultisize();
        } else if ($(this).hasClass('average-size')) {
            $(this).parents('.item').removeClass('thumbnail-small thumbnail-large').addClass('thumbnail-medium');
            $(this).siblings().removeClass('current');
            $(this).siblings('.state').removeClass('active-large active-small').addClass('active-average');
            $(this).addClass('current');
            pixflow_portfolioMultisize();
        } else if ($(this).hasClass('large-size')) {
            $(this).parents('.item').removeClass('thumbnail-medium thumbnail-small').addClass('thumbnail-large');
            $(this).siblings().removeClass('current');
            $(this).addClass('current');
            $(this).siblings('.state').removeClass('active-average active-small').addClass('active-large');
            pixflow_portfolioMultisize();
        } else if ($(this).hasClass('setting')) {
            $(this).closest('.vc_md_portfolio_multisize').find('a[title="Edit Portfolio Multi-Size"]')[0].click();
        }
        var item = $(this).parents('.portfolio-item'),
            post_id = item.data("item_id"),
            size = $(this).attr('data-size');
        jQuery.ajax({
            type: "post",
            url: mBuilderValues.ajax_url,
            data: "action=pixflow_portfolio_size&nonce=" + mBuilderValues.ajax_nonce + "&portfolio_size=" + size + "&post_id=" + post_id,
            success: function (res) {
                return res;
            }
        })
    });

    $portfolioItem.find('.portfolio-panel-setting span').hover(function(){
        var $item = $(this);

        if($item.hasClass('small-size')){
            $item.siblings('.state').removeClass('average large').addClass('small');

        }else if($item.hasClass('average-size')){
            $item.siblings('.state').removeClass('large small').addClass('average');

        }else{
            $item.siblings('.state').removeClass('average small').addClass('large');

        }
    },function(){
        var $item = $(this);
        if($item.hasClass('small-size')){
            $item.siblings('.state').removeClass('average large').addClass('small');

        }else if($item.hasClass('average-size')){
            $item.siblings('.state').removeClass('large small').addClass('average');

        }else{
            $item.siblings('.state').removeClass('average small').addClass('large');

        }

        $('.state').removeClass('average small large');

    });

    $('.portfolio .shortcode-btn a').click(function(e){
        e.preventDefault();
        return;
    })
}


mBuilder.prototype.md_tabsShortcode = function (obj) {

    obj.find('.px_tabs_nav > li').click(function () {
        var id = $(this).find('> a').attr('href'),
            num = $(this).position().left;
        $(id).next().css({left: num});
    });

    obj.find('ul.ui-tabs-nav').sortable({
        cursor: "move",
        items: "li:not(.unsortable)",
        delay: 100,
        axis: "x",
        zIndex: 10000,
        tolerance: "intersect",
        update: function (event, ui) {
            $('body').addClass('changed');
            var prev = ui.item.prev();
            var prevId = prev.find('a').attr('href');
            var id = ui.item.find('a').attr('href');
            if (prevId) {
                $(id).parent().insertAfter($(prevId).parent());
            } else {
                $(id).parent().insertAfter($(id).parent().parent().find('ul').first());
            }
        }
    });
}

mBuilder.prototype.md_modernTabsShortcode = function (obj) {
    obj.find('.px_tabs_nav > li').click(function () {
        var id = $(this).find('> a').attr('href'),
            num = $(this).position().left;
        $(id).next().css({left: num});
    });

    setTimeout(function () {
        obj.find('.px_tabs_nav > li').first().click();
    }, 500);

    obj.find('ul.ui-tabs-nav').sortable({
        cursor: "move",
        items: "li:not(.unsortable)",
        delay: 100,
        axis: "x",
        zIndex: 10000,
        tolerance: "intersect",
        update: function (event, ui) {
            $('body').addClass('changed');
            var prev = ui.item.prev();
            var prevId = prev.find('a').attr('href');
            var id = ui.item.find('a').attr('href');
            if (prevId) {
                $(id).parent().insertAfter($(prevId).parent());
            } else {
                $(id).parent().insertAfter($(id).parent().parent().find('ul').first());
            }
        }
    })
}

mBuilder.prototype.md_hor_tabsShortcode = function (obj) {
    obj.find('.px_tabs_nav > li').click(function () {
        var id = $(this).find('> a').attr('href'),
            num = $(this).position().top + 15;
        $(id).next().css({top: num});
    });
    obj.find('ul.ui-tabs-nav').sortable({
        cursor: "move",
        items: "li:not(.unsortable)",
        delay: 100,
        axis: "y",
        zIndex: 10000,
        tolerance: "intersect",
        update: function (event, ui) {
            $('body').addClass('changed');
            var prev = ui.item.prev();
            var prevId = prev.find('a').attr('href');
            var id = ui.item.find('a').attr('href');
            if (prevId) {
                $(id).parent().insertAfter($(prevId).parent());
            } else {
                $(id).parent().insertAfter($(id).parent().parent().find('ul').first());
            }
        }
    })
}

mBuilder.prototype.md_hor_tabs2Shortcode = function (obj) {
    obj.find('.px_tabs_nav > li').click(function () {
        var id = $(this).find('> a').attr('href'),
            num = $(this).position().top + 20;
        $(id).next().css({top: num});
    });

    obj.find('ul.ui-tabs-nav').sortable({
        cursor: "move",
        items: "li:not(.unsortable)",
        delay: 100,
        axis: "y",
        zIndex: 10000,
        tolerance: "intersect",
        update: function (event, ui) {
            $('body').addClass('changed');
            var prev = ui.item.prev();
            var prevId = prev.find('a').attr('href');
            var id = ui.item.find('a').attr('href');
            if (prevId) {
                $(id).parent().insertAfter($(prevId).parent());
            } else {
                $(id).parent().insertAfter($(id).parent().parent().find('ul').first());
            }
        }
    })
};

/**
 * @summary get value attribite from model attributes
 *
 * @since 1.0.0
 */

mBuilder.prototype.getModelattr = function (modelID, attr) {
    var t = this,
        attrs = t.models.models[modelID].attr;
    var re = new RegExp(attr + '="([.\\s\\S]*?[^\\\\])"', 'gm');
    var str = attrs;
    var m;
    if ((m = re.exec(str)) !== null) {
        return m[1];
    } else {
        return false;
    }
};

mBuilder.prototype.removeColumnSeparator = function () {
    var $coulmnMode = $('.column-hover-mode');
    if ($coulmnMode.length) {
        $coulmnMode.remove();
    }
};
mBuilder.prototype.createColumnSeparator = function (selector) {
    var t = this;
    t.removeColumnSeparator();
    var $height = 0 , $coulmnCount = 0;
    if (selector == 'all') {
        $('.vc_row').each(function () {
            var $maxHeight = 0,
                top =  $(this).find('> .wrap').position().top,
                left =  $(this).find('> .wrap').offset().left,
                currentColTop = 0,
                selector = $(this).find('.vc_column-inner');

            $coulmnCount = selector.length;
            if ($coulmnCount !== 1 && !($(this).find('.vc_row').hasClass('vc_inner'))) {
                $height = parseInt($(this).height()) + parseInt($(this).css('padding-top')) + parseInt($(this).css('padding-bottom'));
                selector.first().prepend('<div class="left column-hover-mode" style="left:0px;height: ' + $height + 'px;top:-' + top + 'px"></div>');
                selector.prepend('<div class="right column-hover-mode" style="height: ' + $height + 'px;top:-' + top + 'px"></div>');
            }

            if ($(this).hasClass('row-content-middle')||$(this).hasClass('row-content-bottom')){
                $('.column-hover-mode').each(function () {
                    currentColTop = parseInt(top) + $(this).closest('.mBuilder-element').position().top;
                    $(this).css({top:-currentColTop});
                })
            }
            
            $(this).find('.mbuilder-column-options').each(function () {
                var $this = $(this);
                var content_left = $('main > #content').offset().left;
                var elemTop = $this.closest('.mBuilder-element').position().top;
                var elemLeft = ($this.closest('.vc_column_container').hasClass('col-sm-12'))?left-11:11;
                currentColTop = (elemTop > 0)?elemTop+top-11:top-11;
                //be sure that column gizmo is in right place in boxed demos
                elemLeft += content_left;
                // -11 is for avoiding gizmo to stick to the top of row
                $(this).css({top:-currentColTop,left:-elemLeft});
            })
        });
    }
    else {
        var $selRow = $('#' + selector);
        selector = $('#' + selector).find('.vc_column-inner');
        $coulmnCount = selector.length;
        var top =  $selRow.find('> .wrap').position().top,
            left =  $selRow.find('> .wrap').offset().left,
            currentColTop = 0;

        if ($coulmnCount !== 1  && !($selRow.find('.vc_row').hasClass('vc_inner')) ) {
            $height = parseInt($selRow.height()) + parseInt($selRow.css('padding-top')) + parseInt($selRow.css('padding-bottom'));
            selector.prepend('<div class="column-hover-mode" style="height: ' + $height + 'px;top:-' + top + 'px"></div>');
            selector.first().prepend('<div class="column-hover-mode" style="left:0px;height: ' + $height + 'px;top:-' + top + 'px"></div>');
            //selector.eq($coulmnCount - 1).find('.column-hover-mode').remove();
        }
        if ($selRow.hasClass('row-content-middle')||$selRow.hasClass('row-content-bottom')){
            $('.column-hover-mode').each(function () {
                currentColTop = parseInt(top) + $(this).closest('.mBuilder-element').position().top;
                $(this).css({top:-currentColTop});
            })
        }

        var content_left = $('main > #content').offset().left;
        $selRow.find('> .wrap > .mBuilder-vc_column > .vc_column_container > .mbuilder-column-options').each(function () {
            var elemTop = $(this).closest('.mBuilder-element').position().top;
            var elemLeft = ($(this).closest('.vc_column_container').hasClass('col-sm-12'))?-(left-11):11;

            //be sure that column gizmo is in right place in boxed demos
            elemLeft += content_left;
            currentColTop = (elemTop > 0)?elemTop+top:top;
            currentColTop -= 11;
            $(this).css({top:-currentColTop,left:elemLeft});
        })
    }
}


// Create A list Of Fonts with Their Varients
var $fontListWithVar = [] ;
var $fontVarMenu = [];
mBuilder.prototype.getEditorFonts = function () {
    var t = this,
        $fontList ,
        count = 0 ,
        $fontName = '' ,
        google_font_url = mBuilderValues.google_font;
    $.get( google_font_url ,  function (data) {
        $fontList =  JSON.parse(data) ;
        for( count  in $fontList.items ){
            $fontVarMenu = [];
            for(var i=0 ; i < $fontList.items[count].variants.length ; i++){
                var e = {
                    text:$fontList.items[count].variants[i],
                    classes:'pixflow-editor-font'
                }
                $fontVarMenu.push(e);
            }
            var s = {text: $fontList.items[count].family.toString() , menu: $fontVarMenu }
            $fontListWithVar.push(s);
        }
        t.fontName =  $fontListWithVar  ;
        return ;
    });
}

// Create A list of Editor Font Sizes
mBuilder.prototype.makeTinymceString = function () {
    var $fontString = '',
        count;
    for (count = 1; count <= 100; count++) {
        $fontString += count + 'px ';
    }
    return $fontString.trim();
};


// Convert String to base64
mBuilder.prototype.b64EncodeUnicode = function (str) {
    return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g, function (match, p1) {
        return String.fromCharCode('0x' + p1);
    }));
};


// Decode The base64 String to Text
mBuilder.prototype.b64DecodeUnicode = function (str) {
    return decodeURIComponent(Array.prototype.map.call(atob(str), function (c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));
};


// Get Selected Html Of Selected Text
mBuilder.prototype.get_selection_html = function() {
        var html = "";
        if (typeof window.getSelection != "undefined") {
            var sel = window.getSelection();
            if (sel.rangeCount) {
                var container = document.createElement("div");
                for (var i = 0, len = sel.rangeCount; i < len; ++i) {
                    container.appendChild(sel.getRangeAt(i).cloneContents());
                }
                html = container.innerHTML;
            }
        } else if (typeof document.selection != "undefined") {
            if (document.selection.type == "Text") {
                html = document.selection.createRange().htmlText;
            }
        }
        if( /^((?!chrome).)*safari/i.test(navigator.userAgent) == true ){
                html = tinyMCE.activeEditor.selection.getContent();
        }
        // Return String 
        return html;
};

/*
 * Remove tinymce object form dom when it dose not need
 * @return
 */
mBuilder.prototype.remove_tinymce = function(){
    setTimeout(function(){
        for (var i = tinymce.editors.length - 1 ; i > -1 ; i--) {
            var ed_id = tinymce.editors[i].id;
            tinyMCE.execCommand("mceRemoveEditor", true, ed_id);
        }
        $('.pixFlow-selected').removeAttr('id').removeAttr('contenteditable');
        $('[class^="mce-"]').remove();
        $('.text-selected').removeClass('text-selected');
        $('.pixFlow-selected').removeClass('pixFlow-selected mce-content-body');
        $('.inline-editor-title , .inline-editor').removeClass('text-selected mce-content-body pixFlow-selected do-save-text mce-edit-focus do-save');
    } , 10);
    return ;
}

// Call the editor For Title of Text Shhortcode
mBuilder.prototype.calltinymcetitle = function (classname) {
    var t = this;
    if (classname == null) {
        classname = '.inline-editor-title';
    }
    tinymce.init({
        selector: classname,
        convert_urls: false,
        theme_advanced_toolbar_location: "top",
        forced_root_block: 'div',
        force_p_newlines: false,
        theme_advanced_resizing: false,
        theme_advanced_resizing_use_cookie: false,
        force_br_newlines: false,
        toolbar_items_size: 'small',
        tabfocus_elements: ":next",
        block_formats: 'Header 1=h1;Header 2=h2;Header 3=h3;Header 4=h4;Header 5=h5;Header 6=h6',
        inline: true,
        setup: function (editor) {
            editor.on('focus', function () {
                $('#' + editor.id).addClass('pixFlow-selected');
                setTimeout(function(){
                    $('.mce-tinymce').each(function () {
                        $btnColor = $(this).find('.mce-btn-group').eq(3).find('.mce-txt');
                        if ($(this).find('.mce-btn-group:nth-child(1)').find('.mce-txt').text() == 'Font Sizes')
                            $(this).find('.mce-btn-group:nth-child(1)').find('.mce-txt').text('32px');
                        if ($(this).find('.mce-btn-group:nth-child(3)').find('.mce-txt').text() == 'Font Family')
                            $(this).find('.mce-btn-group:nth-child(3)').find('.mce-txt').text('Roboto');
                        if ($('#' + editor.id).parents('.ui-draggable').hasClass('no-text')) {
                            $btnColor.attr('style', 'color:black !important');
                        }
                        else {
                            $btnColor.attr('style', 'color: #d8d2d2 ');
                        }
                    });
                }, 100);
            });
            editor.on('blur', function () {
                var $doSave = $('.do-save');
                if ($doSave.length) {
                    var id = $doSave.closest('.ui-draggable').data('mbuilder-id');
                    if ($doSave.text().trim() == '') {
                        var $newTitle = '';
                        $doSave.css('display', 'none');
                        $('div[data-mbuilder-id=' + id + ']').addClass('no-title');
                        if ($('#' + editor.id).closest('.md-text').find('.inline-editor').text().trim() == "") {
                            $('.mBuilder-md_text.no-title .md-text-title').css('display', 'block');
                            $('#' + editor.id).closest('.md-text').find('.inline-editor-title').html('<div class="defulttext" >Add title</div>');
                        }
                    } else {
                        var $newTitle = $doSave.html();
                        $newTitle = 'pixflow_base64' + t.b64EncodeUnicode($newTitle);
                        $('div[data-mbuilder-id=' + id + ']').removeClass('no-title');
                    }
                    $('.inline-editor-title').removeClass('do-save');
                    t.setModelattr(id, 'md_text_title1', $newTitle);
                    // set it to compatible with VC backend editor
                    t.setModelattr(id, 'md_text_use_title_slider', 'yes');
                    document.getSelection().removeAllRanges();
                }
            });
            editor.addButton('mybutton', {
                text: '',
                icon: 'fullpage',
                tooltip: 'More Option',
                onclick: function (e) {
                    var $doSave = $('.do-save');
                    $doSave.blur();
                    $('#' + editor.id).blur();
                    setTimeout(function(){$doSave.closest('.gizmo-container').find(' > .mBuilder_controls .sc-setting').click();},1);
                }
            });
            editor.addButton('fontfamily' , {
                text: 'Font Family' ,
                icon: false ,
                type: 'menubutton',
                menu: t.fontName
            });
            editor.addButton('Add', {
                text: 'Add Content',
                icon: false,
                onclick: function (e) {
                    $editorSelector = $('#' + editor.id).closest('.md-text');
                    $('.do-save').blur();
                    if ($editorSelector.find('.inline-editor-title').text().trim() == '') {
                        $('.mBuilder-md_text.no-title .md-text-title').css('display', 'none');
                    }
                    $('#' + editor.id).closest('.no-text').removeClass('no-text');
                    $editorSelector.find('.without-content').removeClass('without-content');
                    if ($editorSelector.find('.inline-editor').text().trim() == '') {
                        $editorSelector.find('.inline-editor').html('<div class="defulttext" >Add description</div>');
                    }
                }
            });
        },
        fontsize_formats: t.tinymceString,
        font_formats : t.fontName ,
        toolbar: 'fontsizeselect | forecolor | fontfamily | Add | styleselect | link | undo redo | mybutton',
        menubar: false,
        paste_as_text: true ,
        plugins: 'textcolor link tabfocus textcolor colorpicker paste'
    });

};

// Call The Editor For content Of Text Shortcode
mBuilder.prototype.calltinymcecontent = function (classname) {
    var t = this;
    if (classname == null) {
        classname = '.inline-editor';
    }
    tinymce.init({
        selector: classname,
        convert_urls: false,
        theme_advanced_toolbar_location: "top",
        forced_root_block: 'p',
        force_p_newlines: false,
        theme_advanced_resizing: false,
        theme_advanced_resizing_use_cookie: false,
        force_br_newlines: false,
        tabfocus_elements: ":next",
        toolbar_items_size: 'small',
        menubar: false,
        block_formats: 'Header 1=h1;Header 2=h2;Header 3=h3;Header 4=h4;Header 5=h5;Header 6=h6',
        inline: true,
        setup: function (editor) {
            editor.on('focus', function (e) {
                $('#' + editor.id).addClass('pixFlow-selected');
                e.stopPropagation();
                $('#' + editor.id).addClass('do-save-text');
                setTimeout(function () {
                    $('.mce-tinymce').each(function () {
                        $thisSel = $(this).find('.mce-btn-group').eq(3).find('.mce-txt');
                        if ($(this).find('.mce-btn-group:nth-child(1)').find('.mce-txt').text() == 'Font Sizes')
                            $(this).find('.mce-btn-group:nth-child(1)').find('.mce-txt').text('14px');
                        if ($(this).find('.mce-btn-group:nth-child(3)').find('.mce-txt').text() == 'Font Family')
                            $(this).find('.mce-btn-group:nth-child(3)').find('.mce-txt').text('Roboto');
                        if ($('#' + editor.id).parents('.ui-draggable').hasClass('no-title')) {
                            $thisSel.attr('style', 'color: black !important');
                        }
                        else {
                            $thisSel.attr('style', 'color: #d8d2d2');
                        }
                    });
                }, 100);
            });
            editor.on('blur', function (e) {
                var $doSaveText = $('.do-save-text');
                if ($doSaveText.length) {
                    var id = $doSaveText.parents('.ui-draggable').data('mbuilder-id');
                    if ($doSaveText.text().trim() == '') {
                        var $newContent = '';
                        $('div[data-mbuilder-id=' + id + ']').addClass('no-text');
                        if ($('#' + editor.id).closest('.md-text').find('.inline-editor-title').text().trim() == "") {
                            $('.mBuilder-md_text.no-title .md-text-title').css('display', 'block');
                            $('#' + editor.id).closest('.md-text').find('.inline-editor-title').html('<div class="defulttext" >Add title</div>');
                        }
                    } else {
                        var $newContent = $doSaveText.html();
                        $('div[data-mbuilder-id=' + id + ']').removeClass('no-text');
                    }
                    t.models.models[id].content = $newContent;
                    $doSaveText.removeClass('do-save-text');
                }
                document.getSelection().removeAllRanges();
            });
            editor.addButton('mybutton', {
                text: '',
                icon: 'fullpage',
                tooltip: 'More Option',
                onclick: function () {
                    $('.do-save-text').blur();
                    $('#' + editor.id).blur();
                    $('.do-save-text').closest('.gizmo-container').find(' > .mBuilder_controls .sc-setting').click();
                }
            });
            editor.addButton('fontfamily' , {
                text: 'Font Family' ,
                icon: false ,
                type: 'menubutton',
                menu: t.fontName
            });
            editor.addButton('Add', {
                text: 'Add Title',
                icon: false,
                onclick: function () {
                    var $editorSel = $('#' + editor.id).closest('.md-text');
                    $('.do-save-text').blur();
                    $('#' + editor.id).closest('.no-title').removeClass('no-title');
                    $('.mBuilder-md_text .md-text-title').css('display', 'block');
                    $('#' + editor.id).closest('.md-text').find('.without-title').removeClass('without-title');
                    if ($editorSel.find('.inline-editor-title').text().trim() == '') {
                        $editorSel.find('.inline-editor-title').html('<div class="defulttext" >Add title</div>');
                    }
                }
            });
        },
        fontsize_formats: t.tinymceString,

        font_formats : t.fontName ,
        toolbar: 'fontsizeselect | forecolor | fontfamily | Add | styleselect | bullist numlist | link image | undo redo | mybutton',
        paste_as_text: true ,
        plugins: 'textcolor link tabfocus image textcolor colorpicker paste',
    });

};


/**
 * @summary set value to model attribute
 *
 * @since 1.0.0
 */

mBuilder.prototype.setModelattr = function (modelID, attr, value) {
    var t = this,
        attrs = t.models.models[modelID].attr;
    var re = new RegExp(attr + '="([.\\s\\S]*?)[^\\\\]"', 'gm');
    var str = attrs;
    var m;
    if ((m = re.exec(str)) !== null) {
        var find = new RegExp(attr + '="([.\\s\\S]*?)[^\\\\]"', 'gm');
        var replace = attr + '="' + value + '" ';
        attrs = str.replace(find, replace);
    } else {
        attrs = attrs + ' ' + attr + '="' + value + '" ';
    }

    t.models.models[modelID].attr = attrs;
};

mBuilder.prototype.make_links_target_self = function () {

    $('body').on('click','a:not(.pixflow-builder-toolbar a)',function(e){
        $(this).attr('target','_balnk')
    });
};

/*
* Preview button functionality in pixflow builder toolbar
* */
mBuilder.prototype.preview_mode = function () {
    "use strict";
    var $preview_button = $('.builder-preview');
    if (!$preview_button.length)
        return;

    function preview_off($this,$body){
        if($body.length){
            //$('.mBuilder_row_controls').css('display','flex');
            $( ".mBuilder-vc_column .vc_column-inner > .wpb_wrapper" ).removeClass("disable-sort");
            $( ".content-container" ).removeClass("disable-sort");
            $('.inline-editor-title , .inline-editor').attr('contenteditable' , 'true' );
            $('.disable-edit , .disable-edit-title').remove();
        }
        try {
            $(".px_tabs_nav").sortable("enable");
            $('.mBuilder-element:not(.vc_row,.mBuilder-vc_column)').draggable("enable");
        }catch(e){}

        $body.removeClass('gizmo-off');
        $('.pixflow-shortcodes-panel').css({left:''});

        try{
            $this.removeClass('hold-collapse');
            $('.wpb_content_element .px_tabs_nav.md-custom-tab > li:last-child').css('display','inline-block');
            $('.sortable-handle').css('border','1px dashed rgba(92,92,92,.9)');
        }catch (e){}
    }
    function preview_on($this,$body){
        if($body.length){
            $( ".mBuilder-vc_column .vc_column-inner > .wpb_wrapper" ).addClass("disable-sort");
            $( ".content-container" ).addClass("disable-sort");
            $body.addClass('gizmo-off');
            $('.pixflow-shortcodes-panel').css({left:'-310px'});
            try {
                $(".px_tabs_nav").sortable("disable");
                $('.mBuilder-element:not(.vc_row,.mBuilder-vc_column)').draggable("disable");
            }catch(e){}
            $('.inline-editor-title , .inline-editor').removeAttr('contenteditable' );
            if( $('.disable-edit , .disable-edit-title').length){
                $('.disable-edit , .disable-edit-title').css({
                    'z-index' : '100' ,
                    'display' : 'block'
                });
            }
            else {
                $('.md-text-content').prepend('<div class="disable-edit" style="z-index:100" ></div>');
                $('.md-text-title').prepend('<div class="disable-edit-title" style="z-index:100"></div>');
            }
            $(this).addClass('hold-collapse');
        }


        try {
            $this.addClass('hold-collapse');
            $body.off('click','.vc_empty-element,.vc_templates-blank, .vc_add-element-action, .vc_control-btn-append, .vc_element-container');
            $('.vc_row .vc_vc_column,.sortable-handle').css('border','none');
            $('.wpb_content_element .px_tabs_nav.md-custom-tab > li:last-child').css('display','none');
        } catch (e) {}
    }
    $preview_button.unbind('click');
    $preview_button.click(function () {
        var $this = $(this),
            $body = $('body.pixflow-builder');
        $this.toggleClass('active-preview');
        $('.pixflow-add-element-button').removeClass('close-element-button');
        if($this.hasClass('hold-collapse')){
            preview_off($this,$body);
        }else {
            preview_on($this,$body);
        }
    });
};


mBuilder.prototype.save_button_animation_start = function(){
    $('.builder-controls .builder-save').addClass('saving');
    $('.builder-controls .builder-save .save-loading').animate({width: '90%'}, 7000);
    $('.builder-controls .builder-save .save').html(mBuilderValues.saving);

};

mBuilder.prototype.save_button_animation_end = function(){
    $('.builder-controls .builder-save .save-loading').stop().animate({'width': '100%'}, 200, 'swing', function () {
        $('.builder-controls .builder-save .save').html(mBuilderValues.saved);
        $('.builder-controls .builder-save .save-loading').css('width', '0%');
        $('.builder-controls .builder-save').removeClass('active-dropdown-view');

        setTimeout(function () {
            $('.builder-controls .builder-save').removeClass('saving');
            $('.builder-controls .builder-save .save').html(mBuilderValues.save);
        }, 2000);
    });
};

mBuilder.prototype.mBuilder_message_box = function(title, customClass, text, btn1, callback1, btn2, callback2, closeCallback){
    "use strict";
    var t = this;
    $('.message-box-wrapper').remove();
    var $messageBox = $('' +
            '<div class="message-box-wrapper">' +
            '   <div class="message-box-container '+ customClass +'">' +
            '       <div class="message-box-close"/>' +
            '       <div class="message-box-title">' + title + '</div>' +
            '       <div class="message-box-text">' + text + '</div>' +
            '       <button class="message-box-btn1">'+ btn1 +'</button>' +
            '   </div>' +
            '</div>').appendTo('body'),
        $btn1;

    $messageBox.animate({opacity:1},200);
    $btn1 = $messageBox.find('.message-box-btn1');

    $btn1.click(function(){
        if(typeof callback1 == 'function') {
            callback1();
        }
    });
    if(btn2){
        var $btn2 = $('<button class="message-box-btn2">'+ btn2 +'</button>').insertAfter($btn1);
        $btn2.click(function(){
            if(typeof callback2 == 'function'){
                callback2();
            }
        });
    }
    if(!btn2){
        $btn1.css("width","100%");
    }

    var $close = $messageBox;
    $close.on('click', function(e) {
        if (e.target !== this)
            return;
        if(typeof closeCallback == 'function'){
            closeCallback();
        }
        t.mBuilder_close_message_box();
    });
};

mBuilder.prototype.mBuilder_close_message_box = function(){
    "use strict";

    $('.message-box-wrapper').fadeOut(300,function(){
        $(this).remove();
    })
};

mBuilder.prototype.on_before_unload = function(){
    var t = this;
    var leave_builder_msg = "You're about to leave Massive Builder",
        edit_setting_msg = "You're about to edit setting",
        import_demo_msg = "You're about to import demo";
        $('.builder-dashboard a, .builder-sitesetting a, .builder-customizer a, .builder-close a').click(function(){
        var $that = $(this);
        if($('body').hasClass('changed')) {
            if($that.parent().hasClass('builder-sitesetting')){
                var leave_msg = edit_setting_msg;
            }else if($that.parent().hasClass('builder-customizer')){
                var leave_msg = import_demo_msg;
            }else{
                var leave_msg = leave_builder_msg;
            }
            t.mBuilder_message_box(leave_msg, '', 'Would you mind save your changes before leaving?', 'Update First', function () {
                t.save_callback_function = function () {
                    window.location = $that.attr('href');
                }
                t.saveContent();
            }, 'Just Leave', function () {
                window.location = $that.attr('href');
            }, function () {
                return false;
            });
            return false;
        }
    })
};

var open_shortcode_time = open_shortcode_time_duration =  '' ;
mBuilder.prototype.remove_time_out = function (){
    clearTimeout(open_shortcode_time_duration);
}

mBuilder.prototype.calc_time_out = function(){
    var t = this ;
    open_shortcode_time_duration = setTimeout(function(){
        t.close_pixflow_shortcode_panel();
    } , 5000 );
    return true ;
}

mBuilder.prototype.open_pixflow_shortcode_panel  = function(){
    "use strict";
    clearTimeout(open_shortcode_time);
    clearTimeout(open_shortcode_time_duration);
    var $shortcodes_panel = $(".pixflow-shortcodes-panel");

    $shortcodes_panel.removeClass('hide-panel');
    $(".pixflow-add-element-button").addClass('clicked');
    $shortcodes_panel.addClass("opened");
    $shortcodes_panel.find('.pixflow-search-shortcode').focus();

}

mBuilder.prototype.close_pixflow_shortcode_panel = function(){
    var $shortcodes_panel = $(".pixflow-shortcodes-panel");
    clearTimeout(open_shortcode_time_duration);
    if ($shortcodes_panel.hasClass("opened")) {
        $(".pixflow-add-element-button").removeClass('clicked');
        $shortcodes_panel.removeClass("opened");
        open_shortcode_time = setTimeout(function () {
            $shortcodes_panel.addClass('hide-panel')
        },800);
        this.clear_shortcodes_panel_input();

    }
}

mBuilder.prototype.clear_shortcodes_panel_input = function(){
    $('.pixflow-search-shortcode').val('');
    $('.pixflow-shortcodes-container .category-container').addClass('show').find('.shortcodes').addClass('active');
}
// builder instance
var builder = null;
var $ = jQuery;
$(function () {
    builder = new mBuilder();

    $('[data-mbuilder-el]').each(function () {
        var type = $(this).attr('data-mbuilder-el');
        if (typeof builder[type + "Shortcode"] == 'function') {
            builder[type + 'Shortcode']($(this));
        }
    });
});

$('body').on('mousemove','.vc_row',function (e) {
    var leftArr = [];
    var row_with=$(this).width();

    $(this).find('.mBuilder-vc_column').each(function (index) {
        if ($(this).hasClass('col-sm-12')){
            leftArr[0]=0 ;
            leftArr[1]=row_with ;
        }else {
            leftArr[index] = $(this).offset().left ;
        }
    });
    leftArr[leftArr.length] = leftArr[leftArr.length-1]+$(this).find('.mBuilder-vc_column').last().width();
    for (var  i =0; i<leftArr.length;i++){
        if(e.pageX >= leftArr[i] && e.pageX <leftArr[i+1]){
            $(this).find('.mBuilder-vc_column:eq('+i+') > .vc_column_container > .mbuilder-column-options').addClass('active-column');
        }else{
            $(this).find('.mBuilder-vc_column:eq('+i+') > .vc_column_container > .mbuilder-column-options').removeClass('active-column')
        }
    }
});

$(document).on('mouseenter', '.vc_row', function () {
    var mainPadding = parseInt($('main').css('padding-top')),
        top = mainPadding,
        num = 0,
        firstRow = $('main .vc_row').first(),
        $firstRowControls = firstRow.find('> .wrap + .mBuilder_row_controls'),
        $headerTop = $('header[class *= "top"]'),
        columns = $(this).find('.mbuilder-column-options'),
        headerHeight = $headerTop.height()+3;//50 is header gizmo height

    firstRow.addClass('first-row');

    if (!$(this).closest('.first-row').length ){
        return;
    }

    if($('header').is('.left') || $('header').is('.right')){
        $firstRowControls.css('top', '0');
    }else {

        if (!$(this).hasClass('vc_inner')) {

            if (mainPadding >= headerHeight + 45) {
                //content is not under header now check if row has space to view its settings or not
                if (!$firstRowControls.hasClass('flag')) {
                    var number =  ($(this).hasClass('sloped_row') || $(this).hasClass('row_video')) ? '2px':'-45px';
                    $firstRowControls.css('top', number);
                    $firstRowControls.addClass('flag')
                }
            } else {
                //row has enough space to view its setting
                // check if it is under header or not
                var headerTop = parseInt($headerTop.css('top'));
                headerHeight += headerTop;
                num = (headerHeight + 45 <= mainPadding ) ? - 45 : headerHeight - mainPadding;

                if(num < 0)
                    num = 2;

                if (!$firstRowControls.hasClass('flag')) {
                    $firstRowControls.css({'top': num,'left':'50px'});
                    columns.each(function () {
                        $(this).css({top:num+45+parseInt($(this).css('top'))})
                    })
                    $firstRowControls.addClass('flag')
                }
            }
        } else {
            var $innerRow = $(this),
                $innerRowControls = $innerRow.find('.mBuilder_row_controls'),
                rowTop = $innerRowControls.closest('.first-row').find('> .mBuilder_row_controls').position().top;

            if ($innerRowControls.offset().top <= rowTop){
                $innerRowControls.css({'top':rowTop});
                columns.each(function () {
                    $(this).css({top:rowTop+45+parseInt($(this).css('top'))})
                })

            }else{
                columns.each(function (index) {
                    if (index != 0){
                        $(this).css({top:0});
                    }else{
                        $(this).css({top:'45px'});
                    }
                })

            }
        }
    }
});

$(document).on('mouseleave', '.vc_row ', function () {

    var firstRow = $('main .vc_row').first(),
        firstRowControls = firstRow.find('> .wrap + .mBuilder_row_controls');

    if($(this).hasClass('vc_inner')){
        if (! $(this).closest('.first-row').length){
            return;
        }
    }else if (! $(this).hasClass('first-row')){
        return;
    }
    firstRow.removeClass('first-row');

    if (firstRowControls.hasClass('flag')) {
        firstRowControls.removeClass('flag')
    }
});

$(document).on('click','.pixflow-add-element-button',function(e){
    e.stopPropagation();
    builder.open_pixflow_shortcode_panel();
});

$('.pixflow-shortcodes-panel').on('click' , '.pixflow_close_shortcodes_panel' ,function () {
    builder.close_pixflow_shortcode_panel();
});

window.onbeforeunload = function (e) {
    e = e || window.event;
    if($('body').hasClass('changed')){
        if (e) {
            e.returnValue = 'Sure?';
        }
        // For Safari
        return 'Sure?';
    }
};