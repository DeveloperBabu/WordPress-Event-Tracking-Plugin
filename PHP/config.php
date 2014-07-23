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
    
}

//Register the options on Wordpress
function registerWPTrackingSettings(){
    
}