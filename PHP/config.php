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
require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
require_once(ABSPATH . 'wp-admin/includes/template.php');
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
    $wp_list_table = new Track_List_Table();
    $wp_list_table->prepare_items();
?>
	<div class="wrap">

		<?php //Plugin Title ?>
		<div id="icon-plugins" class="icon32"><br /></div>
		<h2><?php echo __('WordPress Tracking') ?></h2>

		<?php //Table of elements
		$wp_list_table->display();
		?>

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

class Track_List_Table extends WP_List_Table {


	/**
     * Constructor, we override the parent to pass our own arguments
     * We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
     */	
	function __construct() {
		parent::__construct( array(
			'singular'=> 'wp_list_text_contact', //Singular label
			'plural' => 'wp_list_test_contacts', //plural label, also this well be one of the table class
			'ajax'	=> false //We won't support Ajax for this table
		) );
	}
	

    /**
     * Add extra markup in the toolbars before or after the list       
     * @param string $which, helps you decide if you add the markup after (bottom) or before (top) the list
     */		
	function extra_tablenav( $which ) {
		if ( $which == "top" ){
			//The code that goes before the table is here
			echo"Tracking Logs";
		}
	}		


    /**
     * Define the columns that are going to be used in the table  
     * @return array $columns, the array of columns to use with the table
     */		
	function get_columns() {
		return $columns= array(
			'col_track_type'=>__('Type'),
			'col_content'=>__('Content'),
			'col_log_time'=>__('Log Time'),
			'col_author'=>__('Author')
		);
	}


    /**
     * Decides on which columns we want to activate the sorting facility    
     * @return array $sortable, the array of columns that can be sorted by the user
     */		
	public function get_sortable_columns() {
		return $sortable = array(
			'col_track_type'=>'track_type',
			'col_log_time'=>'log_time',
			'col_author'=>'author'
		);
	}
	
	
    /**
     * Prepare the table with different parameters, pagination, columns and table elements
     */	
	function prepare_items() {
		global $wpdb, $_wp_column_headers;		
		$screen = get_current_screen();
		
		/* -- Preparing your query -- */
		$query = "SELECT * FROM ".$wpdb->prefix."tracking ORDER BY log_time DESC";
		
		/* -- Ordering parameters -- */
			//Parameters that are going to be used to order the result
			$orderby = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : 'ASC';
			$order = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : '';
			if(!empty($orderby) & !empty($order)){ $query.=' ORDER BY '.$orderby.' '.$order; }

		/* -- Pagination parameters -- */
			//Number of elements in your table?
			$totalitems = $wpdb->query($query); //return the total number of affected rows
			//How many to display per page?
			$perpage = 5;
			//Which page is this?
			$paged = !empty($_GET["paged"]) ? mysql_real_escape_string($_GET["paged"]) : ''; if(empty($paged) || !is_numeric($paged) || $paged<=0 ){ $paged=1; }	//Page Number
			//How many pages do we have in total?
			$totalpages = ceil($totalitems/$perpage); //Total number of pages
			//adjust the query to take pagination into account
			if(!empty($paged) && !empty($perpage)){ 
				$offset=($paged-1)*$perpage;
				$query.=' LIMIT '.(int)$offset.','.(int)$perpage;
			}

		/* -- Register the pagination -- */
			$this->set_pagination_args( array(
				"total_items" => $totalitems,
				"total_pages" => $totalpages,
				"per_page" => $perpage,
			) );
			//The pagination links are automatically built according to those parameters	
		
		/* -- Register the Columns -- */
			$columns = $this->get_columns();
			$_wp_column_headers[$screen->id]=$columns;
			
		/* -- Fetch the items -- */
			$this->items = $wpdb->get_results($query);	

	}	

    /**
     * Display the rows of records in the table
     * @return string, echo the markup of the rows 
     */	
	function display_rows() {
		
		//Get the records registered in the prepare_items method
		$records = $this->items;
		
		//Get the columns registered in the get_columns and get_sortable_columns methods
		$columns = $this->get_columns();
		//Loop for each record
                echo "<thead><tr>";
                foreach ( $columns as $column_name => $column_display_name ) {
				echo '<td>'.$column_display_name.'</td>';
			}
                echo "</tr></thead>";
		if(!empty($records)){foreach($records as $rec){
                  
                    
                        if($rec->track_type=='P'){
                            $permalink = get_permalink( $rec->post_id);
                            $type="Post";
                        }
                        if($rec->track_type=='C'){
                            $comment=get_comment( $rec->comment_id );
                            $permalink = get_permalink( $comment->comment_post_ID);
                            $type="Comment";
                        }
                        if($rec->track_type=='R'){
                            $rcomment=get_comment( $rec->comment_id );
                            $permalink = get_permalink( $rcomment->comment_post_ID);
                            $type="Reply";
                        }
                        
			//Open the line
			echo '<tr>';
			foreach ( $columns as $column_name => $column_display_name ) {
				//Display the cell
				switch ( $column_name ) {
					case "col_track_type":	echo '<td '.$attributes.'><strong><a href="'.$permalink.'">'.$type.'</a></strong></td>';	break;
					case "col_content":	echo '<td '.$attributes.'>'.stripslashes($rec->content).'</td>';	break;
					case "col_log_time":	echo '<td '.$attributes.'>'.stripslashes($rec->log_time).'</td>';	break;
					case "col_author":	echo '<td '.$attributes.'>'.$rec->author.'</td>';	break;
				}
			}
			
			//Close the line
			echo'</tr>';	
		}}
	}
}
