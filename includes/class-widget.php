<?php
/**
 * Adds VisualTextEditorWidget widget.
 */


class VisualTextEditorWidget extends WP_Widget {


	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {

		$widget_ops = apply_filters(
			'visual-text-editor',
			array(
				'classname'     => 'widget_text',
				'description'   => __( 'This Widget allow you to add html content using visual editor inside Default WP Text Widget.', 'visual-text-editor' ),
			)
		);

		parent::__construct(
			'text',
			__( 'Text', 'visual-text-editor' ),
			$widget_ops
		);
	} // END __construct()

	public function widget( $args, $instance ) {

		extract( $args );

		$title          = apply_filters( 'widget_title', $instance['title'] );
		$content        = apply_filters( 'visual_editor_content', $instance['text'] );
		echo $before_widget;
		echo $before_title . $title . $after_title;
		
		/**
		* Fixed Shortcode issue
		* @since 1.2
		* since (may be since v4.4.0) the widget content is wrapped in a div with a "textwidget" class
		*/
 		echo '<div class="textwidget">';
		$final_content = $this->generate_phpcode( do_shortcode($content) );
 		echo '</div>';

		echo $after_widget;
	} // END widget()


	public function form( $instance ) {

		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = __( 'New title', 'visual-text-editor' );
		}

		if ( isset( $instance['text'] ) ) {
			$text = $instance['text'];
		} else {
			$text = '';
		}

		$output_title = ( isset( $instance['output_title'] ) && $instance['output_title'] == '1' ? true : false );

		?>
		<input type="hidden" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" value="<?php echo esc_attr( $text ); ?>">
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'visual-text-editor' ); ?>:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<a href="javascript:VisualTextEditorWidget.showEditor('<?php echo $this->get_field_id( 'text' ); ?>');" class="button"><?php _e( 'Open Visual Editor', 'visual-text-editor' ) ?></a> 
		</p>

		<?php
	} // END form()

	public function generate_phpcode( $content ){

		// Generate php code
		return 	eval('?> ' . $content . '<?php ');
	}

	public function update( $new_instance, $old_instance ) {

		$instance = array();
		$instance['title']      = ( ! empty( $new_instance['title'] ) ? strip_tags( $new_instance['title'] ) : '' );
		$instance['text']       = ( ! empty( $new_instance['text'] ) ? $new_instance['text'] : '' );

		do_action( 'visual_editor_update', $new_instance, $instance );

		return apply_filters( 'visual_editor_instance_update', $instance, $new_instance );
	} // END update()
} // END class VisualTextEditorWidget

