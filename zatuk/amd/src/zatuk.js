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
 * Defines zatuk script.
 *
 * @since      Moodle 2.0
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['jquery'],
        function($){
    var tables = [];
    return {
        init: function() {
            $( ".dataTable" ).each(function() {
            });
          $(document).on('graphsReload', '#segmented-button',function(){
              $( ".dataTable" ).each(function() {
              var timestamps = $(this).data('timestamps');
              tables[$(this).attr('id')].columns(0).search(timestamps).draw();
            });
          });
          $(document).on('radiochange', '#segmented-button', function(){
            var checkedData = $('#segmented-button').find('input:checked').attr('value');
            var today = new Date();
            var endDate = new Date(today.getFullYear(), today.getMonth(), today.getDate());
            var start_duration = '';
            $('#customrange').hide();
            var check = true;
            switch(checkedData){
              case 'week':
                  start_duration = new Date(today.getFullYear(), today.getMonth(), today.getDate() - 7);
                  break;
              case 'month':
                  start_duration = new Date(today.getFullYear(), today.getMonth() - 1, today.getDate());
                  break;
              case 'year':
                  start_duration = new Date(today.getFullYear() - 1, today.getMonth(), today.getDate());
                  break;
              case 'custom':
                  $('#customrange').show();
                  return;
                break;
                case 'clear':
                  check = false;
                break;
              default:
                  break;
            }
            if(check){
              var timestamps = start_duration.getTime()/1000+'-'+endDate.getTime()/1000;
            }else{
              var timestamps = '0-0';
            }
            var viewDatatble;
            viewDatatble.columns(0).search(timestamps).draw();
            $('#segmented-button').data('timestamps', timestamps);
            $('#segmented-button').trigger('graphsReload');
          });
        $(document).on('change', '#module_select_filter', function(){
          var courseid = $('#course_select_filter').children("option:selected").val();
          window.location.href = M.cfg.wwwroot+'/mod/zatuk/'+$(this).children("option:selected").
          data('page')+'?id='+courseid+'&cmid='+$(this).children("option:selected").val();
        });
        $(document).on('change', '#course_select_filter', function(){
          window.location.href = M.cfg.wwwroot+'/mod/zatuk/'+$(this).children("option:selected").
          data('page')+'?id='+$(this).children("option:selected").val();
        });

      }
    };
});
