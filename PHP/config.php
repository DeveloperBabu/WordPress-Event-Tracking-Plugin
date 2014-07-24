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
add_action('admin_menu', 'wp_tracking_menu');

/*--------Tables creation ---------------*/
include_once 'tables.php';

function wp_tracking_menu(){
    add_action('admin_init', 'registerWPTrackingSettings');
    add_menu_page('WP Tracking', 'WP Tracking', 'manage_options', 'wptrack-options', 'addWPTrackingOptions');
    add_submenu_page( 'wptrack-options', 'Track Results', 'Track Results', 'manage_options', 'wptrack-results', 'addWPTrackingResults');
}

//Give options for tracking 
function addWPTrackingOptions(){
    ?>
    <div class="wrap">
        <h2>WordPress Tracking</h2>
        <?php 
        if(isset($_GET['settings-updated']))
        {?>
            <div id="setting-error-settings_updated" class="updated settings-error"> 
            <p><strong>Settings saved.</strong></p></div>
        <?php 
        }?>
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
function addWPTrackingResults(){ 
   global $wpdb;
   $logdata=$wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."tracking ORDER BY log_time DESC" );
    ?>
        <style type="text/css">
			@import "<?php echo plugin_dir_url('WordPress-Event-Tracking-Plugin'); ?>WordPress-Event-Tracking-Plugin/css/jquery-ui-1.10.1.custom.css";
			@import "<?php echo plugin_dir_url('WordPress-Event-Tracking-Plugin'); ?>WordPress-Event-Tracking-Plugin/css/TableTools.css";
                        @import "<?php echo plugin_dir_url('WordPress-Event-Tracking-Plugin'); ?>WordPress-Event-Tracking-Plugin/css/demo_table_jui.css";
                        @import "<?php echo plugin_dir_url('WordPress-Event-Tracking-Plugin'); ?>WordPress-Event-Tracking-Plugin/css/demo_page.css";
        </style>
         <script src="<?php echo plugin_dir_url('netforum'); ?>WordPress-Event-Tracking-Plugin/js/jquery-1.8.3.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo plugin_dir_url('netforum'); ?>WordPress-Event-Tracking-Plugin/js/jquery.dataTables.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
		$('#logdata').dataTable({							
						"sPaginationType": "full_numbers",
						"bJQueryUI": true,
		});
                });
</script>
    <div class="wrap">
        <h2>WordPress Tracking</h2>
        <div id="detailview"><i>Tracking Logs</i></div>
        <table id="logdata" width="100%">
            <thead>
                <tr>
                    <td width="15%">Type</td>
                    <td width="35%">Content</td>
                    <td width="20%">Log Time</td>
                    <td>Author</td>
                </tr>
            </thead>
            <tbody>
            <?php 
            foreach($logdata as $value)
            {
                $type=($value->track_type=='P')?'Post':(($value->track_type=='C')?'Comment':(($value->track_type=='R')?"Reply":""));
                $content=$value->content;
                $time=$value->log_time;
                $author=$value->author;
                ?>
            
            <tr>
                <td><?php echo $type; ?></td>
                <td><?php echo $content; ?></td>
                <td><?php echo $time; ?></td>
                <td><?php echo $author; ?></td>
            </tr>
            
            <?php 
            }
            ?>
            </tbody>
        </table>
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