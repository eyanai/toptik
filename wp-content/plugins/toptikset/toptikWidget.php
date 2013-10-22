<?php 
/**
 * Adds Toptik_Widget widget.
 */
class TOPtik_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'toptik_child', // Base ID
			__('הצגת תתי קטגוריות', 'תתי קטגוריות'), // Name
			array( 'description' => __( 'לינקים עבור ההרכיה ותתי קטגוריות', ' תתי קטגוריות' )) // Args
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
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
		//echo __( 'Hello, World!', 'text_domain' );
			$current_tax = get_query_var('product_cat');
				$taxonomy_name='product_cat';
				if(!empty($current_tax)){
				
				$queried_object = get_queried_object();
				$term_id = $queried_object->term_id;
				$current_tax=$queried_object->name;
				$termchildren = get_term_children( $term_id, $taxonomy_name );

				echo "<ul><li>$current_tax<ul>";
						
				foreach ( $termchildren as $child ) {
					$term = get_term_by( 'id', $child, $taxonomy_name );
					echo '<li><a href="' . get_term_link( $term->name, $taxonomy_name ) . '">' . $term->name . '</a></li>';
				}
				echo '</ul></li></ul>';
				}
		
		
		
		//////////////
		echo $args['after_widget'];
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
			$title = __( 'New title', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
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
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

} // class Toptik_Widget

add_action( 'widgets_init', function(){
     register_widget( 'TOPtik_Widget' );
});


///////////////////////////////////////
				