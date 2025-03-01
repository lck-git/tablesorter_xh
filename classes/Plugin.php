<?php

/**
 * Copyright 2012-2024 Christoph M. Becker
 * Copyright 2025 CMSimple_XH developers
 *
 * This file is part of Tablesorter_XH.
 *
 * Tablesorter_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Tablesorter_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Tablesorter_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Tablesorter;

class Plugin
{
    const VERSION = '1.0beta2';

    public function run()
    {
        global $plugin_cf;

        if ($plugin_cf['tablesorter']['auto']) {
            tablesorter();
        }
        if ($plugin_cf['tablesorter']['auto']
        && $plugin_cf['tablesorter']['select_columns']) {
            tablecolumns();
        }
        if (defined('XH_ADM') && XH_ADM) {
            XH_registerStandardPluginMenuItems(true);
            if (XH_wantsPluginAdministration('tablesorter')) {
                $this->handleAdministration();
            }
        }
    }

    protected function handleAdministration()
    {
        global $admin, $action, $o;

        $o .= print_plugin_admin('on');
        switch ($admin) {
            case '':
            case 'plugin_main':
                $o .= $this->renderVersion()
                    . '<hr>'
                    . $this->renderSystemCheck();
                break;
            default:
                $o .= plugin_admin_common($action, $admin, 'tablesorter');
        }
    }

    /**
     * @return string
     */
    protected function renderVersion()
    {
        global $pth, $plugin_tx;

        return '<h1>Tablesorter</h1>'
            . '<img class="tablesorter_logo" src="' . $pth['folder']['plugins']
            . 'tablesorter/images/tablesorter.png" alt="' . $plugin_tx['tablesorter']['alt_logo'] . '">'
            . '<p style="margin-top: 1em">Version: ' . self::VERSION . '</p>'
            . '<p>Copyright &copy; 2012-2024 Christoph M. Becker<br>'
            . 'Copyright &copy; 2025 CMSimple_XH developers</p>'
            . '<p class="tablesorter_license">'
            . 'Tablesorter_XH is free software: you can redistribute it and/or modify'
            . ' it under the terms of the GNU General Public License as published by'
            . ' the Free Software Foundation, either version 3 of the License, or'
            . ' (at your option) any later version.</p>'
            . '<p class="tablesorter_license">'
            . 'Tablesorter_XH is distributed in the hope that it will be useful,'
            . ' but <em>without any warranty</em>; without even the implied warranty of'
            . ' <em>merchantability</em> or <em>fitness for a particular purpose</em>.  See the'
            . ' GNU General Public License for more details.</p>'
            . '<p class="tablesorter_license">'
            . 'You should have received a copy of the GNU General Public License'
            . ' along with Tablesorter_XH.  If not, see'
            . ' <a href="http://www.gnu.org/licenses/">http://www.gnu.org/licenses/'
            . '</a>.</p>';
    }

    /**
     * @return string
     */
    protected function renderSystemCheck()
    {
        global $pth, $plugin_tx, $sl;
    
        $phpVersion =  '7.4.0';
        $ptx = $plugin_tx['tablesorter'];
        $ok = 'xh_success';
        $warn = 'xh_warning';
        $fail = 'xh_fail';
        $o = '<h2>' . $ptx['syscheck_title'] . '</h2>'
           . "\n"
           . '<p class="'
           . (version_compare(PHP_VERSION, $phpVersion) >= 0
                ? $ok
                : $fail)
           . '">'
           . sprintf($ptx['syscheck_phpversion'], $phpVersion)
           . '</p>'
           . "\n";
        foreach (array('languages/',
                       'languages/' . $sl . '.php',
                       'config/config.php',
                       'css/stylesheet.css') as $path) {
            $paths[] = $pth['folder']['plugins'] . 'tablesorter/' . $path;
        }
        foreach ($paths as $path) {
            if(is_writable($path)) {
                $class = $ok;
                $state = '';
            } else {
                $class = $warn;
                $state = $ptx['syscheck_neg_state'];
            }
            $o .= '<p class="'
                . $class
                . '">'
                . sprintf($ptx['syscheck_writable'],
                          $path,
                          $state)
                . '</p>'
                . "\n";
        }
        return $o;
    }
}
