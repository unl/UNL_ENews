var ajaxUpload = function() {
	/* Workaround for Webkit - the iframe onLoad event fires afterSubmit() three times on the first upload (subsequent uploads are ok)
	 * The first time through puts the iframe body contents (the image) into the parent window placeholder area, the second and third passes move the (empty) iframe body
	 * */
	var afterSubmitCalled = false;
	
	return {
		form : '',
		iframeID : 'ajaxTempIframe',
		urlAction : ENEWS_HOME + '?view=submit&ajaxupload=yes',
		targetElementID : 'upload_area',
		sampleLayoutImageID : 'sampleLayoutImage',
		loadingContent : '<img src="/wdn/templates_3.0/css/header/images/colorbox/loading.gif" />',

		remove : function(theVar) {
			var theParent = theVar.parentNode;
			theParent.removeChild(theVar);
		},

		addEvent : function(obj, evType, fn) {
			if (obj.addEventListener)
				obj.addEventListener(evType, fn, true);
			if (obj.attachEvent)
				obj.attachEvent('on'+evType, fn);
		},

		removeEvent : function(obj, type, fn) {
			if (obj.detachEvent) {
				obj.detachEvent('on'+type, fn);
			} else {
				obj.removeEventListener(type, fn, false);
			}
		},

		upload : function(form) {
			// Set a form var to later use for removing the form target attribute
			ajaxUpload.form = form;
			// Checking a proper form has been received and a defined target in the DOM exists
			var errorText  = "";
			if (form == null || typeof(form) == 'undefined') {
				errorText += "The form parameter does not exist.\n";
			} else if (form.nodeName.toLowerCase() != "form") {
				errorText += "The parameter is not a form.\n";
			}
			if (document.getElementById(ajaxUpload.targetElementID) == null) {
				errorText += "The target div does not exist.\n";
			}
			if (errorText.length > 0) {
				document.getElementById(ajaxUpload.targetElementID).innerHTML = "Error: " + errorText;
				return false;
			}

			// Create the iframe and append it to the DOM
			var iframe = document.createElement('iframe');
			iframe.setAttribute('id',ajaxUpload.iframeID);
			iframe.setAttribute('name',ajaxUpload.iframeID);
			iframe.setAttribute('width','0');
			iframe.setAttribute('height','0');
			iframe.setAttribute('border','0');
			iframe.setAttribute('style','width:0;height:0;border:none;');
			form.parentNode.appendChild(iframe);

			var afterSubmit = function() {
				if (!afterSubmitCalled) {
					afterSubmitCalled = true;
					ajaxUpload.removeEvent(document.getElementById(ajaxUpload.iframeID),'load', afterSubmit);
					// Create the js in the iframe that accepts the POST-back of the form and pushes that value to a hidden input in the submission form
					var cross = 'javascript:';
					cross += 'window.parent.document.getElementById("fileID").value=document.body.innerHTML;';
					cross += 'window.parent.WDN.jQuery("#fileID").change();';
					cross += 'void(0);';
					document.getElementById(ajaxUpload.iframeID).src = cross;
				}
			}

			// When the iframe is done being created call afterSubmit()
			ajaxUpload.addEvent(document.getElementById(ajaxUpload.iframeID),'load', afterSubmit);
			// Make alterations to the form to set the action URL and send the result to the iframe
			form.setAttribute('target',ajaxUpload.iframeID);
			form.setAttribute('action',ajaxUpload.urlAction);
			form.setAttribute('method','post');
			form.setAttribute('enctype','multipart/form-data');
			form.setAttribute('encoding','multipart/form-data');
			// Put in loading... placeholder where the image will ultimately appear on the page
			document.getElementById(ajaxUpload.targetElementID).innerHTML = ajaxUpload.loadingContent;
			// Submit the form!
			form.submit();
		},

		removeIframe : function() {
			// Remove the iframe from the DOM and remove targeting the iframe from the form
			ajaxUpload.remove(document.getElementById(ajaxUpload.iframeID));
			ajaxUpload.form.removeAttribute('target',ajaxUpload.iframeID);
			// Reset the flag
			afterSubmitCalled = false;
		}
	};
}();
