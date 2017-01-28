/**
 * Visual Text Editor JS Object
 */
VisualTextEditorWidget = {

    currentContentId: '',
    currentEditorPage: '',
    wpFullOverlayOriginalZIndex: 0,

    /**
     * Show the editor
     * @param string contentId
     */
    showEditor: function(contentId) {
        jQuery('#visual-editor-overlay').show();
        jQuery('#visual-editor-widget-controller').show();

        this.currentContentId = contentId;
        this.currentEditorPage = ( jQuery('body').hasClass('wp-customizer') ? 'wp-customizer':'wp-widgets');

        if (this.currentEditorPage == "wp-customizer") {
            this.wpFullOverlayOriginalZIndex = parseInt(jQuery('.wp-full-overlay').css('zIndex'));
            jQuery('.wp-full-overlay').css({ zIndex: 49000 });
        }

        this.setEditorContent(contentId);
    },

    /**
     * Hide editor
     */
    hideEditor: function() {
        jQuery('#visual-editor-overlay').hide();
        jQuery('#visual-editor-widget-controller').hide();

        if (this.currentEditorPage == "wp-customizer") {
            jQuery('.wp-full-overlay').css({ zIndex: this.wpFullOverlayOriginalZIndex });
        }
    },

    /**
     * Set editor content
     */
    setEditorContent: function(contentId) {
        var editor = tinyMCE.EditorManager.get('visualeditorwidget');
        var content = jQuery('#'+ contentId).val();

        if (typeof editor == "object" && editor !== null) {
            editor.setContent(content);
        }
        jQuery('#visualeditorwidget').val(content);
    },

    /**
     * Update widget and close the editor
     */
    updateWidgetAndCloseEditor: function() {
        var editor = tinyMCE.EditorManager.get('visualeditorwidget');

        if (typeof editor == "undefined" || editor == null || editor.isHidden()) {
            var content = jQuery('#visualeditorwidget').val();
        }
        else {
            var content = editor.getContent();
        }

        jQuery('#'+ this.currentContentId).val(content);

        // customize.php
        if (this.currentEditorPage == "wp-customizer") {
            var widget_id = jQuery('#'+ this.currentContentId).closest('div.form').find('input.widget-id').val();
            var widget_form_control = wp.customize.Widgets.getWidgetFormControlForWidget( widget_id )
            widget_form_control.updateWidget();
        }

        // widgets.php
        else {
            wpWidgets.save(jQuery('#'+ this.currentContentId).closest('div.widget'), 0, 1, 0);
        }

        this.hideEditor();
    }

};


