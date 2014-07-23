<?php

/*License: The MIT License (MIT)

Copyright (c) 2014 Babu M

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.*/

/* --------Admin Menu Creation------------*/
add_action('admin_menu', 'wp_tracking_menus');

/*--------Tables creation ---------------*/
include_once 'tables.php';

function wp_tracking_menus()
{
    add_options_page('WP-Tracking', //page_title
        'WP-Tracking', //menu title
        'manage_options', //capability
        __FILE__,
        'addWPTrackingOptions');
    //call register settings function
    add_action('admin_init', 'registerWPTrackingSettings');
}

//Give options for tracking 
function addWPTrackingOptions(){
    ?>
    <div class="wrap">
        <h2>WordPress Tracking</h2>

        <form method="post" action="options.php">
            <?php settings_fields('netforum-sso-settings-group'); ?>
            <?php do_settings_sections('netforum-sso-settings-group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Disable new user creation in netforum ?</th>
                    <td><input type="checkbox" id="netforum_user_create" name="netforum_user_create" value=1 <?php echo (get_option('netforum_user_create')==1)?"checked":""?> /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Disable Write back to netforum ?</th>
                    <td><input type="checkbox" id="netforum_write" name="netforum_write" value=1 <?php echo (get_option('netforum_write')==1)?"checked":""?> /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">xWeb Single SignOn WSDL URL</th>
                    <td><input type="text" name="xweb_sso_wsdl_url" size="60"
                               value="<?php echo get_option('xweb_sso_wsdl_url'); ?>"/></td>
                </tr>
                <tr valign="top">
                    <th scope="row">xWeb Single SignOn WSDL URL</th>
                    <td><input type="text" name="xweb_sso_wsdl_url" size="60"
                               value="<?php echo get_option('xweb_sso_wsdl_url'); ?>"/></td>
                </tr>
                <tr valign="top">
                    <th scope="row">xWeb onDemand WSDL URL</th>
                    <td><input type="text" name="xweb_ondemand_wsdl_url" size="60"
                               value="<?php echo get_option('xweb_ondemand_wsdl_url'); ?>"/></td>
                </tr>
                <tr valign="top">
                    <th scope="row">xWeb Admin Username</th>
                    <td><input type="text" name="xweb_admin_username" size="30"
                               value="<?php echo get_option('xweb_admin_username'); ?>"/></td>
                </tr>

                <tr valign="top">
                    <th scope="row">xWeb Admin Password</th>
                    <td><input type="text" name="xweb_admin_password" size="30"
                               value="<?php echo get_option('xweb_admin_password'); ?>"/></td>
                </tr>
            </table>

            <?php submit_button(); ?>

        </form>
    </div>
<?php
}

//Register the options on Wordpress
function registerWPTrackingSettings(){
    
}