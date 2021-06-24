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
 * @param     $key
 * @param     $data
 * @param int $expire
 *
 * @return bool
 */
function osc_cache_add($key, $data, $expire = 0)
{
    $key .= osc_current_user_locale();

    return Object_Cache_Factory::newInstance()->add($key, $data, $expire);
}


/**
 * @return mixed
 */
function osc_cache_close()
{
    return Object_Cache_Factory::newInstance()->close();
}


/**
 * @param $key
 *
 * @return bool
 */
function osc_cache_delete($key)
{
    $key .= osc_current_user_locale();

    return Object_Cache_Factory::newInstance()->delete($key);
}


/**
 * @return bool
 */
function osc_cache_flush()
{
    return Object_Cache_Factory::newInstance()->flush();
}


/**
 * Initialize Cache factory instance using singleton
 */
function osc_cache_init()
{
    Object_Cache_Factory::newInstance();
}


/**
 * @param $key
 * @param $found
 *
 * @return bool|mixed
 */
function osc_cache_get($key, &$found)
{
    $key .= osc_current_user_locale();

    return Object_Cache_Factory::newInstance()->get($key, $found);
}


/**
 * @param     $key
 * @param     $data
 * @param int $expire
 *
 * @return bool
 */
function osc_cache_set($key, $data, $expire = 0)
{
    $key .= osc_current_user_locale();

    return Object_Cache_Factory::newInstance()->set($key, $data, $expire);
}
