<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/agusnurwanto
 * @since      1.0.0
 *
 * @package    Wp_Bl
 * @subpackage Wp_Bl/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Bl
 * @subpackage Wp_Bl/admin
 * @author     Agus Nurwanto <agusnurwantomuslim@gmail.com>
 */
use Carbon_Fields\Container;
use Carbon_Fields\Field;

class Wp_Bl_Admin {

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
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Bl_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Bl_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-bl-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Bl_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Bl_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-bl-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function generatePage($options){
		if(
			empty($options['title'])
			|| empty($options['content'])
		){
			return false;
		}
		$nama_page = $options['title'];
		$content = $options['content'];
		$status = 'private';
		if(!empty($options['status'])){
			$status = $options['status'];
		}
		$update = false;
		if(!empty($options['update'])){
			$update = $options['update'];
		}


		$custom_post = get_page_by_title($nama_page, OBJECT, 'page');
		$_post = array(
			'post_title'	=> $nama_page,
			'post_content'	=> $content,
			'post_type'		=> 'page',
			'post_status'	=> $status,
			'comment_status'	=> 'closed'
		);
		if (empty($custom_post) || empty($custom_post->ID)) {
			$id = wp_insert_post($_post);
			$_post['insert'] = 1;
			$_post['ID'] = $id;
			$custom_post = get_page_by_title($nama_page, OBJECT, 'page');

			// post meta for astra theme
			update_post_meta($custom_post->ID, 'ast-breadcrumbs-content', 'disabled');
			update_post_meta($custom_post->ID, 'ast-featured-img', 'disabled');
			update_post_meta($custom_post->ID, 'ast-main-header-display', 'disabled');
			update_post_meta($custom_post->ID, 'footer-sml-layout', 'disabled');
			update_post_meta($custom_post->ID, 'site-content-layout', 'page-builder');
			update_post_meta($custom_post->ID, 'site-post-title', 'disabled');
			update_post_meta($custom_post->ID, 'site-sidebar-layout', 'no-sidebar');
			update_post_meta($custom_post->ID, 'theme-transparent-header-meta', 'disabled');
		}else if($update){
			$_post['ID'] = $custom_post->ID;
			wp_update_post( $_post );
			$_post['update'] = 1;
		}
		return $this->get_link_post($custom_post);
	}

	public function get_link_post($custom_post){
		$link = get_permalink($custom_post);
		return $link;
	}

	public function crb_attach_wp_bl_options() {
		$url_singkronisasi_lpse = $this->generatePage(array(
			'title' => 'AdminLTE chart page', 
			'content' => '[adminlte_chart_page]',
			'status' => 'publish'
		));
		$basic_options_container = Container::make( 'theme_options', __( 'CRB Options' ) )
			->set_page_menu_position( 4 )
	        ->add_fields( array(
	        	Field::make( 'text', 'crb_wp_bl_text', 'Carbon field type text' )
	            	->set_default_value('ini default value'),
	            Field::make( 'html', 'crb_wp_bl_adminlte' )
	            	->set_html( '<a target="_blank" href="'.$url_singkronisasi_lpse.'">Halaman AdminLTE chart page.</a>' )
	    	) );
	}

	public function adminlte_chart_page(){
		if(!empty($_GET) && !empty($_GET['post'])){
			return '';
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/wp-bl-adminlte-chart-page.php';
	}

}
