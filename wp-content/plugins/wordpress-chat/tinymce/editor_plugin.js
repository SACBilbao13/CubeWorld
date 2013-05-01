(function() {
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('chat');

	tinymce.create('tinymce.plugins.ChatPlugin', {
		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished it's initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
		init : function(ed, url) {
			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceChat');
			ed.addCommand('mceChat', function() {
				ed.windowManager.open({
					file : ajaxurl + "?action=chatTinymceOptions",
					width : 450 + ed.getLang('chat.delta_width', 0),
					height : 380 + ed.getLang('chat.delta_height', 0),
					inline : 1
				}, {
					plugin_url : url // Plugin absolute URL
				});
			});

			// Register chat button
			ed.addButton('chat', {
				title : 'Insert chat',
				cmd : 'mceChat',
				image : url + '/images/chat.png'
			});

			// Add a node change handler, selects the button in the UI when a image is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('chat', n.nodeName == 'IMG');
			});
		},

		/**
		 * Creates control instances based in the incomming name. This method is normally not
		 * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
		 * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
		 * method can be used to create those.
		 *
		 * @param {String} n Name of the control to create.
		 * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
		 * @return {tinymce.ui.Control} New control instance or null if no control was created.
		 */
		createControl : function(n, cm) {
			return null;
		},

		/**
		 * Returns information about the plugin as a name/value array.
		 * The current keys are longname, author, authorurl, infourl and version.
		 *
		 * @return {Object} Name/value array containing information about the plugin.
		 */
		getInfo : function() {
			return {
				longname : 'Chat plugin',
				author : 'S H Mohanjith',
				authorurl : 'http://mohanjith.com',
				infourl : 'http://mohanjith.com/wordpress/chat',
				version : "1.0.1"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('chat', tinymce.plugins.ChatPlugin);
})();
