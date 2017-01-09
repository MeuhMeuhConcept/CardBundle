define(['jquery', 'jquery-ui', 'bootstrap', 'i18n/fr_FR'], function(
    $, ui, bs, lang
) {
    $.widget( "jtf.cardToolboxWidget", {
        widgetEventPrefix: 'card_toolbox_',
        enableToggleValid: true,
        enableValidation:true,
        options: {
            hide_validdate_action: false,
            status: {
                valid: 'btn-primary',
                draft: 'btn-warning'
            },
            removeStatusClass: 'btn-primary btn-warning btn-default'
        },
        _create: function() {

            this.element.addClass('btn-group');

            this.button = $('<button>')
                .attr('type', 'button')
                .addClass('btn')
                .addClass('btn-xs')
                .addClass('btn-default')
                .addClass('dropdown-toggle')
                .html(' <span class="caret"></span>')
                .attr('data-toggle', 'dropdown')
                .appendTo(this.element)
                .dropdown()
                ;

            this.button_text = $('<span>')
                .prependTo(this.button);

            this.ul = $('<ul>')
                .addClass('dropdown-menu')
                .appendTo(this.element)
                ;

            this.action_toggle_valid = $('<li><a href="#"><i class="fa fa-toggle-off" aria-hidden="true"></i> '+
                    lang.card.btn.toggle_valid+
                    '</a></li>')
                .appendTo(this.ul)
                .hide()
                ;

            this.action_toggle_draft = $('<li><a href="#"><i class="fa fa-toggle-on" aria-hidden="true"></i> '+
                    lang.card.btn.toggle_draft+'</a></li>')
                .appendTo(this.ul)
                .hide()
                ;

            this.action_validate = $('<li><a href="#"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> '+
                    lang.card.btn.validate+'</a></li>')
                ;

            if(!this.options.hide_validdate_action) {
                this.action_validate.appendTo(this.ul);
            }

        },
        enable: function() {
            this._super();

            this._on(this.action_toggle_draft, {
                'click': '_showDraft'
            });
            this._on(this.action_toggle_valid, {
                'click': '_showValid'
            });
            this._on(this.action_validate, {
                'click': '_validate'
            });
        },
        disable: function() {
            this._super();

            this._off(this.action_toggle_draft, 'click');
            this._off(this.action_toggle_valid, 'click');
        },
        _destroy: function() {
            this.disabled();

            this.button_text.remove();
            this.ul.remove();
            this.button.remove();

            this.element.removeClass('btn-group');
        },
        disableToggleValid: function() {
            this.enableToggleValid = false;
            this.action_toggle_valid.html('<a href="#">'+lang.card.btn.toggle_valid_disable+'</a>');
        },
        disableValidation: function() {
            this.enableValidation = false;
            this.action_validate.html('<a href="#">'+lang.card.btn.validation_disable+'</a>');
        },
        toggleStatus: function(status, validate) {
            this.button.removeClass(this.options.removeStatusClass);
            this.button_text.html(lang.card.status[status]);
            if (!validate) {
                this.button_text.append(' <span class="label label-danger">'+lang.card.validation.invalid+'</span>');
            }
            this.button.addClass(this.options.status[status]);

            if (status == 'draft') {
                this.action_toggle_valid.show();
                this.action_toggle_draft.hide();
            } else if (status == 'valid') {
                this.action_toggle_draft.show();
                this.action_toggle_valid.hide();
            }
        },
        _showDraft: function(event) {
            event.preventDefault();
            this._trigger("show_draft", event);
        },
        _showValid: function(event) {
            event.preventDefault();

            if (this.enableToggleValid) {
                this._trigger("show_valid", event);
            }
        },
        _validate: function(event) {
            event.preventDefault();
            if (this.enableValidation) {
                this._trigger("validate", event);
            }
        }
    });
});
