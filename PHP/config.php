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
        <div id="detailview"><i>The Wordpress tracking plugin Logs every new post with posted time , Log every new comments and reply posted time</i></div>
        <form method="post" action="options.php">
            <?php settings_fields('wptracking-settings-group'); ?>
            <?php do_settings_sections('wptracking-settings-group'); ?>
            <?php $post=((get_option('wp_tracking_post')==1)?"checked":((get_option('wp_tracking_post')==null)?"":""));?>
            <?php $comment=((get_option('wp_tracking_comment')==1)?"checked":((get_option('wp_tracking_comment')==null)?"":""));?>
            <?php $reply=((get_option('wp_tracking_reply')==1)?"checked":((get_option('wp_tracking_reply')==null)?"":""));?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Log New Post</th>
                    <td><input type="checkbox" id="wp_tracking_post" name="wp_tracking_post" value=1 <?php echo $post;?>/></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Log Comments</th>
                    <td><input type="checkbox" id="wp_tracking_comment" name="wp_tracking_comment" value=1 <?php echo $comment;?>/></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Log Comment Reply</th>
                    <td><input type="checkbox" id="wp_tracking_reply" name="wp_tracking_reply" value=1 <?php echo $reply;?>/></td>
                </tr>
                
            </table>
            <?php submit_button(); ?>
        </form>
        <style>
            #detailview{
                width: 100%;
                background: none repeat scroll 0% 0% #FFF;
                height: 25px;
                border-radius: 7px;
                box-shadow: 0px 1px 2px #D3D33D;
                padding: 1% 5%;
            }
        </style>
    </div>
<?php
}

//Register the options on Wordpress
function registerWPTrackingSettings(){
     register_setting('wptracking-settings-group', //settings page
        'wp_tracking_post' //option name
    );
     register_setting('wptracking-settings-group', //settings page
        'wp_tracking_comment' //option name
    );
     register_setting('wptracking-settings-group', //settings page
        'wp_tracking_reply' //option name
    );
}