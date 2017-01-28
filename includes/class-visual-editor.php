<?php

// Avoid direct calls to this file
if ( ! function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

class VisualTextEditor {

	/*
	 * @var string
	 */
	const VERSION = '1.0';

	private $autop = false;
	private $mediabutton = false;
	private $draganddrop = false;
	private $editorheight ;

	/*
	 * Action: init
	 */
	public function __construct() {

		add_action( 'widgets_init', array( $this, 'widgets_init' ) );
		add_action( 'load-widgets.php', array( $this, 'load_plugin_assets' ) );
		add_action( 'load-customize.php', array( $this, 'load_plugin_assets' ) );
		add_action( 'widgets_admin_page', array( $this, 'visual_editor_output_html' ), 100 );
		add_action( 'customize_controls_print_footer_scripts', array( $this, 'visual_editor_output_html' ), 1 );
		add_action( 'customize_controls_print_footer_scripts', array( $this, 'customize_controls_print_footer_scripts' ), 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_plugin_assets' ) );

		add_shortcode( 'vphp', array($this, 'visualtext_phpgenerate') );

		if ( get_option( 'autop' ) == 'on' ) {
			$this->autop = true;
		}

		if ( get_option( 'mediabuttons' ) == 'on' ) {
			$this->mediabutton = true;
		}

		if ( get_option( 'dragndrop' ) == 'on' ) {
			$this->draganddrop = true;
		}

		$this->editorheight = get_option( 'editorheight' ,300 );

	}


	public function customize_controls_print_footer_scripts() {

		$wp_version = get_bloginfo( 'version' );
		if ( version_compare( $wp_version, '3.9.1', '<' ) && class_exists( '_WP_Editors' ) ) {
			_WP_Editors::enqueue_scripts();
		}
	}
	public function load_plugin_assets() {
		wp_register_script( 'visual-text-widget-js', plugins_url( '../js/visual-editor-widget.js', __FILE__ ), array( 'jquery' ), self::VERSION );
		wp_enqueue_script( 'visual-text-widget-js' );
		wp_register_style( 'visual-text-editor-css', plugins_url( '../css/visual-text-editor.css', __FILE__ ), array(), self::VERSION );
		wp_enqueue_style( 'visual-text-editor-css' );
	}
	/*
	 * Action: widgets_admin_page
	 */
	public function visual_editor_output_html() {
		?>
		<div id="visual-editor-overlay" style="display: none;"></div>
		<div id="visual-editor-widget-controller" style="display: none;">
			<a class="close" href="javascript:VisualTextEditorWidget.hideEditor();" title="<?php esc_attr_e( 'Close', TEXTDOMAIN ); ?>"><span class="icon"></span></a>
			<div class="editor">

				<?php
				$settings = array(
								'editor_height' => intval( trim( $this->editorheight ) ) . 'px',
								'drag_drop_upload' => $this->draganddrop,
								'wpautop' => $this->autop,
								'media_buttons' => $this->mediabutton,
							);

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


	public function visualtext_phpgenerate( $atts, $content ){

		$phpcontent = eval( $content );
		return $phpcontent; 
	}

} // End VisualTextEditor

