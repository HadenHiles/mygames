(function($) {
    $.fn.matoriaFileupload = function(options){
        var defaults = {
            showButton: true, //Show button or not
            showAnimator: true, //Show animator or not
            watermarkClass: "watermarkTextbox", //Class for watermarked textbox
            containerCss: "fakeContainer", //Class of container
            buttonText:   "Choose File", //Text of button control
            fileUploadClass: "file", //Class of fileUpload control
            textboxClass: "text", //Class of textbox control
            animatorText: "click here", //Text of animator
            buttonClass: "button", //Class of button control
            hoverClass: "hover" //Hover Class of textbox & button controls
        };
        var options = $.extend(defaults, options);
        
        return this.each(function(){
            var self = $(this),
		        o = options;
		    /* It shall be used for checking the mouse moving outside the boundries of textbox/button control */
            self.dimensions = {
                               width: 0, 
                               height: 0,
                               left: 0,
                               top: 0
                             };
            /*  
                These are used so that mouse is always centered at the browse button.
                IE doesn't open the file upload selection window upon clicking file upload textbox.
            */
            self.FILE_OFFSET = {
                                    X: 70,
                                    Y: 10
                                };
            self.timer = null;
            
            //Watermark text.
            self.watermarkText = self.attr("title");
            if(self.watermarkText != ""){
                self.showWatermark = true;
                self.removeAttr("title");
            }else{
                self.showWatermark = false;
            }
            
		    self.css({"opacity": 0}) //Setting opacity to 0
		        .addClass(o.fileUploadClass)
		        .attr("size", 1); //Setting size to 1 so that FILE_OFFSET's work properly accross browser.

            //Creating wrapper container.
		    self.wrapper = $("<div></div>").addClass(o.containerCss);
		    
		    //Creating button.
		    if(o.showButton){
		        self.button = $("<input>")
                    .attr("type", "button")
                    .addClass(o.buttonClass)
                    .val(o.buttonText);
		    }
		    //Creating textbox.
		    self.textbox = $("<input>")
                .attr({"type": "text", "readOnly" : true})
                .addClass(o.textboxClass + (self.showWatermark ? (' ' + o.watermarkClass) : ''))
                .val(self.showWatermark ? self.watermarkText : "");
            
            //Creating animator.
            if(o.showAnimator){
                self.animator = $("<span></span>")
                                .addClass("animator")
                                .text(o.animatorText);
            }
            //Constructing structure.
            self.wrapper.insertBefore(self).append(self, self.textbox)
            if(o.showAnimator){
                self.wrapper.append(self.animator);
            };
            if(o.showButton){
                self.wrapper.append(self.button);
            };
            //Setting top position of animator
            if(o.showAnimator){
                self.animator.css({top: (self.textbox.outerHeight() - self.animator.outerHeight())/2});
            }
            
            //Attaching events
		    self //File upload control
		    .bind("mousemove", {fileUpload: self, hoverClass: o.hoverClass}, function(event){_fMousemove(event)})
		    .bind("mouseleave", {fileUpload: self, hoverClass: o.hoverClass}, function(event){_fMouseleave(event)})
            .change(function(event){_fChange(event)})
            .nextAll() //Textbox & Button control
            .bind("mouseenter", {fileUpload: self, hoverClass: o.hoverClass}, function(event){_fMouseenter(event)})
            .bind("mousemove", {fileUpload: self, hoverClass: o.hoverClass}, function(event){_fMousemove(event)})
            .bind("mouseleave", {fileUpload: self, hoverClass: o.hoverClass}, function(event){_fMouseleave(event)});
        });
        /* MouseEnter event */
        function _fMouseenter(event){
            var self = event.data.fileUpload, //FileUpload control
                $this = jQuery(event.target); //Context control
            clearTimeout(self.timer); //Clearing the timer
            //Setting the dimensions with context
            self.dimensions = {
                               width: $this.outerWidth(), 
                               height: $this.outerHeight(),
                               left: $this.offset().left,
                               top: $this.offset().top
                             };
            _setFileUploadPosition(self, event, self.parent().offset(), self.FILE_OFFSET);
            $this.addClass(event.data.hoverClass); //Adding hover class to context
            if(options.showAnimator){
                self.animator.animate({top: -self.animator.outerHeight(true)}, {queue: false});
            }
        };
        /* MouseMove event */
        function _fMousemove(event){
            var self = event.data.fileUpload, //FileUpload control
                $this = jQuery(event.target), //Context control
                pageX = event.pageX,
                pageY = event.pageY;
            clearTimeout(self.timer); //Clearing the timer
            //Checking if mouse position is outside the context boundaries.
            if( pageX >= self.dimensions.left &&
                pageX <= (self.dimensions.left + self.dimensions.width) &&
                pageY >= self.dimensions.top &&
                pageY <= (self.dimensions.top + self.dimensions.height)){
                    _setFileUploadPosition(self, event, self.parent().offset(), self.FILE_OFFSET);
                }
            else{
                jQuery(self).css("left", -5000); //Setting position of File Upload control
                jQuery(self).nextAll(":input").removeClass(event.data.hoverClass); //Removing hover class to context
                if(options.showAnimator){
                    self.animator.animate({top: (self.textbox.outerHeight() - self.animator.outerHeight())/2}, {queue: false});
                }
            }
        };
        /* MouseLeavfe event */
        function _fMouseleave(event){
            var self = event.data.fileUpload, //FileUpload control
                $this = jQuery(event.target) //Context control
            //Setting timer to hiding of File Upload control
            self.timer = setTimeout(function(){
                        jQuery(self).css("left", -5000); //Setting position of File Upload control
                        jQuery(self).nextAll(":input").removeClass(event.data.hoverClass); //Removing hover class to context
                        if(options.showAnimator){
                            self.animator.animate({top: (self.textbox.outerHeight() - self.animator.outerHeight())/2}, {queue: false});
                        }
                    }, 300);
        };
        /* FileChange event */
        function _fChange(event){
            var self = $(event.target), //FileUpload control
                file = self.val().split("\\");
            
            self.next().val(file[file.length-1]); //Setting textbox control value to selected file.
            //Watermarking textbox
            if(self.showWatermark){
                if(self.val() == "")
                     self.nextAll("input[type=text]").val(self.watermarkText).addClass(options.watermarkClass);
                else
                    self.nextAll("input[type=text]").removeClass(options.watermarkClass);
            }
        }
        /* Setting File Upload control position */
        function _setFileUploadPosition(self, event, parentOffset, FILE_OFFSET){
            //Setting position of File Upload control
            jQuery(self).css({
                                left: event.pageX - parentOffset.left - FILE_OFFSET.X, 
                                top: event.pageY - parentOffset.top - FILE_OFFSET.Y
                            });
        };
    }
}(jQuery));