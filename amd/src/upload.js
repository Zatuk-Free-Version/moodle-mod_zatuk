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
    document.addEventListener('click', function(e) {
        let uploadvideo = e.target.closest(Selectors.actions. uploadvideo);
        if (uploadvideo) {
            e.stopImmediatePropagation();
            const zatukrepositorystatus = uploadvideo.getAttribute('data-zatukrepoenabled');
            const zatukid = uploadvideo.getAttribute('data-id');
            if (zatukrepositorystatus == 1) {
                const title = uploadvideo.getAttribute('data-id') ?
                    getString('uploadvideo', 'mod_zatuk', uploadvideo.getAttribute('data-name')) :
                    getString('uploadvideo', 'mod_zatuk');
                const form = new ModalForm({
                    formClass: 'mod_zatuk\\form\\upload',
                    args: {id: zatukid},
                    modalConfig: {title},
                    returnFocus: uploadvideo,
                });
                form.addEventListener(form.events.FORM_SUBMITTED, () => window.location.reload());
                form.addEventListener(form.events.FORM_SUBMITTED, (event) => {
                    event.preventDefault();
                    if (zatukid) {
                        var messageString = getString('videoupdated' ,'mod_zatuk');
                    } else {
                        var messageString = getString('videouploaded' ,'mod_zatuk');
                    }
                    messageString.then((str) => {
                      MessageModal.confirmbox(getString('finalzatuksmessage','mod_zatuk',str));
                    });
                    setTimeout(function() {
                        window.location.reload();
                    },3500);
                });
                form.show();

            } else {
                getString('enablezatuk' ,'mod_zatuk').then((str) => {
                    MessageModal.confirmbox(getString('finalzatuksmessage','mod_zatuk',str));
                });
            }
        }
    });
};
