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

/*--------Table creation--------*/
add_action('init','create_tables');

function create_tables()
{

        global $wpdb;
	$charset_collate = "";
	
	if ($wpdb->has_cap('collation')) {
		if ( ! empty($wpdb->charset) )
			$charset_collate = " DEFAULT CHARACTER SET $wpdb->charset";
		if ( ! empty($wpdb->collate) )
			$charset_collate .= " COLLATE $wpdb->collate";
	}

	if ($wpdb->get_var("SHOW TABLES LIKE '".$wpdb->prefix."tracking'") != $wpdb->prefix."tracking") {

		$sql = "CREATE TABLE `".$wpdb->prefix."tracking`(
                        `track_id` bigint(20) NOT NULL AUTO_INCREMENT,
                        `track_type` char(1) NOT NULL COMMENT 'P-  Post , C- Comment, R-Reply',
                        `post_id` bigint(20) NOT NULL,
                        `comment_id` bigint(20) NOT NULL,
                        `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        PRIMARY KEY (`track_id`),
                        KEY `Post-Index` (`track_type`,`post_id`),
                        KEY `Com-Index` (`track_type`,`comment_id`),
                        KEY `log_time` (`log_time`)
                      )".$charset_collate.";";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
       add_option( 'wp_tracking_post', 1);
       add_option( 'wp_tracking_comment', 1);
       add_option( 'wp_tracking_reply', 1);
}