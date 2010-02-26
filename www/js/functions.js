WDN.jQuery(document).ready(function() {
	WDN.jQuery('ol.option_step a').click(function() {
		WDN.jQuery('#wdn_process_step1').slideToggle();
		if(WDN.jQuery(this).attr('id') == 'newsAnnouncement') { //the user has selected news, so hide the event date panel and show the news form
			WDN.jQuery('#enews h3').eq(1).hide();
			WDN.jQuery('#wdn_process_step3').slideToggle(function() {
				WDN.jQuery('#enews h3').eq(0).removeClass('highlighted');
				WDN.jQuery('#enews h3').eq(2).addClass('highlighted').append(' <span class="announceType">News Announcement</span>');
			});
		} else { //we have an event request
			WDN.jQuery('#wdn_process_step2').slideToggle(function() {
				WDN.jQuery('#enews h3').eq(0).removeClass('highlighted');
				WDN.jQuery('#enews h3').eq(1).addClass('highlighted');
			});
		}
		return false;
	});
	WDN.jQuery('#next_step3').click(function() {
		WDN.jQuery('#wdn_process_step2').slideToggle();
		WDN.jQuery('#wdn_process_step3').slideToggle(function() {
			WDN.jQuery('#enews h3').eq(1).removeClass('highlighted');
			WDN.jQuery('#enews h3').eq(2).addClass('highlighted').append('<span class="announceType">Event Announcement</span>');
		});
		return false;
	});
	WDN.jQuery('#enews h3').eq(0).css('cursor','pointer').click(backToStep1);
	WDN.jQuery('#enews h3').eq(1).css('cursor','pointer').click(backToStep2);
	//Update the sample layout
	WDN.jQuery('#title').keyup(function() {
		var demoTitle = WDN.jQuery(this).val();
		WDN.jQuery('#sampleLayout h4').text(function(index){
			return demoTitle;
		});
	});
	var characterLimit = 300;
	WDN.jQuery('#description').keyup(function() {
		var demoText = WDN.jQuery(this).val();
		WDN.jQuery(this).prev('label').children('span').children('strong').text(characterLimit - demoText.length);
		if ((characterLimit - demoText.length) < (characterLimit * .08)) {
			WDN.jQuery(this).prev('label').children('span').children('strong').addClass('warning');
		} else {
			WDN.jQuery(this).prev('label').children('span').children('strong').removeClass('warning');
		}
		if (demoText.length > characterLimit) {
			WDN.jQuery(this).val(demoText.substr(0,characterLimit));
		}
		WDN.jQuery('#sampleLayout p').text(function(index){
			return demoText;
		});
	});
	
	//manager tables
	WDN.jQuery(".checkall").click(function() {
		WDN.jQuery('.storylisting input[type=checkbox]').attr('checked', true);
		return false;
	});
	WDN.jQuery(".uncheckall").click(function() {
		WDN.jQuery('.storylisting input[type=checkbox]').attr('checked', false);
		return false;
	});
	
	//the newsletter creation page <- to be moved to it's own file/plugin
	WDN.jQuery('.dragItem').draggable({ 
		revert: 'invalid',
		snap: '.newsColumn',
		snapMode : 'inner',
		connectToSortable: '.newsColumn',
		//helper: 'clone',
		opacity: 0.45
	});
	WDN.jQuery('#newsColumn1, #newsColumn2, #newsColumnIntro').sortable({
		//revert: true,
		connectWith: '.newsColumn',
		scroll: true,
		delay: 250,
		opacity: 0.45,
		tolerance: 'pointer',
		stop: function(event, ui){
			saveStoryOrder();
		}
	});
	WDN.jQuery('#newsColumn1, #newsColumn2, #newsColumnIntro').disableSelection();
	WDN.jQuery('.newsColumn').droppable({
		drop: function(event, ui) {
			ui.draggable.addClass('story').removeAttr('style').removeClass('dragItem');
			WDN.jQuery.post(WDN.jQuery(this).find('form').attr('action'), WDN.jQuery(this).find('form').serialize());
		}
	});
});
function saveStoryOrder() { //this function determines the order of the stories and sends it to the DB.
	WDN.jQuery('#newsColumn1, #newsColumn2, #newsColumnIntro').sortable('refresh');
	var resultIntro = WDN.jQuery('#newsColumnIntro').sortable('toArray');
	var result1 = WDN.jQuery('#newsColumn1').sortable('toArray');
	var result2 = WDN.jQuery('#newsColumn2').sortable('toArray');
	for(var i = 0; i<resultIntro.length; i++) {
		WDN.jQuery('#'+resultIntro[i]+' form input[name=sort_order]').attr('value', i*2);
		WDN.jQuery.post(WDN.jQuery('#'+resultIntro[i]+' form').attr('action'), WDN.jQuery('#'+resultIntro[i]+' form').serialize());
	}
	for(i = 0; i<result1.length; i++) {
		WDN.jQuery('#'+result1[i]+' form input[name=sort_order]').attr('value', i*2+1);
		WDN.jQuery.post(WDN.jQuery('#'+result1[i]+' form').attr('action'), WDN.jQuery('#'+result1[i]+' form').serialize());
	}
	for(i = 0; i<result2.length; i++) {
		WDN.jQuery('#'+result2[i]+' form input[name=sort_order]').attr('value', i*2+2);
		WDN.jQuery.post(WDN.jQuery('#'+result2[i]+' form').attr('action'), WDN.jQuery('#'+result2[i]+' form').serialize());
	}
};
function backToStep1 () {
	WDN.jQuery('#wdn_process_step2').slideUp();
	WDN.jQuery('#wdn_process_step3').slideUp();
	WDN.jQuery('#wdn_process_step1').slideDown();
	WDN.jQuery('#enews h3').eq(2).removeClass("highlighted");
	WDN.jQuery('#enews h3').eq(1).removeClass("highlighted");
	WDN.jQuery('#enews h3').eq(0).addClass("highlighted");
	WDN.jQuery('#enews h3 span.announceType').remove();
	WDN.jQuery('#enews h3').show();
};
function backToStep2 () {
	WDN.jQuery('#wdn_process_step1').slideUp();
	WDN.jQuery('#wdn_process_step3').slideUp();
	WDN.jQuery('#wdn_process_step2').slideDown();
	WDN.jQuery('#enews h3').eq(2).removeClass("highlighted");
	WDN.jQuery('#enews h3').eq(0).removeClass("highlighted");
	WDN.jQuery('#enews h3').eq(1).addClass("highlighted");
	WDN.jQuery('#enews h3 span.announceType').remove();
	WDN.jQuery('#enews h3').show();
};



