(function(){

    var MarkItUpEditor = Backbone.View.extend({

        el: 'body',
        events: {
           'click #upload-file-btn': 'startUploading',
           'hidden #uploaderModal': 'removeUploaderModalWindow',
           'hidden #galleryModal': 'removeGalleryModalWindow',
           'click #choose-file': 'chooseFile',
           'change #file-input': 'changedFileInput',
           'click #show-gallery-btn': 'showGalleryModalWindow',
           'click #editor-gallery-items li div.img-wrapper': 'pasteImageToEditor',
           'click #editor-gallery-items .delete-gallery-image': 'deleteGalleryImage'
        },

        templates: {
            uploaderTemplate: '/libs/backbone.markitup.addon/template/uploaderModal.html',
            galleryTemplate: '/libs/backbone.markitup.addon/template/galleryModal.html'
        },

        uploaderErrorMsg: 'An error has occurred. Sorry, but we are currently experiencing technical problems with upload. Please try again later',
        unsupportedFileMsg: 'Selected file type is not allowed',
        requiredFileMsg: 'Please select file',
        allowedFileTypes: ['image'],
        allowedFileExtensions: ['jpg', 'jpeg', 'png'],
        mylatesttap: null,

        initialize: function(){
            var self = this;
            var settings = {
                onShiftEnter:	{keepDefault:false, replaceWith:'<br />\n'},
                onCtrlEnter:	{keepDefault:false, openWith:'\n<p>', closeWith:'</p>\n'},
                onTab:			{keepDefault:false, openWith:'	 '},
                markupSet: [
                    {name:'Heading 1', key:'1', openWith:'<h1(!( class="[![Class]!]")!)>', closeWith:'</h1>', placeHolder:'Your title here...' },
                    {name:'Heading 2', key:'2', openWith:'<h2(!( class="[![Class]!]")!)>', closeWith:'</h2>', placeHolder:'Your title here...' },
                    {name:'Heading 3', key:'3', openWith:'<h3(!( class="[![Class]!]")!)>', closeWith:'</h3>', placeHolder:'Your title here...' },
                    {name:'Paragraph', openWith:'<p(!( class="[![Class]!]")!)>', closeWith:'</p>' },
                    {separator:'---------------' },
                    {name:'Bold', key:'B', openWith:'(!(<strong>|!|<b>)!)', closeWith:'(!(</strong>|!|</b>)!)' },
                    {name:'Italic', key:'I', openWith:'(!(<em>|!|<i>)!)', closeWith:'(!(</em>|!|</i>)!)' },
                    {name:'Stroke through', key:'S', openWith:'<del>', closeWith:'</del>' },
                    {separator:'---------------' },
                    {name:'Ul', openWith:'<ul>\n', closeWith:'</ul>\n' },
                    {name:'Ol', openWith:'<ol>\n', closeWith:'</ol>\n' },
                    {name:'Li', openWith:'<li>', closeWith:'</li>' },
                    {separator:'---------------' },
                    {name:'Picture', key:'P', replaceWith:'<img src="[![Source:!:http://]!]" alt="[![Alternative text]!]" />' },
                    {name:'Link', key:'L', openWith:'<a href="[![Link:!:http://]!]"(!( title="[![Title]!]")!)>', closeWith:'</a>', placeHolder:'Your text to link...' },
                    {separator:'---------------' },
                    {name:'Upload', className:'upload', call: function(){self.showUploadContentModalWindow();} },
                    {name:'Gallery', className:'gallery', call: function(){self.showGalleryModalWindow();} },
                    {separator:'---------------' },
                    {name:'Clean', className:'clean', replaceWith:function(markitup) { return markitup.selection.replace(/<(.*?)>/g, "") } },
                    {name:'Preview', className:'preview', call:'preview' }
                ]
            };
            this.$el.find('.redactor').markItUp(settings);
            this.loadTemplates();
        },

        loadTemplates: function(){
            this.uploaderTemplate = '<div id="uploaderModal" class="modal hide fade">'
                +'<div class="modal-header">'
                +'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>'
                +'<h3>Uploader</h3>'
                +'</div>'
                +'<div class="modal-body">'
                +'<p><button id="choose-file" class="btn">Select File</button></p>'
                +'<input type="file" id="file-input" style="display: none;">'
                +'<div class="error"></div>'
                +'<div id="filename" style="font-weight: bold;"></div>'
                +'<div class="progress" style="display: none;">'
                +'<div class="bar bar-success" style="width: 0;"></div>'
                +'</div>'
                +'</div>'
                +'<div class="modal-footer">'
                +'<a data-dismiss="modal" class="btn">Close</a>'
                +'<a id="show-gallery-btn" data-dismiss="modal" class="btn btn-primary">Show Gallery</a>'
                +'<a id="upload-file-btn" class="btn btn-primary">Upload</a>'
                +'</div>'
                +'</div>';

            this.galleryTemplate = '<div id="galleryModal" style="width: 600px;" class="modal hide fade">'
                +'<div class="modal-header">'
                +'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>'
                +'<h3>Gallery</h3>'
                +'</div>'
                +'<div class="modal-body">'
                +'<ul id="editor-gallery-items"></ul>'
                +'</div>'
                +'<div class="modal-footer">'
                +'<a data-dismiss="modal" class="btn">Close</a>'
                +'</div>'
                +'</div>';
        },

        showUploadContentModalWindow: function(){
            this.$el.append(this.uploaderTemplate);
            this.$el.find('#uploaderModal').modal({});
        },

        showGalleryModalWindow: function(){
            this.$el.append(this.galleryTemplate);
            this.$el.find('#galleryModal').modal({});
            this.loadGalleryImages();
        },

        loadGalleryImages: function(){
            var self = this;
            $.ajax({
                type: 'GET',
                url: ComcashAdmin.url.getGalleryImages,
                beforeSend: function(){

                },
                success: function(response) {
                    if(response.status == 200){
                        self.$el.find('#editor-gallery-items').append('<div data-image-url="'+response.imageUrl+'"></div>');
                        self.renderGalleryImages(response.data);
                    }else{
                        self.$el.find('#editor-gallery-items').html(response.message);
                    }
                },
                complete:function() {

                }
            });

        },

        renderGalleryImages: function(files){
            var gallery = this.$el.find('#editor-gallery-items');
            var imgUrl = this.$el.find('[data-image-url]').attr('data-image-url');
            var itemsList = '';
            for(var i=0;files.length>i;i++){
                var galleryItem = '<li><div class="img-wrapper">';
                galleryItem += '<img data-filename="'+files[i]+'" src="'+imgUrl+'/thumb_'+files[i]+'">';
                galleryItem += '</div><div class="del-wrapper">';
                galleryItem += '<button class="delete-gallery-image btn btn-mini btn-danger" type="button"><i class="icon-trash icon-white"></i> delete</button>';
                galleryItem += '</div></li>';
                itemsList += galleryItem;
            }
            gallery.append(itemsList);
        },

        removeUploaderModalWindow: function(){
            this.$el.find('#uploaderModal').remove();
        },

        removeGalleryModalWindow: function(){
            this.$el.find('#galleryModal').remove();
        },

        getFileInfoFromInput: function(){
            var file = this.$el.find('#file-input')[0].files[0];
            if(file){
                return file;
            }
        },

        chooseFile: function(){
            var fileInput = this.$el.find('#file-input');
            if(fileInput){
                fileInput.click();
            }
        },

        changedFileInput: function(){
            this.$el.find('#uploaderModal .error').html('');
            this.$el.find('#uploaderModal .progress').hide();
            this.resetUploaderProgressBar();
            var file = this.getFileInfoFromInput();
            if(file){
                this.$el.find('#filename').html(file.name);
            }else{
                this.$el.find('#filename').html('');
            }
        },

        startUploading: function(){
            var self = this;
            var disabled = this.$el.find('#upload-file-btn').attr('disabled');
            if(!disabled){
                var uploaderModalErrorEl = this.$el.find('#uploaderModal .error');
                uploaderModalErrorEl.html('');
                var file = this.getFileInfoFromInput();
                if(file){
                    var type = file.type.split('/')[0];
                    var extension = file.name.split('.').pop().toLowerCase();
                    if ($.inArray(type, this.allowedFileTypes) == -1 || $.inArray(extension, this.allowedFileExtensions) == -1) {
                        uploaderModalErrorEl.html(this.unsupportedFileMsg);
                        return;
                    }
                    this.uploadFile(file);
                }else{
                    uploaderModalErrorEl.html(this.requiredFileMsg);
                    return;
                }
            }
        },

        uploadFile: function(file){
            var self = this;
            var formData = new FormData();
            formData.append('file', file);
            $.ajax({
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt){
                        if (evt.lengthComputable) {
                            var percentComplete = parseInt(evt.loaded / evt.total * 100, 10);
                            if(percentComplete == 100){
                                percentComplete = 99;
                            }
                            self.$el.find('#uploaderModal .progress .bar').css('width',percentComplete+'%').html(percentComplete+'%');
                        }
                    }, false);
                    return xhr;
                },
                url: ComcashAdmin.url.uploadFile,
                type: 'POST',
                dataType:'json',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function(xhr) {
                    self.resetUploaderProgressBar();
                    self.$el.find('#uploaderModal .progress').show();
                    self.$el.find('#choose-file').attr('disabled','disbaled');
                    self.$el.find('#upload-file-btn').attr('disabled','disbaled');
                },
                success: function(data, text, xhr) {
                    self.$el.find('#choose-file').removeAttr('disabled');
                    self.$el.find('#upload-file-btn').removeAttr('disabled');
                    if(data.status == 200){
                        self.$el.find('#filename').html(data.message);
                        self.$el.find('#file-input').val('');
                        self.$el.find('#uploaderModal .progress .bar').css('width','100%').html('100%');
                    }else{
                        self.$el.find('#uploaderModal .error').html(data.message);
                    }
                },
                error: function() {
                    self.$el.find('#choose-file').removeAttr('disabled');
                    self.$el.find('#upload-file-btn').removeAttr('disabled');
                }
            });
        },

        resetUploaderProgressBar: function(){
            var progressBar = this.$el.find('#uploaderModal .progress');
            progressBar.find('.bar').css('width',0).html('');
        },

        pasteImageToEditor: function(e){
            var now = new Date().getTime();
            var timesince = now - this.mylatesttap;
            if((timesince < 600) && (timesince > 0)){
                var curEl = $(e.currentTarget);
                var img = curEl.find('img');
                var imgUrl = this.$el.find('[data-image-url]').attr('data-image-url');
                if(img){
                    $.markItUp(
                        { replaceWith: '<img src="'+imgUrl+'/'+img.attr('data-filename')+'" alt="" />' }
                    );
                    this.$el.find('#galleryModal').modal('hide');
                }
            }
            this.mylatesttap = new Date().getTime();
        },

        deleteGalleryImage: function(e){
            var curEl = $(e.currentTarget);
            var img = curEl.parent().parent().find('img');
            if(img){
                $.ajax({
                    type: 'POST',
                    url: ComcashAdmin.url.deleteGalleryImage,
                    data: {
                        filename: img.attr('data-filename')
                    },
                    success: function(response) {
                        if(response.status == 200){
                            curEl.parent().parent().remove();
                        }
                    }
                });
            }
        }

    });

    ComcashAdmin.widgets.markItUp = MarkItUpEditor;
})();
