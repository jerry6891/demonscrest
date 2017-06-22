function pixflow_teamMemberClassic(teamMemberId, teamMemberClassicHoverColor, rowChanged) {
    "use strict";

    if (rowChanged == 'row_changed') {

        var teamWidthRowChanged = $('.vc_vc_row').find('.team-member-classic').parent().width();

        if ($('.vc_vc_row').hasClass(teamMemberId)) {
            $('.vc_vc_row').find('.team-member-classic .content').css({
                width: teamWidthRowChanged,
                height: teamWidthRowChanged
            });
        }
    }

    var teamMemberWidth = teamMemberId.width();

    teamMemberId.sliphover({
        backgroundColor: teamMemberClassicHoverColor,
        duration: 'transform 0.5s cubic-bezier(0.7, 0.27, 0.33, 0.92);',
        target: '.content',
        caption: 'data-caption'
    });

    teamMemberId.find('.content').css({width: teamMemberWidth, height: teamMemberWidth});

}

// for performance of execution
var teamMemberClassics = {};
function pixflow_teamMemberClassicHover($id, $image_url, $team_member_classic_texts_color) {
    "use strict";

    var $content = $('#' + $id).find('.content'),
        teamMemberWidth;
    // for performance of execution
    if (teamMemberClassics[$id] == undefined) {
        teamMemberClassics[$id] = setTimeout(function () {
            $('#' + $id).find('style[data-name="' + $id + '"]').remove();
            $('#' + $id).append('' +

                '<style  data-name="' + $id + '"> ' + "#" + $id + " .content" + ' { background-image: url(' + $image_url + '); } ' +
                '' + '.' + $id + '-topPos' + ' { position: absolute; top: 0; padding: 25px 50px 0 30px; text-align: left; }' +
                '' + '.' + $id + '-topPos .title' + ' { padding: 0; margin: 0; font-size: 22px; line-height: 22px; margin-bottom:10px; font-weight: 400; color: ' + $team_member_classic_texts_color + '; }' +
                '' + '.' + $id + '-topPos .subtitle' + ' { font-size: 16px; line-height: 16px; color: ' + $team_member_classic_texts_color + '; }' +

                '' + '.' + $id + '-bottomPos' + ' { position: absolute; bottom: 0; padding: 0 50px 30px 30px; width: 100%; }' +
                '' + '.' + $id + '-bottomPos .description' + ' { padding-bottom: 17px; text-align: left; font-size: 14px; font-weight: 400; word-wrap: break-word; color: ' + $team_member_classic_texts_color + '; }' +
                '' + '.' + $id + '-bottomPos ul li' + ' { list-style: none; cursor: pointer; float: left; padding-right: 15px; transition: opacity 0.2s; }' +
                '' + '.' + $id + '-bottomPos ul li:hover' + ' { opacity: 0.6; }' +
                '' + '.' + $id + '-bottomPos ul li a' + ' { color: #fff; color: ' + $team_member_classic_texts_color + '; }' +

                '</style>');
            teamMemberClassics[$id] = undefined;
        }, 1000);
    }
    teamMemberWidth = $('#' + $id).width();
    $('#' + $id).find('.content').css({width: teamMemberWidth, height: teamMemberWidth});
}

function pixflow_teamMemberRecall() {
    "use strict";

    var teamMemberClassic = $('.team-member-classic'),
        teamMemberWidth = 0,
        teamID;

    if (!teamMemberClassic.length)
        return;

    // Reset team member sizes
    $('.team-member-classic').each(function () {
        var teamId = $(this).parent().attr('id'),
            teamIdImg = $('#' + teamId).find('.content').attr('data-image'),
            teamIdColor = $('#' + teamId).find('.content').attr('data-color');

        pixflow_teamMemberClassicHover(teamId, teamIdImg, teamIdColor);
        $('#' + teamId).sliphover({
            backgroundColor: $('#' + teamId).attr('data-bgColor'),
            duration: 'transform 0.5s cubic-bezier(0.7, 0.27, 0.33, 0.92);',
            target: '.content',
            caption: 'data-caption'
        });
        teamMemberWidth = $(this).width();
    });
}
document_ready_functions.pixflow_teamMemberRecall = [];
window_resize_functions.pixflow_teamMemberRecall = [];
