<?php
/**
 * Plugin Name: WordPress Event Tracking Plugin
 * Plugin URI: https://github.com/BabuYii/WordPress-Event-Tracking-Plugin
 * Description: The WordPress tracking plugin Logs every new post with posted time , Log every new comments and reply posted time
 * Version: 1.0
 * Author: Babu M
 * Author URI: https://github.com/BabuYii
 * License: The MIT License (MIT)

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
SOFTWARE.
 */


require_once('PHP/config.php');

class WPTracking{
    
     static $instance;
    
     function __construct()
    {
        self::$instance = $this;

        add_action('init', array($this, 'init'));
    }
    //add hooks on app init
    function init()
    {
        add_action(  'transition_post_status',  array($this,'log_post'), 10, 3 );
        add_action( 'wp_set_comment_status ', array($this,'log_comment' ));
        add_action('wp_insert_comment',array($this,'log_comment'),99,2);

    }
    
    public function log_post($new_status, $old_status, $post){
       if($new_status=='publish'&&$old_status=='draft')
       {
           $this->wplog('P',$post); //Post log
       }
    }
    public function log_comment($comment_id, $comment_object){
        
       if ($comment_object->comment_parent > 0) { //Reply 
           $this->wplog('R',$comment_object);
       }
       else {                                  //new comment
           $this->wplog('C',$comment_object);
       }
    }
    
    
    public function wplog($type,$data)
    {
        global $wpdb;
        //Save log by data 
        if($type=='P')
        {
            if(get_option('wp_tracking_post')==1)
            {
                $user=get_user_meta( $data->post_author);
                $author=$user['nickname'][0];
                $posttitle=$data->post_name;
                $post_id=$data->ID;
                $insert="INSERT INTO `".$wpdb->prefix."tracking`( `track_type`, `post_id`, `content`, `author`) VALUES
                    ('P', '$post_id', '$posttitle','$author')";
                $wpdb->query($insert);
            }
        }
        if($type=='C')
        {
            if(get_option('wp_tracking_comment')==1)
            {
                $author=$data->comment_author;
                $comment=$data->comment_content;
                $com_id=$data->comment_ID;
                $insert="INSERT INTO `".$wpdb->prefix."tracking`( `track_type`, `comment_id`, `content`, `author`) VALUES
                    ('C', '$com_id', '$comment','$author')";
                $wpdb->query($insert);
            }
        }
        if($type=='R')
        {
            if(get_option('wp_tracking_reply')==1)
            {
                $author=$data->comment_author;
                $comment=$data->comment_content;
                $com_id=$data->comment_ID;
                $insert="INSERT INTO `".$wpdb->prefix."tracking`( `track_type`, `comment_id`, `content`, `author`) VALUES
                    ('R', '$com_id', '$comment','$author')";
                $wpdb->query($insert);
            }
        }
    }
}
new WPTracking;