/* JS from events for controlling manager actions 
 **********************************/
 

function getElementsByClassName(oElm, strTagName, strClassName){
    var arrElements = (strTagName == "*" && oElm.all)? oElm.all : oElm.getElementsByTagName(strTagName);
    var arrReturnElements = new Array();
    strClassName = strClassName.replace(/\-/g, "\\-");
    var oRegExp = new RegExp("(^|\\s)" + strClassName + "(\\s|$)");
    var oElement;
    for(var i=0; i<arrElements.length; i++){
        oElement = arrElements[i];      
        if(oRegExp.test(oElement.className)){
            arrReturnElements.push(oElement);
        }
    }
    return (arrReturnElements);
}

/**
 * Will show or hide an element with the given ID.
 */
function showHide(e)
{
   document.getElementById(e).style.display=(document.getElementById(e).style.display=="block")?"none":"block";
   return false;
}


/**
 * Namespace for manager javascript.
 */
var manager = function() {
    return {
        list : 'unset',
        eventselected : false,
        /* Updates elements to which actions can be selected.  */
        updateActionMenus : function(sel) {
            sel.selectedIndex = 0;
            if (manager.anEventIsSelected()) {
                if (manager.list == 'approved' || manager.list == 'archived') {
                    sel[1].disabled = 'disabled';
                    sel[2].disabled = null;
                    sel[4].disabled = null;
                } else if (manager.list == 'search') {
                    sel[1].disabled = null;
                    sel[2].disabled = null;
                    sel[4].disabled = 'disabled';
                } else {
                    sel[1].disabled = null;
                    sel[2].disabled = 'disabled';
                    sel[4].disabled = null;
                }
                sel[3].disabled = null;
            } else {
                sel[1].disabled = 'disabled';
                sel[2].disabled = 'disabled';
                sel[3].disabled = 'disabled';
                sel[4].disabled = 'disabled';
            }
        },
        
        /** Determines if an event is currently selected. */
        anEventIsSelected : function() {
            return manager.eventselected;
        },
        
        /* This function is called when an action is selected within an event listing */
        actionMenuChange  : function(sel) {
            switch(sel[sel.selectedIndex].value) {
            case 'posted':
            case 'archived':
                var button = document.getElementById('moveto_posted');
                button.click();
                break;
            case 'pending':
                var button = document.getElementById('moveto_pending');
                button.click();
                break;
            case 'recommend':
                var form = document.getElementById('formlist');
                form.action = '?action=recommend';
                form.submit();
                break;
            case 'delete':
                var button = document.getElementById('delete_event');
                button.click();
                break;
            }
        }
    };
}();


