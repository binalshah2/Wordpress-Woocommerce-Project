<?php ob_start();
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://scopeship.com
 * @since      1.0.0
 *
 * @package    Dropsync
 * @subpackage Dropsync/admin/partials
 */
 
 
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php

$exampleListTable = new Example_List_Table();

$reql = filter_input( INPUT_GET, "la", FILTER_SANITIZE_STRING );
$reqlac = filter_input( INPUT_GET, "ac", FILTER_SANITIZE_STRING );
$reqlp = filter_input( INPUT_GET, "pa", FILTER_SANITIZE_STRING );
$reqln = filter_input( INPUT_GET, "n", FILTER_SANITIZE_STRING );


$getopt = get_option('dropsync-options');
		$token = $getopt['dropsync-token'];
		
		if(!empty($reqlp)){
			
			$folder_path = $reqlp;
		
		}
		else{
			
			$folder_path = '';
		}
		
		if(!empty($reqlac))
		{
			if($reqlac == 'del')
			{
				
				$fl = $folder_path.'/'.$reqln;	
					
				$exampleListTable->dropsync_delete($fl,$token);
		
			}
			if($reqlac == 'down')
			{
				$updl = wp_upload_dir();
				$fl = $folder_path.'/'.$reqln;
				
				$out_fl = $updl['path'].'/'.$reqln;	
			
				
			
				$metadata = $exampleListTable->dropsync_download($token,$fl,$out_fl);
				

			}
			}	

