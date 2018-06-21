(function($)
{
	$.Redactor.prototype.highlight2 = function()
	{
		return {
			langs: {
				en: {
                    "highlight": "Highlight",
                    "code": "Code",
                    "highlighting": "Syntax highlighting for the Web"
				}
			},
			getTemplate: function()
			{
				return String()
				+ '<div class="modal-section" id="redactor-modal-highlight2-insert">'
					+ '<section>'
						+ '<label>' + this.lang.get('code') + '</label>'
						+ '<select id="modal-highlight-code-input" name="code">' +
						'<option selected="selected" value="html">html</option>' +
						'<option value="php">php</option>' +
						'<option value="css">css</option>' +
						'<option value="javascript">javascript</option>' +
						'<option value="bash">bash</option>' +
						'<option value="java">java</option>' +
						'<option value="json">json</option>' +
						'<option value="less">less</option>' +
						'<option value="markdown">markdown</option>' +
						'<option value="scss">scss</option>' +
						'<option value="sql">sql</option>' +
						'</select>'
					+ '</section>'
					+ '<section>'
						+ '<label>' + this.lang.get('highlighting') + '</label>'
						+ '<textarea id="redactor-insert-highlight2-area" style="height: 160px;"></textarea>'
					+ '</section>'
					+ '<section>'
						+ '<button id="redactor-modal-button-action">Insert</button>'
						+ '<button id="redactor-modal-button-cancel">Cancel</button>'
					+ '</section>'
				+ '</div>';
			},
			init: function()
			{
				var button = this.button.addAfter('image', 'codemirror', this.lang.get('highlight'));
				this.button.setIcon(button, '<i class="re-icon-html"></i>');
				this.button.addCallback(button, this.highlight2.show);
			},
			show: function()
			{
				this.modal.addTemplate('highlight2', this.highlight2.getTemplate());

				this.modal.load('highlight2', this.lang.get('highlight2'), 700);

				// action button
				this.modal.getActionButton().text(this.lang.get('insert')).on('click', this.highlight2.insert);
				this.modal.show();

				// focus
				if (this.detect.isDesktop())
				{
					setTimeout(function()
					{
						$('#redactor-insert-highlight2-area').focus();

					}, 1);
				}


			},
			insert: function()
			{
				var data = $('#redactor-insert-highlight2-area').val();
				var class_name = $('#modal-highlight-code-input').val();

                var $code = $('<code></code>').addClass(class_name).html(data);
                var $html = $('<pre></pre>').append($code);

                var html = $html.get(0).outerHTML;

				this.modal.close();
				this.placeholder.hide();

				// buffer
				this.buffer.set();

				// insert
				this.air.collapsed();
				this.insert.raw(html);

			}

		};
	};
})(jQuery);