function checknegate(id){
    checkevent(id);
}

function highlightLine(l,id) {
    animation(l,id);    
    checkevent(id);
    checkInput();
}

function animation(l,id){
    var TRrow = "row" + id;
    var input = l.getElementsByTagName('input')[0];
    try {
        if (input.checked == true){
            if(!l.className){
                Spry.Effect.Highlight(TRrow,{duration:400,from:'#ffffcc',to:'#ffffff',restoreColor: '#ffffff',toggle: false});
            }
            else{
                Spry.Effect.Highlight(TRrow,{duration:400,from:'#ffffcc',to:'#e8f5fa',restoreColor: '#e8f5fa',toggle: false});
            } 
        } else {
            if(!l.className){
                Spry.Effect.Highlight(TRrow,{duration:400,from:'#ffffff',to:'#ffffcc',restoreColor: '#ffffcc',toggle: false});
            }
            else{
                Spry.Effect.Highlight(TRrow,{duration:400,from:'#e8f5fa',to:'#ffffcc',restoreColor: '#ffffcc',toggle: false});
            } 
            //bring back uncheck all button
            var inputUncheck = getElementsByClassName(document, "a", "uncheckall");
            inputUncheck[0].style.display = 'inline';
        }
    } catch(e) {}
}

function checkevent(id) {
    try {
        var checkSet = eval("document.formlist.story_" + id);
        checkSet.checked = !checkSet.checked
    } catch(e) {}
}

function updateRow(){

    var rowT = document.getElementsByTagName('tr');
    for (var i=0; i< rowT.length; i++)
        {
            if(rowT[i].className.indexOf('updated') >= 0){
                if(rowT[i].className.indexOf('alt') >= 0){
                Spry.Effect.Highlight(rowT[i],{duration:2000,from:'#FAFAB7',to:'#e8f5fa',restoreColor: '#e8f5fa',toggle: false});
                }
                else{
                Spry.Effect.Highlight(rowT[i],{duration:2000,from:'#FAFAB7',to:'#ffffff',restoreColor: '#ffffff',toggle: false});                   
                }
            }
        }   

} 

function requiredField(){
    var fieldset = document.getElementsByTagName('fieldset');
    var lastrequired = getElementsByClassName(document, "span", "required");
    try {
        //alert(lastrequired.length);
        lastrequired[lastrequired.length - 1].id = 'lastfieldset';
        
        for(var i=0; i<fieldset.length; i++){
            //var divrequired = getElementsByClassName(fieldset[i], "div", "reqnote");
            var spanrequired = getElementsByClassName(fieldset[i], "span", "required");
            if(spanrequired.length < 2){
                if (spanrequired.length > 0 && spanrequired[0].parentNode.nextSibling.childNodes.length > 0){
                    spanrequired[0].parentNode.nextSibling.childNodes[0].style.background = '#f8e6e9';
                }
            } else {
                for(var c = 0, p = spanrequired.length; c<p; c++){
                    if (spanrequired.length > 0 && spanrequired[c].parentNode.nextSibling.childNodes.length > 0){
                        spanrequired[c].parentNode.nextSibling.childNodes[0].style.background = '#f8e6e9';
                    }
                }
            }
        }
    } catch(e) {}
}

