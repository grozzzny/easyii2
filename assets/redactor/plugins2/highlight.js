(function($)
{
    $.Redactor.prototype.highlight = function() {
        return {
            langs: {
                en: {
                    "highlight": "Highlight",
                    "code": "Code",
                    "highlighting": "Syntax highlighting for the Web"
                }
            },
            modals: {
                'highlight':
                    '<form action=""> \
                        <div class="form-item"> \
                            <label for="modal-highlight-code-input">## code ## <span class="req">*</span></label> \
                            <select id="modal-highlight-code-input" name="code"> \
                                <option selected="selected" value="html">html</option> \
                                <option value="php">php</option> \
                                <option value="css">css</option> \
                                <option value="javascript">javascript</option> \
                                <option value="bash">bash</option> \
                                <option value="java">java</option> \
                                <option value="json">json</option> \
                                <option value="less">less</option> \
                                <option value="markdown">markdown</option> \
                                <option value="scss">scss</option> \
                                <option value="sql">sql</option> \
                            </select> \
                        </div> \
                        <div class="form-item"> \
                            <label for="modal-highlight-input">## highlighting ## <span class="req">*</span></label> \
                            <textarea id="modal-highlight-input" name="highlight" style="height: 200px;"></textarea> \
                        </div> \
                    </form>'
            },
            init: function (app) {
                this.app = app;
                this.lang = app.lang;
                this.opts = app.opts;
                this.toolbar = app.toolbar;
                this.component = app.component;
                this.insertion = app.insertion;
                this.inspector = app.inspector;
                this.selection = app.selection;
            },
            // messages
            onmodal: {
                highlight: {
                    opened: function ($modal, $form) {
                        $form.getField('highlight').focus();

                        if (this.$currentItem) {
                            var code = decodeURI(this.$currentItem.attr('data-highlight-code'));
                            $form.getField('highlight').val(code);
                        }
                    },
                    insert: function ($modal, $form) {
                        var data = $form.getData();
                        this._insert(data);
                    }
                }
            },
            oncontextbar: function (e, contextbar) {
                var data = this.inspector.parse(e.target)
                if (!data.isFigcaption() && data.isComponentType('highlight')) {
                    var node = data.getComponent();
                    var buttons = {
                        "edit": {
                            title: this.lang.get('edit'),
                            api: 'plugin.highlight.open',
                            args: node
                        },
                        "remove": {
                            title: this.lang.get('delete'),
                            api: 'plugin.highlight.remove',
                            args: node
                        }
                    };

                    contextbar.set(e, node, buttons, 'bottom');
                }
            },
            onbutton: {
                highlight: {
                    observe: function (button) {
                        this._observeButton(button);
                    }
                }
            },

            // public
            start: function () {
                var obj = {
                    title: this.lang.get('highlight'),
                    api: 'plugin.highlight.open',
                    observe: 'highlight'
                };

                var $button = this.toolbar.addButton('highlight', obj);
                $button.setIcon('<i class="re-icon-widget"></i>');
            },
            open: function () {
                this.$currentItem = this._getCurrent();

                var options = {
                    title: this.lang.get('highlight'),
                    width: '600px',
                    name: 'highlight',
                    handle: 'insert',
                    commands: {
                        insert: {title: (this.$currentItem) ? this.lang.get('save') : this.lang.get('insert')},
                        cancel: {title: this.lang.get('cancel')}
                    }
                };

                this.app.api('module.modal.build', options);
            },
            remove: function (node) {
                this.component.remove(node);
            },

            // private
            _getCurrent: function () {
                var current = this.selection.getCurrent();
                var data = this.inspector.parse(current);
                if (data.isComponentType('highlight')) {
                    return this.component.build(data.getComponent());
                }
            },
            _insert: function (data) {
                this.app.api('module.modal.close');

                if (data.highlight.trim() === '') {
                    return;
                }

                //<pre><code class="nohighlight">...</code></pre>
                var code_elem = document.createElement('code').className(data.code);
                code_elem.innerHTML = data.highlight;

                var html = document.createElement('pre').appendChild(code_elem);

                var $component = this.component.create('highlight', html);
                $component.attr('data-highlight-code', encodeURI(data.highlight.trim()));
                this.insertion.insertHtml($component);

            },
            _isHtmlString: function (html) {
                return !(typeof html === 'string' && !/^\s*<(\w+|!)[^>]*>/.test(html));
            },
            _observeButton: function (button) {
                var current = this.selection.getCurrent();
                var data = this.inspector.parse(current);

                if (data.isComponentType('table')) button.disable();
                else button.enable();
            }
        };
    };
})(jQuery);



// (function($)
// {
//     $.Redactor.prototype.highlight.component = function() {
//         return {
//         mixins: ['dom', 'component'],
//         init: function(app, el)
//         {
//             this.app = app;
//
//             // init
//             return (el && el.cmnt !== undefined) ? el : this._init(el);
//         },
//         getData: function()
//         {
//             return {
//                 html: this._getHtml()
//             };
//         },
//
//         // private
//         _init: function(el)
//         {
//             if (typeof el !== 'undefined')
//             {
//                 var $node = $R.dom(el);
//                 var $figure = $node.closest('figure');
//                 if ($figure.length !== 0)
//                 {
//                     this.parse($figure);
//                 }
//                 else
//                 {
//                     this.parse('<figure>');
//                     this.html(el);
//                 }
//             }
//             else
//             {
//                 this.parse('<figure>');
//             }
//
//
//             this._initWrapper();
//         },
//         _getHtml: function()
//         {
//             var $wrapper = $R.dom('<div>');
//             $wrapper.html(this.html());
//             $wrapper.find('.redactor-component-caret').remove();
//
//             return $wrapper.html();
//         },
//         _initWrapper: function()
//         {
//             this.addClass('redactor-component');
//             this.attr({
//                 'data-redactor-type': 'highlight',
//                 'tabindex': '-1',
//                 'contenteditable': false
//             });
//         }
//     }
//     };
// })(jQuery);