if(!empty($reql)){
	
	if(isset($_POST['submitf']))
	{
			$fpathl = esc_html($_POST['dropsync-folder']);
		$folder_path = $folder_path.'/'.$fpathl;	
		$exampleListTable->dropsync_createfolder($folder_path,$token);
	}
	
	if(isset($_POST['submit']))
	{
		
		
		if(isset($_FILES)){
			
			$uploads = wp_upload_dir();
			$upload_dir = $uploads['basedir'];
			$upload_dir1 = 'dropsync';
    if (! is_dir($upload_dir1)) {
       mkdir( $upload_dir1, 0777 );
    }
	$path=$_FILES['dropsync-upload']['name'];
    $pathto=$upload_dir.'/'.$upload_dir1.'/'.$path;
	
    move_uploaded_file($_FILES['dropsync-upload']['tmp_name'],$pathto) or die( "Could not copy file!");



		$path_origin = $_FILES['dropsync-upload']['name'];  	

			
				
		$exampleListTable->dropsync_upload($path_origin,$token,$pathto);
		
		}
		
		
	}
?>	
	<div class="wrap">
                <div id="icon-users" class="icon32"></div>
				
				<?php if(!empty($reql) && $reql == 'new'){ ?>
				<h2><?php echo __('Dropbox Files');?></h2>
                <form method="post" enctype="multipart/form-data" action="">
 
					<div id="universal-message-container">
			 
						<div class="options">
							<p>
								<label>Upload file to dropbox</label>
								<br />
								<input type="file" name="dropsync-upload" value="" />
								<input type="submit" name="submit" value="Submit">
							</p>
						</div>
					</div>	
				</form>
				<?php }else{ ?>
				<h2><?php echo __('Create Dropbox Folder');?></h2>
                <form method="post" enctype="multipart/form-data" action="">
 
					<div id="universal-message-container">
			 
						<div class="options">
							<p>
								<label>Create Folder</label>
								<br />
								<input type="text" name="dropsync-folder" value="" />
								<input type="submit" name="submitf" value="Submit">
							</p>
						</div>
					</div>	
				</form>
				<?php } 
}
else{
	
	
        $exampleListTable->prepare_items();
    

    ?>
            <div class="wrap">
                <div id="icon-users" class="icon32"></div>
				<h2><?php echo __('Dropbox Files');?></h2>
                <?php $exampleListTable->display(); ?>
            </div>
			
<?php 
}

	// WP_List_Table is not loaded automatically so we need to load it in our application
	if( ! class_exists( 'WP_List_Table' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}

/**
 * Create a new table class that will extend the WP_List_Table
 */
class Example_List_Table extends WP_List_Table
{
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $data = $this->table_data();
        if(!empty($data)){
		usort( $data, array( &$this, 'sort_data' ) );
        }
		$perPage = 10;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);
        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );
		if(!empty($data)){
        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
        }
		$this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
		
	//	$this->process_bulk_action();
    }
	
	
    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            'id'          => 'ID',
            'name'       => 'Name',
            'size' => 'Size',
			'actions' => 'Actions'
        );
        return $columns;
    }
    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }
    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        return array('name' => array('name', false));
    }
    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data()
    {
        $data = array();
		
		$ch = curl_init();

		$getopt = get_option('dropsync-options');
		$token = $getopt['dropsync-token'];
			
		if(isset($_REQUEST['pa'])){
			
			$folder_path = $_REQUEST['pa'];
		
		}
		else{
			
			$folder_path = $getopt['dropsync-folder-path'];
		}
		$lbody = array('path'=>'/'.$folder_path.'');
		$url = 'https://api.dropboxapi.com/2/files/list_folder';
		//$request = new HttpRequest();
		$response = wp_remote_post($url, 
							array('method'=>'POST',
								'httpversion' => '1.0',							
							'headers' => array('Content-Type' => 'application/json',
												'Authorization' => 'Bearer '.$token),
							'body' => 	json_encode($lbody)				
								)
							);
		
		if ( is_wp_error( $response ) ) {
		$error_message = $response->get_error_message();
		echo "Something went wrong: $error_message";
		}
		else{	
		

		$json = json_decode($response['body'], true);
		$i=1;
		if(!empty($json['entries'])){
		foreach ($json['entries'] as $datal) {
	    if($datal['.tag'] == 'folder')
		{
			$ic = '<span class="dashicons dashicons-category"></span>';
		}
		else
		{
			$ic = '<span class="dashicons dashicons-media-default"></span>';
		}
		if(isset($datal['size'])){
			$sizel = $this->formatSizeUnits($datal['size']);
		}
		else{
			$sizel = '';
		}
		
		if($datal['.tag'] == 'folder')
		{
			
		  $flo = array( 'pa' => $datal['name']);
		  
			
			$dl = '<a href="'.esc_url( add_query_arg( $flo ) ).'">'.$ic.' &nbsp;&nbsp;'.$datal['name'].'</a>';
		}
		else{
			$dl = $ic.' &nbsp;&nbsp;'.$datal['name'];
		}
		$flod = array( 'n' => $datal['name'], 'ac' => 'del');
		$flodn = array( 'n' => $datal['name'], 'ac' => 'down');
		
		$actions ='<a href="'.esc_url( add_query_arg( $flod ) ).'"><span class="dashicons dashicons-trash"></span></a>&nbsp;&nbsp;<a href="'.esc_url( add_query_arg( $flodn ) ).'"><span class="dashicons dashicons-upload"></span></a>';
		
		$data[] = array(
                    'id'          => $i,
                    'name'       => $dl,
                    'size'        => $sizel,
					'actions'     => $actions
                    );
			$i++;
		}
		
        return $data;
		}
		}
    }
	
	function dropsync_upload($pat,$token,$pto)
	{
		
		$getopt = get_option('dropsync-options');
		$token = $getopt['dropsync-token'];
			
		if(isset($_REQUEST['pa'])){
			
			$folder_path = $_REQUEST['pa'];
		
		}
		else{
			
			$folder_path = '/';
		}
		
		//$ubody = array($folder_path.''.$pat);
		
		$url = 'https://content.dropboxapi.com/2/files/upload';
		$pathll = $folder_path.''.$pat;
			$dropapi = array('path'=>$pathll,'mode'=>'add');
		//$request = new HttpRequest();
		$response = wp_remote_post($url, 
							array('method'=>'POST', 
							'headers' => array('Content-Type' => 'application/octet-stream',
												'Authorization' => 'Bearer '.$token,
												'Dropbox-API-Arg' => json_encode($dropapi),
												
												),
							'body'	=>file_get_contents($pto)											
								)
							);
		
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();	
			?>
				<div class="notice notice-error is-dismissible">
					<p><?php _e($error_message, 'dropsync'); ?></p>
				</div>
			<?php
    
    
		} else {
				?>
				<div class="notice notice-success is-dismissible">
					<p><?php _e('File has been uploaded successfully', 'dropsync'); ?></p>
				</div>
				<?php	
			
		}
		
				unlink($pto);
		
	}
	
	function dropsync_download($token,$fl,$out_fl)
	{
		
	
		$getopt = get_option('dropsync-options');
		$token = $getopt['dropsync-token'];
			
		if(isset($_REQUEST['pa'])){
			
			$folder_path = $_REQUEST['pa'];
		
		}
		else{
			
			$folder_path = '/';
		}
			
		    $out_fp = fopen($out_fl, 'w+');
		if ($out_fp === FALSE)
        {
        echo "fopen error; can't open $out_fl\n";
        return (NULL);
        }
		

	
		$url = 'https://api.dropboxapi.com/2/files/get_temporary_link';
		$jsl = array('path' => $fl);
		//$request = new HttpRequest();
		$response = wp_remote_post($url, 
							array('method'=>'POST', 
							'headers' => array('Content-Type' => 'application/json',							
												'Authorization' => 'Bearer '.$token
												),
									'body' => json_encode($jsl)					
								)
							);
		
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();	
			?>
				<div class="notice notice-error is-dismissible">
					<p><?php _e($error_message, 'dropsync'); ?></p>
				</div>
			<?php
    
    
		} else {
				
			$resl = json_decode($response['body']);
		
			$content = file_get_contents($resl->link);
		
			file_put_contents($out_fl, $content);
			
				$filepathl = $out_fl;


				if (file_exists($filepathl))
				{
					
					$upload_id = wp_insert_attachment( array(
						'guid'           => $filepathl, 
						'post_mime_type' => mime_content_type($filepathl),
						'post_title'     => preg_replace( '/\.[^.]+$/', '', $resl->metadata->name ),
						'post_content'   => '',
						'post_status'    => 'inherit'
					), $filepathl );

					
					// wp_generate_attachment_metadata() won't work if you do not include this file
					require_once( ABSPATH . 'wp-admin/includes/image.php' );
				 
					// Generate and save the attachment metas into the database
					wp_update_attachment_metadata( $upload_id, wp_generate_attachment_metadata( $upload_id, $filepathl ) );
	
						?>
						<div class="notice notice-success is-dismissible">
							<p><?php _e('File has been uploaded to media liberary', 'dropsync'); ?></p>
						</div>
					<?php	
					    }
			
		}
		
	}
	
	function dropsync_delete($pat,$token)
	{
	
		$getopt = get_option('dropsync-options');
		$token = $getopt['dropsync-token'];
			
		if(isset($_REQUEST['pa'])){
			
			$folder_path = $_REQUEST['pa'];
		
		}
		else{
			
			$folder_path = '/';
		}
		
				$dbody = array('path'=>''.$pat.'');
		$url = 'https://api.dropboxapi.com/2/files/delete_v2';
		$response = wp_remote_post($url, 
							array('method'=>'POST', 
							'headers' => array('Content-Type' => 'application/json',
												'Authorization' => 'Bearer '.$token),
							'body' => json_encode($dbody)					
								)
							);
		
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();	
			?>
				<div class="notice notice-error is-dismissible">
					<p><?php _e($error_message, 'dropsync'); ?></p>
				</div>
			<?php
    
    
		} else {
				?>
				<div class="notice notice-success is-dismissible">
					<p><?php _e('File has been deleted', 'dropsync'); ?></p>
				</div>
				<?php	
			
		}
		
	}
	
	function dropsync_createfolder($pat,$token)
	{
		
		$cbody = array('path'=>''.$pat.'');
		$url = 'https://api.dropboxapi.com/2/files/create_folder_v2';
			$response = wp_remote_post($url, 
							array('method'=>'POST', 
							'headers' => array('Content-Type' => 'application/json',
												'Authorization' => 'Bearer '.$token),
							'body' => json_encode($cbody)					
								)
							);
		
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();	
			?>
				<div class="notice notice-error is-dismissible">
					<p><?php _e($error_message, 'dropsync'); ?></p>
				</div>
			<?php
    
    
		} else {
				?>
				<div class="notice notice-success is-dismissible">
					<p><?php _e('New folder has been created', 'dropsync'); ?></p>
				</div>
				<?php	
			
		}
	}
	
	 function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
}
	
	
    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'id':
            case 'name':
            case 'size':
			case 'actions':
				return $item[ $column_name ];
            default:
                return print_r( $item, true ) ;
        }
    }
    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data( $a, $b )
    {
        // Set defaults
        $orderby = 'name';
        $order = 'asc';
        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby']))
        {
            $orderby = $_GET['orderby'];
        }
        // If order is set use this as the order
        if(!empty($_GET['order']))
        {
            $order = $_GET['order'];
        }
        $result = strcmp( $a[$orderby], $b[$orderby] );
        if($order === 'asc')
        {
            return $result;
        }
        return -$result;
    }

	function extra_tablenav( $which ) {
	   if ( $which == "top" ){
		   
		  		  
		  $upl = array( 'la' => 'new');
		  $fld = array( 'la' => 'folder');
		  
		  //The code that goes before the table is here
		  
		  if(isset($_REQUEST['pa'])){
		  echo '<a href="javascript:history.back()" class="back-h2"> Back</a>';
		  }
		  echo '<a href="'.esc_url( add_query_arg( $upl ) ).'" class="back-h2"> Upload</a>';
		  echo '<a href="'.esc_url( add_query_arg( $fld ) ).'" class="back-h2"> Create New Folder</a>';
	   }

	}
	
	function get_bulk_actions() {
	
	}
	
	
}	