<?php
//avoid direct calls to this file
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

class VisualTextEditor{
	/*
	 * @var string
	 */
	const VERSION = '1.0';

	/*
	 * Action: init
	 */
	public function __construct(){

        add_action( 'widgets_init', array( $this, 'widgets_init' ) );
		add_action( 'load-widgets.php', array( $this, 'load_plugin_assets' ) );
		add_action( 'load-customize.php', array( $this, 'load_plugin_assets' ) );
		add_action( 'widgets_admin_page', array( $this, 'visual_editor_output_html' ), 100 );
		add_action( 'customize_controls_print_footer_scripts', array( $this, 'visual_editor_output_html' ), 1 );
        add_action( 'customize_controls_print_footer_scripts', array( $this, 'customize_controls_print_footer_scripts' ), 2 );
    }

    public function customize_controls_print_footer_scripts() {

        $wp_version = get_bloginfo( 'version' );
        if ( version_compare( $wp_version, '3.9.1', '<' ) && class_exists( '_WP_Editors' ) ) {
            _WP_Editors::enqueue_scripts();
        }

    }
	public function load_plugin_assets(){
		wp_register_script( 'visual-text-widget-js', plugins_url( '../js/visual-editor-widget.js', __FILE__ ), array( 'jquery' ), self::VERSION );
		wp_enqueue_script( 'visual-text-widget-js' );
		wp_register_style( 'visual-text-editor-css', plugins_url( '../css/visual-text-editor.css', __FILE__ ), array(), self::VERSION );
		wp_enqueue_style( 'visual-text-editor-css' );
	}
	/*
	 * Action: widgets_admin_page
	 */
	public function visual_editor_output_html(){
		?>
		<div id="visual-editor-overlay" style="display: none;"></div>
		<div id="visual-editor-widget-controller" style="display: none;">
			<a class="close" href="javascript:VisualTextEditorWidget.hideEditor();" title="<?php esc_attr_e( 'Close', 'visual-text-editor' ); ?>"><span class="icon"></span></a>
			<div class="editor">
				<?php $settings = array( 'textarea_rows' => 30, );
				wp_editor( '', 'visualeditorwidget', $settings );
				?>
				<p>
					<a href="javascript:VisualTextEditorWidget.updateWidgetAndCloseEditor(true);" class="button button-primary"><?php _e( 'Save & close', 'visual-text-editor' ); ?></a>
				</p>
			</div>
		</div>
		<?php
	}

	/*
	 * Override WP Text Widget to Visual Text Widget
	 */
	public function widgets_init() {
		unregister_widget( 'WP_Widget_Text' );
		register_widget( 'VisualTextEditorWidget' );
	}
} // End VisualTextEditor


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
				'classname' 	=> 'widget_text',
				'description' 	=> __( 'This Widget allow you to add html content using visual editor inside Default WP Text Widget.', 'visual-text-editor' ),
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

		$title			= apply_filters( 'widget_title', $instance['title'] );
		$content		= apply_filters( 'visual_editor_content', $instance['text'] );
		echo $before_widget;
		echo $before_title . $title . $after_title;
		/** Mod: since (may be since v4.4.0) the widget content is wrapped in a div with a "textwidget" class */
		echo '<div class="textwidget">';
		echo $content;
		echo '</div>';

		echo $after_widget;

	} // END widget()

	
	public function form( $instance ) {

		if ( isset($instance['title']) ) {
			$title = $instance['title'];
		}
		else {
			$title = __( 'New title', 'visual-text-editor' );
		}

		if ( isset($instance['text']) ) {
			$text = $instance['text'];
		}
		else {
			$text = "";
		}

		$output_title = ( isset($instance['output_title']) && $instance['output_title'] == "1" ? true : false );

		?>
		<input type="hidden" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" value="<?php echo esc_attr($text); ?>">
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'visual-text-editor' ); ?>:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<p>
			<a href="javascript:VisualTextEditorWidget.showEditor('<?php echo $this->get_field_id( 'text' ); ?>');" class="button"><?php _e( 'Open Visual Editor', 'visual-text-editor' ) ?></a>
		</p>

		<?php

	} // END form()

	
	public function update( $new_instance, $old_instance ) {

		$instance = array();
		$instance['title']			= ( !empty($new_instance['title']) ? strip_tags( $new_instance['title']) : '' );
		$instance['text']		= ( !empty($new_instance['text']) ? $new_instance['text'] : '' );

		do_action( 'visual_editor_update', $new_instance, $instance );

		return apply_filters( 'visual_editor_instance_update', $instance, $new_instance );

	} // END update()

} // END class VisualTextEditorWidget

