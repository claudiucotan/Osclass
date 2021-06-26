<?php
/*
 * Osclass - software for creating and publishing online classified advertising platforms
 * Maintained and supported by Mindstellar Community
 * https://github.com/mindstellar/Osclass
 * Copyright (c) 2021.  Mindstellar
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *                     GNU GENERAL PUBLIC LICENSE
 *                        Version 3, 29 June 2007
 *
 *  Copyright (C) 2007 Free Software Foundation, Inc. <http://fsf.org/>
 *  Everyone is permitted to copy and distribute verbatim copies
 *  of this license document, but changing it is not allowed.
 *
 *  You should have received a copy of the GNU Affero General Public
 *  License along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

/**
 * Helper Sanitize
 *
 * @package    Osclass
 * @subpackage Helpers
 * @author     Osclass
 */

/**
 * Sanitize a website URL.
 *
 * @param string $value value to sanitize
 *
 * @return string sanitized
 */
function osc_sanitize_url($value)
{
    if (!function_exists('filter_var')) {
        return preg_replace('|([^a-zA-Z0-9\$\-\_\.\+!\*\'\(\),{}\|\^~\[\]`"#%;\/\?:@=<>\\\&]*)|', '', $value);
    }

    return filter_var($value, FILTER_SANITIZE_URL);
}


/**
 * Sanitize a string.
 *
 * @param string $value value to sanitize
 *
 * @return string sanitized
 */
function osc_sanitize_string($value)
{
    return osc_sanitizeString($value);
}


/**
 * Sanitize capitalization for a string.
 * Capitalize first letter of each name.
 * If all-caps, remove all-caps.
 *
 * @param string $value value to sanitize
 *
 * @return string sanitized
 */
function osc_sanitize_name($value)
{
    return ucwords(osc_sanitize_allcaps(trim($value)));
}


/**
 * Sanitize string that's all-caps
 *
 * @param string $value value to sanitize
 *
 * @return string sanitized
 */
function osc_sanitize_allcaps($value)
{
    if (preg_match('/^([A-Z][^A-Z]*)+$/', $value) && !preg_match('/[a-z]+/', $value)) {
        $value = ucfirst(strtolower($value));
    }

    return $value;
}


/**
 * Sanitize a username
 *
 * @param string $value
 *
 * @return string sanitized
 */
function osc_sanitize_username($value)
{
    return preg_replace('/(_+)/', '_', preg_replace('/([^0-9A-Za-z_]*)/', '', str_replace(' ', '_', trim($value))));
}


/**
 * Sanitize number (with no periods)
 *
 * @param string $value value to sanitize
 *
 * @return string sanitized
 */
function osc_sanitize_int($value)
{
    if (!preg_match('/^[0-9]*$/', $value)) {
        return (int)$value;
    }

    return $value;
}


/**
 * Format phone number. Supports 10-digit with extensions,
 * and defaults to international if cannot match US number.
 *
 * @param string $value value to sanitize
 *
 * @return string sanitized
 */
function osc_sanitize_phone($value)
{
    if (empty($value)) {
        return '';
    }

    // Remove strings that aren't letter and number.
    $value = preg_replace('/[^a-z0-9]/', '', strtolower($value));

    // Remove 1 from front of number.
    if (preg_match('/^([0-9]{11})/', $value) && $value[0] == 1) {
        $value = substr($value, 1);
    }

    // Check for phone ext.
    if (!preg_match('/^[0-9]$/', $value)) {
        $value =
            preg_replace('/^([0-9]{10})([a-z]+)([0-9]+)/', '$1ext$3', $value); // Replace 'x|ext|extension' with 'ext'.
        list($value, $ext) = explode('ext', $value); // Split number & ext.
    }

    // Add dashes: ___-___-____
    if (strlen($value) == 7) {
        $value = preg_replace('/([0-9]{3})([0-9]{4})/', '$1-$2', $value);
    } elseif (strlen($value) == 10) {
        $value = preg_replace('/([0-9]{3})([0-9]{3})([0-9]{4})/', '$1-$2-$3', $value);
    }

    return $ext ? $value . ' x' . $ext : $value;
}


/**
 * Escape html
 *
 * Formats text so that it can be safely placed in a form field in the event it has HTML tags.
 *
 * @access  public
 *
 * @param string
 *
 * @return  string
 * @version 2.4
 */
function osc_esc_html($str = '')
{
    if ($str === '') {
        return '';
    }

    $temp = '__TEMP_AMPERSANDS__';

    // Replace entities to temporary markers so that
    // htmlspecialchars won't mess them up
    $str = preg_replace("/&#(\d+);/", "$temp\\1;", $str);
    $str = preg_replace("/&(\w+);/", "$temp\\1;", $str);

    $str = htmlspecialchars($str);

    // In case htmlspecialchars misses these.
    $str = str_replace(array("'", '"'), array('&#39;', '&quot;'), $str);

    // Decode the temp markers back to entities
    $str = preg_replace("/$temp(\d+);/", "&#\\1;", $str);
    $str = preg_replace("/$temp(\w+);/", "&\\1;", $str);

    return $str;
}


/**
 * Escape single quotes, double quotes, <, >, & and line endings
 *
 * @access  public
 *
 * @param string $str
 *
 * @return string
 * @version 2.4
 */
function osc_esc_js($str)
{
    static $sNewLines = '<br><br/><br />';
    static $aNewLines = array('<br>', '<br/>', '<br />');
    $str = strip_tags($str, $sNewLines);
    $str = str_replace("\r", '', $str);
    $str = addslashes($str);
    $str = str_replace("\n", '\n', $str);
    $str = str_replace($aNewLines, '\n', $str);

    return $str;
}
