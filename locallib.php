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
 * This file contains the definition for the class zatuk.
 *
 * This class provides all the functionality for the new zatuk module.
 *
 * @package    mod_zatuk
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
use mod_zatuk\zatuk_constants as zc;
/**
 * This methods does weak url validation, we are looking for major problems only,
 * no strict RFE validation.
 *
 * @param string $url
 * @return bool true is seems valid, false if definitely not valid URL
 */
function zatuk_appears_valid_url($url) {
    if (preg_match('/^(\/|https?:|ftp:)/i', $url)) {
        // Note: this is not exact validation, we look for severely malformed URLs only.
        return (bool)preg_match('/^[a-z]+:\/\/([^:@\s]+:[^@\s]+@)?[a-z0-9_\.\-]+(:[0-9]+)?(\/[^#]*)?(#.*)?$/i', $url);
    } else {
        return (bool)preg_match('/^[a-z]+:\/\/...*$/i', $url);
    }
}

/**
 * Fix common URL problems that we want teachers to see fixed
 * the next time they edit the resource.
 *
 * This function does not include any XSS protection.
 *
 * @param string $url
 * @return string
 */
function zatuk_fix_submitted_url($url) {
    // Note: empty urls are prevented in form validation.
    $url = trim($url);

    // Remove encoded entities - we want the raw URI here.
    $url = html_entity_decode($url, ENT_QUOTES, 'UTF-8');

    if (!preg_match('|^[a-z]+:|i', $url) && !preg_match('|^/|', $url)) {
        // Invalid URI, try to fix it by making it normal URL.
        // Please note relative urls are not allowed, /xx/yy links are ok.
        $url = 'http://'.$url;
    }
    return $url;
}

/**
 * Decide the best display format.
 * @param object $url
 * @return int display type constant
 */
function zatuk_get_final_display_type($url) {
    global $CFG;
    require_once($CFG->libdir.'/resourcelib.php');

    if ($url->display != RESOURCELIB_DISPLAY_AUTO) {
        return $url->display;
    }

    // Detect links to local moodle pages.
    if (strpos($url->externalurl, $CFG->wwwroot) === zc::DEFAULTSTATUS) {
        if (strpos($url->externalurl, 'file.php') === false && strpos($url->externalurl, '.php') !== false ) {
            // Most probably our moodle page with navigation.
            return RESOURCELIB_DISPLAY_OPEN;
        }
    }

    static $download = ['application/zip', 'application/x-tar', 'application/g-zip',     // Binary formats.
                             'application/pdf', 'text/html'];  // These are known to cause trouble for external links, sorry.
    static $embed    = ['image/gif', 'image/jpeg', 'image/png', 'image/svg+xml',         // Images.
                             'application/x-shockwave-flash', 'video/x-flv', 'video/x-ms-wm', // Video formats.
                             'video/quicktime', 'video/mpeg', 'video/mp4',
                             'audio/mp3', 'audio/x-realaudio-plugin', 'x-realaudio-plugin',   // Audio formats.
                            ];

    $mimetype = resourcelib_guess_url_mimetype($url->externalurl);

    if (in_array($mimetype, $download)) {
        return RESOURCELIB_DISPLAY_DOWNLOAD;
    }
    if (in_array($mimetype, $embed)) {
        return RESOURCELIB_DISPLAY_EMBED;
    }

    // Let the browser deal with it somehow.
    return RESOURCELIB_DISPLAY_OPEN;
}


/**
 * Optimised mimetype detection from general URL
 * @param string|null $fullurl
 * @return string|null mimetype or null when the filetype is not relevant.
 */
function zatuk_guess_icon($fullurl) {
    global $CFG;
    require_once($CFG->libdir.'/filelib.php');

    if (substr_count($fullurl, '/') < 3 || substr($fullurl, -1) === '/') {
        // Most probably default directory - index.php, index.html, etc. Return null because.
        // We want to use the default module icon instead of the HTML file icon.
        return null;
    }
    $icon = file_extension_icon($fullurl);
    $htmlicon = file_extension_icon('.htm');
    $unknownicon = file_extension_icon('');

    // We do not want to return those icon types, the module icon is more appropriate.
    if ($icon === $unknownicon || $icon === $htmlicon) {
        return null;
    }

    return $icon;
}

