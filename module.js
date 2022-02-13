/****************************************************************

 File:       local/course_create/module.js

 Purpose:    Init YUI

 ****************************************************************/
M.course_create = M.course_create || {};

M.course_create.init = function (Y) {

};

M.course_create.toggle = function (Y) {
    var schoolTemplate = $('.create_template');
    var prevCourse = $('.create_prev');
    prevCourse.hide();
    schoolTemplate.hide();
    $('input[type=radio][name=createchoice]').change(function () {

        var clickedOption = this.value;
        if (clickedOption == 2) {
            prevCourse.hide();
            schoolTemplate.hide();
        }
        else if (clickedOption == 1) {
            prevCourse.show();
            schoolTemplate.hide();
        }
        else if (clickedOption == 0) {
            prevCourse.hide();
            schoolTemplate.show();
        }
    });
};

M.course_create.adjustForm = function (Y) {
    var nextId = $('#id_submitbutton');
    nextId.hide();
    $('#id_oneclickbackup').val('Next');

};