/*safari fixes*/
function showIsAppleWebKit() {
    // String found if this is a AppleWebKit based product
    var kitName = "applewebkit/";
    var tempStr = navigator.userAgent.toLowerCase();
    var pos = tempStr.indexOf(kitName);
    var isAppleWebkit = (pos != -1);
    
    if (isAppleWebkit) {
        var fieldObj = getElementsByClassName(document, "fieldset", "d__header___class"); 
        fieldObj[0].style.marginTop = '-10px';  
    } else {
       var eventLoc = document.getElementById('eventlocationheader');
       if (eventLoc.getElementsByTagName('table')[0]) {
           // Do nothing... table of existing eventdatetimes.
       } else {
            eventLoc.getElementsByTagName('label')[0].style.display = 'none';
        }
    }
}

function hideField() {
    try {
        var id = document.getElementById('optionaldetailsheader');
        var formContainer = id.getElementsByTagName('ol');
        createButton('Click to add additional details', id, formHide, 'formShow')
        formContainer[0].style.display='none';
        
      
        //fix some layout problem at the same time
        //var eventNewLoc = document.getElementById('__reverseLink_eventdatetime_event_idlocation_id_1__subForm__div');
        //eventNewLoc.className = 'newlocation';
        var eventBr = document.getElementById('__header__');
        eventBr.getElementsByTagName('br')[1].style.display = 'none';
        
        var eventLi = eventBr.getElementsByTagName('li')[1];
        eventLi.className='consider';
        showIsAppleWebKit();
    } catch(e) {}
}

function formHide(){
    var id = document.getElementById('optionaldetailsheader');
    var formContainer = id.getElementsByTagName('ol');
    formContainer[0].style.display=(formContainer[0].style.display=="block")?"none":"block";
    var linkId = document.getElementById('formShow');
    linkId.childNodes[0].nodeValue = (linkId.childNodes[0].nodeValue=="Hide Form")?"Click to add additional details":"Hide Form";
    return false;
}

function createButton(linktext, attachE, actionFunc, idN){
    var morelink = document.createElement("a");
    morelink.style.display = 'inline';
    var text = document.createTextNode(linktext);
    morelink.id=idN;
    morelink.href = '#';
    morelink.onclick = actionFunc;
    morelink.appendChild(text);
    attachE.appendChild(morelink);
}

/**
 * Will set all checkboxes under the element with the given ID
 * to the value passed in val.
 */
function setCheckboxes(formid,val)
{
    //try {
        var inputUncheck = getElementsByClassName(document, "a", "uncheckall");
        var inputCheck = getElementsByClassName(document, "a", "checkall");
        var f = document.getElementById(formid);
        var checks = f.getElementsByTagName('input');
        for (var i=0;i<checks.length;i++) {
            var TDcell = checks[i].parentNode.parentNode;
            if (val) {
                checks[i].checked = true;
                if (formid != 'unl_ucbcn_user') {
                    //Spry.Effect.Highlight(TDcell,{duration:400,from:'#FFFFFF',to:'#ffffcc',restoreColor:'#ffffcc',toggle: false});
                }
                manager.eventselected = true;
            //  inputUncheck[0].style.display = 'inline';
            } else {
                checks[i].checked = false;
                if (formid != 'unl_ucbcn_user'){
                    if(TDcell.className.indexOf('alt') >= 0){
                        Spry.Effect.Highlight(TDcell,{duration:400,from:'#FAFAB7',to:'#e8f5fa',restoreColor:'#e8f5fa',toggle: false});
                    }
                    else{
                      //  Spry.Effect.Highlight(TDcell,{duration:400,from:'#FAFAB7',to:'#ffffff',restoreColor:'#ffffff',toggle: false});                    
                    }
                }
            manager.eventselected = false;
            //    inputCheck[0].className += 'eventselected';
            //  inputUncheck[0].style.display = 'none';
            }
        }
    
    //} catch(e) {}
}

//we need to constantly check whether any of the inputs are selected
function checkInput(){
    var flag = 0;
    var inputUncheck = getElementsByClassName(document, "a", "uncheckall");
    var inputCheck = getElementsByClassName(document, "a", "checkall");
    var f = document.formlist;
    var checks = f.getElementsByTagName('input');
    
    for(var k=0;k<checks.length;k++){
        if(checks[k].checked == true){
            flag = 1;
        }
    }
    if (flag == 0){
        //inputUncheck[0].style.display = 'none';
    }
    else{
        //inputCheck[0].className += 'eventselected';
        manager.eventselected = true;
    }
}

 