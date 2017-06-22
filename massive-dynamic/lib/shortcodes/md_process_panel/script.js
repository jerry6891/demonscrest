function pixflow_process_panel() {
    "use strict";


    $(".process-panel-main").each(function () {


        $(this).parent().closest('.box_size_container').css("cssText", "width : 100% !important");


    });

    $(".process-panel-main-container:not(:first-child)").each(function () {
        var windowWidth = $(window).width();
        if (navigator.userAgent.indexOf('Firefox') > 1 && windowWidth > 1024) {
            $(this).addClass("fix-border");
        }

    });
}
document_ready_functions.pixflow_process_panel = [];