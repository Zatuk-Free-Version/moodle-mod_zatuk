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
 * mod_zatuk zatuk constants class
 *
 * @package    mod_zatuk
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_zatuk;

/**
 * class zatuk constants
 */
class zatuk_constants {

    /**
     * @var int indicates accepted video types.
     */
    public const ACCEPTED_VIDEO_TYPES = ['.mp4', '.m4v', '.mov'];
    /**
     * @var int default page limit.
     */
    public const DEFAULTPAGELIMIT = 10;
    /**
     * @var int indicates the default status as zero.
     */
    public const DEFAULTSTATUS = 0;
    /**
     * @var int indicates the status-a value as 1.
     */
    public const STATUSA = 1;
    /**
     * @var int indicates the status-b value as 2.
     */
    public const STATUSB = 2;
    /**
     * @var int indicates the status-c value as 3.
     */
    public const STATUSC = 3;
    /**
     * @var int indicates the status-d value as 4.
     */
    public const STATUSD = 4;
    /**
     * @var int indicates the status-e value as 5.
     */
    public const STATUSE = 5;

    /**
     * @var int indicates the element max length value as 255.
     */
    public const ELEMENTMAXSIZE = 255;
    /**
     * @var int indicates mod form name element size value.
     */
    public const MOD_FORM_NAME_SIZE = '48';
    /**
     * @var int indicates name value of filepicker.
     */
    public const FILEPICKER = 'filepicker';
    /**
     * @var int indicates name value of hidden.
     */
    public const HIDDEN_VALUE = 'hidden';
    /**
     * @var int indicates name value of all.
     */
    public const ALL = 'all';
    /**
     * @var int indicates static width of the player.
     */
    public const PLAYERWIDTH = 640;
    /**
     * @var int indicates static height of the player.
     */
    public const PLAYERHEIGHT = 268;
    /**
     * @var int indicates static value of guess icon.
     */
    public const GUESS_ICON_SIZE = 24;

}
