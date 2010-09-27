var ajaxUpload = function() {
	return {
		url_action : "?view=submit&ajaxupload=yes",
		id_element : "upload_area",
		sampleLayoutImage : "sampleLayoutImage",
		message : 'Click and drag on the image above to select a thumbnail',
		html_show_loading : '<img src="/wdn/templates_3.0/css/header/images/colorbox/loading.gif" />',
		html_error_http : "Error....",
		
		remove : function(theVar) {
			var theParent = theVar.parentNode;
			theParent.removeChild(theVar);
		},
		addEvent : function(obj, evType, fn) {
			if (obj.addEventListener)
			    obj.addEventListener(evType, fn, true);
			if (obj.attachEvent)
			    obj.attachEvent("on"+evType, fn);
		},
		removeEvent : function(obj, type, fn) {
			if (obj.detachEvent) {
				obj.detachEvent('on'+type, fn);
			} else {
				obj.removeEventListener(type, fn, false);
			}
		},
		isWebKit : function() {
			return RegExp(" AppleWebKit/").test(navigator.userAgent);
		},
		upload : function(form) {
			form = typeof(form)=="string"?document.getElementById(form):form;
			var erro = "";
			if (form == null || typeof(form) == "undefined") {
				erro += "The form of 1st parameter does not exists.\n";
			} else if (form.nodeName.toLowerCase() != "form") {
				erro += "The form of 1st parameter its not a form.\n";
			}
			if (document.getElementById(ajaxUpload.id_element) == null) {
				erro += "The element of 3rd parameter does not exists.\n";
			}
			if (erro.length > 0) {
				alert("Error in call ajaxUpload:\n" + erro);
				return;
			}
			var iframe = document.createElement("iframe");
			iframe.setAttribute("id","ajax-temp");
			iframe.setAttribute("name","ajax-temp");
			iframe.setAttribute("width","0");
			iframe.setAttribute("height","0");
			iframe.setAttribute("border","0");
			iframe.setAttribute("style","width: 0; height: 0; border: none;");
			form.parentNode.appendChild(iframe);
			window.frames['ajax-temp'].name="ajax-temp";
			var doUpload = function() {
				ajaxUpload.removeEvent(document.getElementById('ajax-temp'),"load", doUpload);
				var cross = "javascript: ";
				cross += "window.parent.document.getElementById('"+ajaxUpload.sampleLayoutImage+"').innerHTML = document.body.innerHTML;";
				cross += "window.parent.document.getElementById('"+ajaxUpload.id_element+"').innerHTML = document.body.innerHTML + '<span>"+ajaxUpload.message+"</span>'; window.parent.submission.setImageCrop(); void(0);";
				document.getElementById(ajaxUpload.id_element).innerHTML = ajaxUpload.html_error_http;
				document.getElementById('ajax-temp').src = cross;
				if (ajaxUpload.isWebKit()) {
					ajaxUpload.remove(document.getElementById('ajax-temp'));
		        } else {
		        	setTimeout(function(){ ajaxUpload.remove(document.getElementById('ajax-temp'))}, 250);
		        }
		    }
			ajaxUpload.addEvent(document.getElementById('ajax-temp'),"load", doUpload);
			form.setAttribute("target","ajax-temp");
			form.setAttribute("action",ajaxUpload.url_action);
			form.setAttribute("method","post");
			form.setAttribute("enctype","multipart/form-data");
			form.setAttribute("encoding","multipart/form-data");
			if (ajaxUpload.html_show_loading.length > 0) {
				document.getElementById(ajaxUpload.id_element).innerHTML = ajaxUpload.html_show_loading;
			}
			form.submit();
		}

	};
}();
