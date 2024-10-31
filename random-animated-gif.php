<?php
/*
Plugin Name: WordPress Random Animated Gif
Description: Plugin adds widget and shortcode with random animated gif from http://gifs4u.com to your site.
Version: 1.0.1
Author: Algoritmika Ltd.
Author URI: http://www.algoritmika.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
?>
<?php
class WRAG_widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'WRAG_widget', // Base ID
			'Random Animated Gif', // Name
			array( 'description' => __( 'Animated gifs widget', 'text_domain' ), ) // Args
			
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
	
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		

		//echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
			
		$ret_str = $this->animated_gif_func( NULL );
		echo $ret_str;
		
		add_filter('widget_text', 'do_shortcode', 11);
		add_shortcode('animated-gif', array($this, 'animated_gif_func'));
			
	}
	
	function animated_gif_func( $atts ) {
		$ret_str = file_get_contents('http://gifs4u.com/last-gif.txt');
		//return '<div>'.$ret_str.'</div>'.'<div class="sidebar-spacer"></div>';
		return $ret_str.'<hr />';
	}	
				
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Animated gifs', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}

} // class WRAG_widget

// register WRAG_widget widget
add_action( 'widgets_init', create_function( '', 'register_widget( "WRAG_widget" );' ) );