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

use mindstellar\utility\Validate;

/**
 * Class CWebPage
 */
class CWebPage extends BaseModel
{
    public $pageManager;

    public function __construct()
    {
        parent::__construct();

        $this->pageManager = Page::newInstance();
        osc_run_hook('init_page');
    }

    public function doModel()
    {
        $id   = Params::getParam('id');
        $page = false;

        if (is_numeric($id)) {
            $page = $this->pageManager->findByPrimaryKey($id);
        } else {
            $page = $this->pageManager->findByInternalName(Params::getParam('slug'));
        }

        // page not found
        if ($page == false) {
            $this->do404();

            return;
        }

        // this page shouldn't be shown (i.e.: e-mail templates)
        if ($page['b_indelible'] == 1) {
            $this->do404();

            return;
        }

        $kwords = array('{WEB_URL}', '{WEB_TITLE}');
        $rwords = array(osc_base_url(), osc_page_title());
        foreach ($page['locale'] as $k => $v) {
            $page['locale'][$k]['s_title'] = str_ireplace(
                $kwords,
                $rwords,
                osc_apply_filter('email_description', $v['s_title'])
            );
            $page['locale'][$k]['s_text']  =
                str_ireplace($kwords, $rwords, osc_apply_filter('email_description', $v['s_text']));
        }

        // export $page content to View
        $this->_exportVariableToView('page', $page);
        if (Params::getParam('lang') && (new Validate())->localeCode(Params::getParam('lang'))) {
            Session::newInstance()->_set('userLocale', Params::getParam('lang'));
        }

        $meta = json_decode($page['s_meta'], true);

        // load the right template file
        if (file_exists(osc_themes_path() . osc_theme() . '/page-' . $page['s_internal_name']
            . '.php')
        ) {
            $this->doView('page-' . $page['s_internal_name'] . '.php');
        } elseif (isset($meta['template'])
            && file_exists(osc_themes_path() . osc_theme() . '/' . $meta['template'])
        ) {
            $this->doView($meta['template']);
        } elseif (isset($meta['template'])
            && file_exists(osc_plugins_path() . '/' . $meta['template'])
        ) {
            osc_run_hook('before_html');
            require osc_plugins_path() . '/' . $meta['template'];
            Session::newInstance()->_clearVariables();
            osc_run_hook('after_html');
        } else {
            $this->doView('page.php');
        }
    }

    /**
     * @param $file
     *
     * @return void
     */
    public function doView($file)
    {
        osc_run_hook('before_html');
        osc_current_web_theme_path($file);
        Session::newInstance()->_clearVariables();
        osc_run_hook('after_html');
    }
}

/* file end: ./CWebPage.php */
