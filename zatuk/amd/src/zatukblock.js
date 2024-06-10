// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Defines zatuk block script.
 *
 * @since      Moodle 2.0
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['jquery',
    'core/str'],
    function($, Str){
    return {
        init: function() {
            $(".dataTable" ).each(function() {

            });
        },
        DataTables: function(container){
                var str = $('#'+container).data('function');
                var pagelenth = $('#'+container).data('pagelength');
                if(pagelenth == undefined){
                  pagelenth = 10;
                }
                var args = {action: str};
                return Str.get_strings([{
                key: $('#'+container).data('nodatastring'),
                  component: 'mod_zatuk',
                  param: M.cfg.sesskey
                }]).then(function() {
                $('#'+container).DataTable({
                    'bInfo' : false,
                    'bLengthChange': false,
                    'pageLength': pagelenth,
                    'processing': true,
                    'serverSide': true,
                    'ajax': {
                        "type": "POST",
                        "dataType": "json",
                        "url": M.cfg.wwwroot + '/lib/ajax/service.php?sesskey=' + M.cfg.sesskey+'&info=mod_zatuk_blocktablecontent',
                        "data": function(d) {
                          var newdata = {};
                          newdata.methodname = 'mod_zatuk_blocktablecontent';
                          newdata.args = {args: JSON.stringify({d,args})};
                          return JSON.stringify([newdata]);
                        },
                        "dataSrc" : function (json) {
                           var data = JSON.parse(json[0].data.data);
                           return data;
                        }
                    },
                    "language": {
                        "search": '',
                        "searchPlaceholder": 'search',
                        "paginate": {
                           "next": '<i class="fa fa-angle-right"></i>',
                           "previous": '<i class="fa fa-angle-left"></i>'
                        }
                    }
                });
            });
        },
    };
});
