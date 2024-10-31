<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http:/webstilo.com
 * @since      1.0.0
 *
 * @package    remove-media-library
 * @subpackage remove-media-library/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    remove-media-library
 * @subpackage remove-media-library/admin
 * @author     Eduard Puigdemunt <puigdemunt@gmail.com>
 */

class Remove_Media_Library_Admin
{

	/**
  * The ID of this plugin.
  *
  * @since    1.0.0
  * @access   private
  * @var      string    $plugin_name    The ID of this plugin.
  */
	private $plugin_name;

	/**
  * The version of this plugin.
  *
  * @since    1.0.0
  * @access   private
  * @var      string    $version    The current version of this plugin.
  */
	private $version;

	/**
  * Initialize the class and set its properties.
  *
  * @since    1.0.0
  * @param      string    $plugin_name       The name of this plugin.
  * @param      string    $version    The version of this plugin.
  */

	public function __construct($plugin_name, $version)
	{
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
  * Register the stylesheets for the admin area.
  *
  * @since    1.0.0
  */
	public function enqueue_styles()
	{
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__ ) . 'css/remove-media-library-admin.css', array (), $this->version, 'all');
	}

	/**
  * Register the JavaScript for the admin area.
  *
  * @since    1.0.0
  */
	public function enqueue_scripts()
	{
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__ ) . 'js/remove-media-library-admin.js', array ('jquery'), $this->version, false);
	}

	/**
  * Register the administration menu for this plugin into the WordPress Dashboard menu.
  *
  * @since    1.0.0
  */

	public function add_plugin_admin_menu()
	{

		/*
   * Add a settings page for this plugin to the Settings menu.
   *
   * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
   *
   *        Administration Menus: http://codex.wordpress.org/Administration_Menus
   *
   */
		add_menu_page('Remove all media from WordPress Media Library', 'Remove Media Library', 'manage_options', $this->plugin_name, array ($this, 'display_plugin_page')
		);
	}


	/**
  * Render the settings page for this plugin.
  *
  * @since    1.0.0
  */

	public function display_plugin_page()
	{
		include_once ('partials/remove-media-library-admin-display.php');
	}


	public function form_response()
	{
		if (isset ($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'form_nonce'))
		{

			// sanitize the input
			$images = sanitize_key($_POST['remove-media-library_']['images']);
			$videos = sanitize_key($_POST['remove-media-library_']['videos']);
			$documents = sanitize_key($_POST['remove-media-library_']['docs']);
			$audios = sanitize_key($_POST['remove-media-library_']['audios']);
			$orphans = sanitize_key($_POST['remove-media-library_']['orphans']);

			// do the processing

			if ($orphans == 1)
			{
				$only_orphan = true;
				$text = 'Removing orphans selected...';
			}
			else
			{
				$only_orphan = false;
				$text = 'Removing all files selected...';
			}
			$result = plugin_dir_path(__FILE__ ) . 'result.log';

			file_put_contents($result, 'DATE: ' . date('Y-m-d H:i:s') . '<BR>', LOCK_EX);
			file_put_contents($result, $text . '<BR>', FILE_APPEND | LOCK_EX);
			// file_put_contents($result, 'Reload page to refresh log<BR><BR>', FILE_APPEND | LOCK_EX);

			$attachments = array ();

			if ($images == 1)
			{
				//echo " IMAGES ";
				$data = $this->get_attachments_id("image", $only_orphan);
				if ($data)
				{
					$attachments = array_merge($attachments, $data);
				}
			}
			if ($videos == 1)
			{
				//echo " VIDEOS ";
				$data = $this->get_attachments_id("video", $only_orphan);
				if ($data)
				{
					$attachments = array_merge($attachments, $data);
				}
			}
			if ($documents == 1)
			{
				//echo " DOCUMENTS ";
				$data = $this->get_attachments_id("application", $only_orphan);
				if ($data)
				{
					$attachments = array_merge($attachments, $data);
				}
			}
			if ($audios == 1)
			{
				//echo " AUDIOS ";
				$data = $this->get_attachments_id("audio", $only_orphan);
				if ($data)
				{
					$attachments = array_merge($attachments, $data);
				}
			}
			// add the admin notice
			// $admin_notice = "success";
			if ($attachments){
			$_SESSION["arrays"] = array_chunk($attachments, 1000); // ARRAY OF ARRAYS

			exit (json_encode(true));
			}
			else{
				$_SESSION["arrays"] = array(); 
				exit (json_encode(false));
			}
		}
		else
		{
			exit (json_encode(false));
		}
	}

	/**
  * Print Admin Notices
  * 
  * @since    1.0.0
  */
	public function print_plugin_admin_notices()
	{
		if (isset ($_REQUEST['admin_add_notice']))
		{
			if ($_REQUEST['admin_add_notice'] === "success")
			{
				$html = '<div class="notice notice-success is-dismissible"> 
							<p><strong>' . esc_attr__('Purgue process finished. Please, check output log.', $this->plugin_name) . '</strong></p></div>';
				echo $html;
			}
		// handle other types of form notices
		}
		else
		{
			return;
		}
	}

	function get_attachments_id($mime, $only_orphan)
	{

		if ($only_orphan)
		{
			$array = array (
			 'post_type' => 'attachment',
			 'post_mime_type' => $mime,
			 'numberposts' => -1,
			 'fields' => 'ids',
			 'post_parent' => 0
			);
		}
		else
		{
			$array = array (
			 'post_type' => 'attachment',
			 'post_mime_type' => $mime,
			 'numberposts' => -1,
			 'fields' => 'ids'
			);
		}
		$attachments = get_posts($array);
		if ($attachments)
		{
			return $attachments;
		}
	}

	// AJAX PROCESS
	function process_chunk()
	{
		// AJAX PARAMS
		$lenght = count($_SESSION["arrays"]);
		$count = intval($_POST['counter']);

		if ($count <= $lenght -1)
		{
			$this->remove_media_chunk($count);
		}
		if ($count < $lenght -1)
		{
			exit (json_encode(true));
		}
		else
		{
			exit (json_encode(false));
		}
	}

	function remove_media_chunk($count)
	{
		$result = plugin_dir_path(__FILE__ ) . 'result.log';
		foreach ($_SESSION["arrays"][$count] as $array_id)
		{
			$delete_attachment = wp_delete_attachment($array_id, false);
			if ($delete_attachment->ID)
			{
				$counter = $counter + 1;
			}
		}
		file_put_contents($result, number_format_i18n($counter, 0) . ' media removed.<BR>', FILE_APPEND | LOCK_EX);
	}


	function attachment_count($mime, $only_orphan)
	{
		// mime: 'image', 'video', 'document', 'audio' 
		global $post;
		//Get all attachments
		$attachments = get_posts(array (
		 'post_type' => 'attachment',
		 'post_mime_type' => $mime,
		 'posts_per_page' => -1,
		 'fields' => 'id=>parent'
		));

		$att_count = 0;
		if ($attachments)
		{
			foreach ($attachments as $attachment)
			{
				if ($only_orphan && $attachment == 0 || !$only_orphan)
				{
					$att_count = $att_count + 1;
				}
			}
			return $att_count;
		}
	}

	function activate_media_trash()
	{

		if (isset ($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'form_nonce'))
		{
			// sanitize the input
			$action = absint($_POST['remove-media-library_']['action']);

			$config_file = ABSPATH . 'wp-config.php';

			if (file_exists($config_file))
			{
				$added = array ();
				$constant = 'MEDIA_TRASH';
				$array = file($config_file);

				$found = false;

				foreach ($array as $line)
				{
					if (substr(trim($line), 0, 6) === "define" && strpos($line, $constant) !== false)
					{
						// UPDATE VARS
						$updated_constant_value = $action == 1? 'true': 'false';
						$updated_constant = str_replace(array ('true', 'false'), $updated_constant_value, trim($line));
						$array[$i] = $updated_constant . "\n";

						$found = true;
					}
					$i++;
				}
				if (false === $found)
				{
					$updated_constant_value = $action == 1? 'true': 'false';
					array_push($added, "define('" . $constant . "', " . $updated_constant_value . " );\n");
				}


				// Find PHP tag
				$j = 0;
				$pointer = 0;
				foreach ($array as $ln)
				{
					if (strpos($ln, '<?') !== false)
					{
						$pointer = $j;
						break;
					}
					$j++;
				}

				array_splice($array, $pointer + 1, 0, $added);

				// UPDATE FILE
				$config_contents = implode('', $array);
				file_put_contents($config_file, $config_contents);

				wp_redirect(esc_url_raw(admin_url('admin.php?page=' . $this->plugin_name)));
			}
		}
		else
		{
			wp_die(__('Invalid nonce specified', $this->plugin_name), __('Error', $this->plugin_name), array (
			   'response' => 403,
			   'back_link' => 'admin.php?page=' . $this->plugin_name,

			 ));
		}
	}

	// SESIONS
	function session_start() {
		if( ! session_id() ) {
			session_start();
		}
	}
	
	function session_end() {
		session_destroy();
	}





}