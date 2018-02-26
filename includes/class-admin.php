<?php

// Avoid direct calls to this file
if ( ! function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

class VisualTextAdmin {

	protected $overlayColor = '#fff';
	protected $autop 		= false;
	protected $dragndrop 	= false;

	public function __construct() {

		add_action( 'admin_menu', array( $this, 'veadminoptions' ) );
		add_action( 'admin_init', array( $this, 'registerPluginSetting' ) );
		add_action( 'admin_head', array( $this, 'veChangeStyle' ) );
		add_shortcode( 'addcode',array( $this, 'addcode_callback' ) );

		if ( get_option( 'autop' ) == 'on' ) {
			$this->autop = true;
		}

		if ( get_option( 'draganddrop' ) == 'on' ) {
			$this->dragndrop = true;
		}

		if ( get_option( 'overlaycolor' ) != '' ) {
			$this->overlayColor = get_option( 'overlaycolor' );
		}
	}

	public function addcode_callback( $atts ) {
		echo 'fdsfdsf';
	}

	public function veChangeStyle() {

		?>
		<style>
		#visual-editor-overlay{
		background: <?php echo $this->overlayColor;?>;
		}
		</style>
		<?php
	}

	public function registerJSCSS() {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker-alpha', WPVE_URL . '/js/wp-color-picker-alpha.js', array( 'wp-color-picker' ), '1.2.2' );
	}

	public function registerPluginSetting() {
		register_setting( 'wpvisualsettings', 'editorheight' );
		register_setting( 'wpvisualsettings', 'overlaycolor' );
		register_setting( 'wpvisualsettings', 'autop' );
		register_setting( 'wpvisualsettings', 'mediabuttons' );
		register_setting( 'wpvisualsettings', 'dragndrop' );

		add_action( 'admin_enqueue_scripts', array( $this, 'registerJSCSS' ) );
	}

	public function veadminoptions() {
		add_menu_page( 'WP Text Widget Settings', 'WP Visual Text ', 'manage_options', 'wptextwidgetsettings', array( $this, 'veTextWidgetSetting' ), WPVE_URL . 'img/wpve-icon.png' );
	}

	public function veTextWidgetSetting() {
		?>
		<h2><?php echo __('General Options','visual-text-editor');?></h2>
		<hr/>
		<form action="options.php" method="post">
		<?php
		settings_fields( 'wpvisualsettings' );
		do_settings_sections( 'wpvisualsettings' );
		?>
		<table class="form-table">
		<tr valign="top">
		<th scope="row"><?php echo __( 'Visual Editor height','visual-text-editor' );?></th>
		<td><input type="number" min="5" max="600" name="editorheight" value="<?php echo esc_attr( get_option( 'editorheight' ) );?>"/><?php echo __( 'px','visual-text-editor' );?><br/><span class="description"><?php echo __( 'Number of textarea rows','visual-text-editor' );?> </span></td>
		</tr>
		<tr>
		<th scope="row"><?php echo __( 'Overlay Color','visual-text-editor' );?> </th>
		<td><input type="text"  name="overlaycolor" data-alpha="true" class="color-picker" id="overlaycolor" value="<?php echo esc_attr( get_option( 'overlaycolor' ) );?>"/><br/><span class="description"><?php echo __( 'Lightbox overlay color','visual-text-editor' );?> </span></td>
		</tr>   
		<tr>
		<th scope="row">
			<?php echo __( 'Auto Paragraph','visual-text-editor' );?>
		</th>
		<td>
			<label for="autop"><input type="checkbox" id="autop"  name="autop" data-alpha="true" id="autop" <?php checked( esc_attr( get_option( 'autop' ) ), 'on', true );?> /><?php echo __( 'Enable','visual-text-editor' );?></label>
			<br/><span class="description"><?php echo __( 'Add paragraph (&lt;p&gt;&lt;/p&gt;) to content automatically.','visual-text-editor' );?></span>
		</td>
		</tr>
		<tr>
		<th scope="row">    
			<?php echo __( 'Media buttons','visual-text-editor' );?>
		</th>
		<td>
			<label for="mediabuttons"><input type="checkbox" id="mediabuttons"  name="mediabuttons" id="mediabuttons" <?php checked( esc_attr( get_option( 'mediabuttons' ) ), 'on', true );?> /><?php echo __( 'Enable','visual-text-editor' );?></label><br/><span class="description"><?php echo __( 'Whether to display media insert/upload buttons','visual-text-editor' );?></span>
		</td>
		</tr>
		<tr>
		<th scope="row">    
			<?php echo __( 'Drag & Drop Upload','visual-text-editor' );?>
		</th>
		<td>
			<label for="dragndrop"><input type="checkbox" id="dragndrop"  name="dragndrop" <?php checked( esc_attr( get_option( 'dragndrop' ) ), 'on', true );?> /><?php echo __( 'Enable','visual-text-editor' );?></label><Br/><span class="description"><?php echo __( 'Enable Drag & Drop Upload Support','visual-text-editor' );?></span>
		</td>
		</tr>
		</table>
		<?php submit_button( 'Save Settings' );?>
		</form>     
		<?php
	}
}

new VisualTextAdmin();
