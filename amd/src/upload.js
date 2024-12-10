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
 * This file is haveing the functionality for video upload.
 *
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
import $ from 'jquery';
import ModalForm from 'core_form/modalform';
import {get_string as getString} from 'core/str';
import messagemodal from 'mod_zatuk/messagemodal';
const Selectors = {
    actions: {
        uploadvideo: '[data-action="uploadvideo"]',
    },
};
let MessageModal = new messagemodal();
export const init = () => {
    $(document).on('click','#uploadzatukvideoaction', function(e){
        let uploadvideo = e.target.closest(Selectors.actions. uploadvideo);
        e.stopImmediatePropagation();
        const zatukrepositorystatus = uploadvideo.getAttribute('data-zatukrepoenabled');
        const courseid = uploadvideo.getAttribute('data-courseid');
        const zatukid = uploadvideo.getAttribute('data-id');
        if (zatukrepositorystatus == 1) {
            const title = zatukid > 0 ?
                getString('edit') :
                getString('uploadvideo', 'mod_zatuk');
            const form = new ModalForm({
                formClass: 'mod_zatuk\\form\\upload',
                args: {id: zatukid, courseid: courseid},
                modalConfig: {title},
                returnFocus: uploadvideo,
            });
            form.addEventListener(form.events.FORM_SUBMITTED, (event) => {
                event.preventDefault();
                e.preventDefault();
                if (zatukid > 0) {
                    var messageString = getString('videoupdated' ,'mod_zatuk');
                } else {
                    var messageString = getString('videouploaded' ,'mod_zatuk');
                }
                form.modal.destroy();
                messageString.then((str) => {
                  MessageModal.confirmbox(getString('finalzatuksmessage','mod_zatuk',str), true);
                });
            });
            form.show();

        } else {
            getString('enablezatuk' ,'mod_zatuk').then((str) => {
                MessageModal.confirmbox(getString('finalzatuksmessage','mod_zatuk',str));
            });
        }
    });
};
