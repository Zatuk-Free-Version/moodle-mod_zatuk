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
 * This file is having the functionality for deletevideo and publish video.
 *
 * @since      Moodle 2.0
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
import {get_string as getString} from 'core/str';
import ModalFactory from 'core/modal_factory';
import Ajax from 'core/ajax';
import ModalEvents from 'core/modal_events';
import Templates from 'core/templates';
const Selectors = {
    actions: {
        deletevideo: '[data-action="deletevideo"]',
        movetozatuk: '[data-action="movetozatuk"]',
    },
};
export const init = () => {
    document.addEventListener('click', function(e) {
        e.stopImmediatePropagation();
        let deletevideo = e.target.closest(Selectors.actions.deletevideo);
        if (deletevideo) {
            const id = deletevideo.getAttribute('data-id');
            ModalFactory.create({
                title: getString('deletevideo', 'mod_zatuk'),
                type: ModalFactory.types.SAVE_CANCEL,
                body: getString('deleteconfirm', 'mod_zatuk')
            }).done(function(modal) {
                this.modal = modal;
                modal.setSaveButtonText(getString('delete', 'mod_zatuk'));
                modal.getRoot().on(ModalEvents.save, function(e) {
                    e.preventDefault();
                    var params = {};
                    params.id = id;
                    var promise = Ajax.call([{
                        methodname: 'mod_zatuk_delete_video',
                        args: params
                    }]);
                    promise[0].done(function() {
                        window.location.reload(true);
                    }).fail(function() {
                    });
                }.bind(this));
                modal.show();
            }.bind(this));
        }
        let movetozatuk = e.target.closest(Selectors.actions. movetozatuk);
        if (movetozatuk) {
            const id = movetozatuk.getAttribute('data-id');
            ModalFactory.create({
                title: getString('movetozatuk', 'mod_zatuk'),
                type: ModalFactory.types.SAVE_CANCEL,
                body: getString('movetozatukconfirm', 'mod_zatuk')
            }).done(function(modal) {
                this.modal = modal;
                modal.setSaveButtonText(getString('movetozatuk', 'mod_zatuk'));
                modal.getRoot().on(ModalEvents.save, function(e) {
                    e.preventDefault();
                     Templates.renderForPromise('mod_zatuk/loader', {}).then(({html, js}) => {
                        Templates.appendNodeContents('.modal-content', html, js);
                    });
                    var params = {};
                    params.id = id;
                    var promise = Ajax.call([{
                        methodname: 'mod_zatuk_move_to_zatuk',
                        args: params
                    }]);
                    promise[0].done(function() {
                        window.location.reload(true);
                    }).fail(function() {
                    });
                }.bind(this));
                modal.show();
            }.bind(this));
        }

    });
};
