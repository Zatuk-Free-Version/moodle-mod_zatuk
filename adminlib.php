<?php
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
 * Functions and classes used during installation, upgrades and for admin settings.
 *
 *  ADMIN SETTINGS TREE INTRODUCTION
 *
 *  This file performs the following tasks:
 *   -it defines the necessary objects and interfaces to build the Moodle
 *    admin hierarchy
 *   -it defines the admin_externalpage_setup()
 *
 *  ADMIN_SETTING OBJECTS
 *
 *  Moodle settings are represented by objects that inherit from the admin_setting
 *  class. These objects encapsulate how to read a setting, how to write a new value
 *  to a setting, and how to appropriately display the HTML to modify the setting.
 *
 *  ADMIN_SETTINGPAGE OBJECTS
 *
 *  The admin_setting objects are then grouped into admin_settingpages. The latter
 *  appear in the Moodle admin tree block. All interaction with admin_settingpage
 *  objects is handled by the admin/settings.php file.
 *
 *  ADMIN_EXTERNALPAGE OBJECTS
 *
 *  There are some settings in Moodle that are too complex to (efficiently) handle
 *  with admin_settingpages. (Consider, for example, user management and displaying
 *  lists of users.) In this case, we use the admin_externalpage object. This object
 *  places a link to an external PHP file in the admin tree block.
 *
 *  If you're using an admin_externalpage object for some settings, you can take
 *  advantage of the admin_externalpage_* functions. For example, suppose you wanted
 *  to add a foo.php file into admin. First off, you add the following line to
 *  admin/settings/first.php (at the end of the file) or to some other file in
 *  admin/settings:
 * <code>
 *     $ADMIN->add('userinterface', new admin_externalpage('foo', get_string('foo'),
 *         $CFG->wwwdir . '/' . '$CFG->admin . '/foo.php', 'some_role_permission'));
 * </code>
 *
 *  Next, in foo.php, your file structure would resemble the following:
 * <code>
 *         require(__DIR__.'/../../config.php');
 *         require_once($CFG->libdir.'/adminlib.php');
 *         admin_externalpage_setup('foo');
 *         // functionality like processing form submissions goes here
 *         echo $OUTPUT->header();
 *         // your HTML goes here
 *         echo $OUTPUT->footer();
 * </code>
 *
 *  The admin_externalpage_setup() function call ensures the user is logged in,
 *  and makes sure that they have the proper role permission to access the page.
 *  It also configures all $PAGE properties needed for navigation.
 *
 *  ADMIN_CATEGORY OBJECTS
 *
 *  Above and beyond all this, we have admin_category objects. These objects
 *  appear as folders in the admin tree block. They contain admin_settingpage's,
 *  admin_externalpage's, and other admin_category's.
 *
 *  OTHER NOTES
 *
 *  admin_settingpage's, admin_externalpage's, and admin_category's all inherit
 *  from part_of_admin_tree (a pseudointerface). This interface insists that
 *  a class has a check_access method for access permissions, a locate method
 *  used to find a specific node in the admin tree and find parent path.
 *
 *  admin_category's inherit from parentable_part_of_admin_tree. This pseudo-
 *  interface ensures that the class implements a recursive add function which
 *  accepts a part_of_admin_tree object and searches for the proper place to
 *  put it. parentable_part_of_admin_tree implies part_of_admin_tree.
 *
 *  Please note that the $this->name field of any part_of_admin_tree must be
 *  UNIQUE throughout the ENTIRE admin tree.
 *
 *  The $this->name field of an admin_setting object (which is *not* part_of_
 *  admin_tree) must be unique on the respective admin_settingpage where it is
 *  used.
 *
 * Defines admin_settings_configcheckbox class
 * @package    mod_zatuk
 * @subpackage admin
 * @copyright  1999 onwards Martin Dougiamas  http://dougiamas.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core_admin\local\settings\linkable_settings_page;

defined('MOODLE_INTERNAL') || die();

// Add libraries.
require_once($CFG->libdir.'/ddllib.php');
require_once($CFG->libdir.'/xmlize.php');
require_once($CFG->libdir.'/messagelib.php');

// Add classes, traits, and interfaces which should be autoloaded.
// The autoloader is configured late in setup.php, after ABORT_AFTER_CONFIG.
// This is also required where the setup system is not included at all.
require_once($CFG->dirroot.'/'.$CFG->admin.'/classes/local/settings/linkable_settings_page.php');


/**
 * Checkbox
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_settings_configcheckbox extends admin_setting {
    /** @var string Value used when checked */
    public $yes;
    /** @var string Value used when not checked */
    public $no;

    /**
     * Constructor
     * @param string $name unique ascii name, either 'mysetting'
     * for settings that in config, or 'myplugin/mysetting' for ones in config_plugins.
     * @param string $visiblename localised
     * @param string $description long localised info
     * @param string $defaultsetting
     * @param string $yes value used when checked
     * @param string $no value used when not checked
     */
    public function __construct($name, $visiblename, $description, $defaultsetting, $yes='1', $no='0') {
        parent::__construct($name, $visiblename, $description, $defaultsetting);
        $this->yes = (string)$yes;
        $this->no  = (string)$no;
    }

    /**
     * Retrieves the current setting using the objects name
     *
     * @return string
     */
    public function get_setting() {
        return $this->config_read($this->name);
    }

    /**
     * Sets the value for the setting
     *
     * Sets the value for the setting to either the yes or no values
     * of the object by comparing $data to yes
     *
     * @param mixed $data Gets converted to str for comparison against yes value
     * @return string empty string or error
     */
    public function write_setting($data) {
        if ((string)$data === $this->yes) { // Convert to strings before comparison.
            $data = $this->yes;
        } else {
            $data = $this->no;
        }
        return ($this->config_write($this->name, $data) ? '' : get_string('errorsetting', 'admin'));
    }

    /**
     * Returns an XHTML checkbox field
     *
     * @param string $data If $data matches yes then checkbox is checked
     * @param string $query
     * @return string XHTML field
     */
    public function output_html($data, $query='') {
        global $OUTPUT;

        $context = (object) [
            'id' => $this->get_id(),
            'name' => $this->get_full_name(),
            'no' => $this->no,
            'value' => $this->yes,
            'checked' => (string) $data === $this->yes,
            'readonly' => $this->is_readonly(),
        ];

        $default = $this->get_defaultsetting();
        if (!is_null($default)) {
            if ((string)$default === $this->yes) {
                $defaultinfo = get_string('checkboxyes', 'admin');
            } else {
                $defaultinfo = get_string('checkboxno', 'admin');
            }
        } else {
            $defaultinfo = null;
        }

        $element = $OUTPUT->render_from_template('core_admin/setting_configcheckbox', $context);

        return format_admin_setting($this, $this->visiblename, $element, $this->description, true, '', $defaultinfo, $query);
    }
}





