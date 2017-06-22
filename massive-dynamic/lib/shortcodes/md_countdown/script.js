function pixflow_countdown(year, month, day, hour, min) {
    'use strict';

    var $ = jQuery;

    $('.count-down #date-time').countdown(year + '/' + month + '/' + day + ' ' + hour + ':' + min + ':59', function (event) {

        $(this).html(event.strftime(''
            + '<div class="content"> <span>%m</span> <hr /> Months   </div> '
            + '<div class="content"> <span>%n</span> <hr /> Days    </div> '
            + '<div class="content"> <span>%H</span> <hr /> Hours   </div> '
            + '<div class="content"> <span>%M</span> <hr /> Minutes </div> '
            + '<div class="content"> <span>%S</span> <hr /> Seconds  </div> '
        ));

    });

}