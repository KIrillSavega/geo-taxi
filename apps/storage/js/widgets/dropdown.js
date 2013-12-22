(function(){
    var DropDownView = Backbone.View.extend({

        tagName: "select",
        className: "chzn-select",
        multiple: false,
        placeholder: 'Choose...',
        selected: null,

        attributes: function(){
            var selectName = this.options.name || 'select';
            return {
                name: selectName
            }
        },

        initialize: function(){
            this.render();
        },

        render: function() {
            this.$el.append($('<option>', {}));
            var self = this;
            if (this.options.selected){
                this.selected = this.options.selected;
            }
            if (this.options.multiple){
                this.multiple = this.options.multiple;
            }
            if (typeof(this.options.source) == 'string'){
                var ajaxOptions = {
                    success:function (data) {
                        self.buildDropdown(data);
                    }
                };
                if (this.options.ajaxOptions){
                    ajaxOptions = this.options.ajaxOptions;
                    ajaxOptions.success = function (data) {
                        self.buildDropdown(data);
                    }
                }
                ajaxOptions.url = this.options.source;
                jQuery.ajax(ajaxOptions);
            }else if(typeof(this.options.source) == 'object'){
                this.buildDropdown(this.options.source);
            }

            return this;
        },

        buildDropdown: function(data){
            var chosenOptions = {disable_search_threshold:10,allow_single_deselect:true};
            if(this.options.template){
                var templateFile = this.templates[this.options.template];
                chosenOptions.template = function(text, value, templateData){
                    return _.template(templateFile, {
                        text: text,
                        value: value,
                        templateData: templateData}
                    );
                };
                if(this.options.templateSelected){
                    var templateFileSelected = this.templates[this.options.templateSelected];
                    chosenOptions.templateSelected = function(text, value, templateData){
                        return _.template(templateFileSelected, {
                            text: text,
                            value: value,
                            templateData: templateData}
                        );
                    };
                }
                this.generateDOMForTemplate(data);
            }else{
                this.generateDOM(data);
            }
            if(this.multiple == true){
                this.$el.attr('multiple', true);
            }
            if(this.options.width){
                this.$el.attr('style', 'width: '+this.options.width+'px');
            }
            if(this.options.placeholder){
                this.$el.attr('data-placeholder', this.options.placeholder);
            }else{
                this.$el.attr('data-placeholder', this.placeholder);
            }

            $(this.options.domElement).html(this.$el);
            $("."+this.className).chosen(chosenOptions).trigger("liszt:updated");
            if(this.options.onSelect){
                if (typeof(this.options.onSelect) == "function") {
                    $(this.options.domElement).bind("change", this.options.onSelect );
                }
            }
        },

        generateDOM: function(data){
            for (var value in data){
                var attributes = {
                    value: value,
                    text: data[value]
                };
                if(this.selected != null){
                    if(value == this.selected){
                        attributes.selected = true;
                    }
                }
                this.$el.append($('<option>', attributes));
            }
        },

        generateDOMForTemplate: function(data){
            for(var i=0;i<data.length;i++){
                var attributes = {};
                for(var attribute in data[i]){
                    if((attribute != 'text') && (attribute != 'value')){
                        attributes['data-'+attribute] = data[i][attribute];
                    }else{
                        attributes[attribute] = data[i][attribute];
                    }
                }
                if(this.selected != null){
                    if(data[i].value == this.selected){
                        attributes.selected = true;
                    }
                }
                this.$el.append($('<option>', attributes));
            }
        }

    });

    ComcashShop.dropDown = DropDownView;

    return DropDownView;
})();