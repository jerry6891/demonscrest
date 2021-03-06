
function pixflow_pieChart2($id, barColor, trackColor) {
    'use strict';
    var md_pieChart2_animation = $id.attr("data-animation-type");
    var md_pieChart2_show_type = $id.attr("data-show-type");
    var md_pieChart2_line_width = $id.attr("data-line-width");
    var md_pieChart2_line_width = $id.attr("data-line-width");

    $.easyPieChart.defaultOptions = {
        barColor: barColor,
        trackColor: trackColor,
        lineCap: 'round',
        rotate: 0,
        size: 220,
        lineWidth: 3,
        animate: 1500,
        onStart: $.noop,
        onStop: $.noop,
        onStep: $.noop,
    };

    if (md_pieChart2_show_type != 'yes') {
        var md_pieChart2_title = $id.find('.percentage').attr("data-title");
    }
    else {
        var md_pieChart2_title = $id.find('.percentage').attr("data-percent") + "%";
    }

    if ('yes' == md_pieChart2_show_type) {
        $id.find('.percentage').easyPieChart({
            animate: 1500,
            lineWidth: md_pieChart2_line_width,
            easing: md_pieChart2_animation,
            onStep: function (value) {
                this.$el.find('span').html('<p class="md_pieChart2_title">' + Math.round(value) + '%</p>');
            },
            onStop: function (value, to) {
                this.$el.find('span').html('<p class="md_pieChart2_title">' + Math.round(value) + '%</p>');
            }
        });
    } else {
        $id.find('.percentage').easyPieChart({
            animate: 1500,
            lineWidth: md_pieChart2_line_width,
            easing: md_pieChart2_animation,
            onStep: function (value) {
                this.$el.find('span').html('<p class="md_pieChart2_title">' + md_pieChart2_title + '</p>');
            },
            onStop: function (value, to) {
                this.$el.find('span').html('<p class="md_pieChart2_title">' + md_pieChart2_title + '</p>');
            }
        });
    }
}