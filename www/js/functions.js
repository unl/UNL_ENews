WDN.jQuery(document).ready(function() {	
	//manager tables
	WDN.jQuery(".checkall").click(function() {
		WDN.jQuery('.storylisting input[type=checkbox]').attr('checked', true);
		checkInput();
		return false;
	});
	WDN.jQuery(".uncheckall").click(function() {
		WDN.jQuery('.storylisting input[type=checkbox]').attr('checked', false);
		manager.storyselected = false;
		checkInput();
		return false;
	});
	WDN.jQuery(".storylisting input[type=checkbox]").click(function() { 
		checkInput();
		return this;
	});
	
	//the newsletter creation page <- to be moved to it's own file/plugin
	WDN.jQuery('#maincontent table .story').hover(
			function(){
				WDN.jQuery(this).children('.storyTools').delay(500).fadeIn(800);
			},
			function() {
				WDN.jQuery(this).children('.storyTools').fadeOut(200);
			}
	);
	WDN.jQuery('#maincontent table .story').focus(function() {
		WDN.jQuery(this).children('.storyTools').hide();
	});
	WDN.jQuery('a.edit').click(function(){ // we have clicked the edit story icon
		WDN.jQuery(this).parent().siblings('p').before("<textarea>"+WDN.jQuery(this).parent().siblings('p').text()+"</textarea>");
		WDN.jQuery(this).parent().hide();
		return false;
	
	});
	WDN.jQuery('.dragItem').draggable({ 
		revert: 'invalid',
		snap: '.newsColumn',
		snapMode : 'inner',
		connectToSortable: '.newsColumn',
		helper: 'original',
		opacity: 0.45
	});
	WDN.jQuery('#newsColumn1, #newsColumn2, #newsColumnIntro').sortable({
		revert: true,
		connectWith: '.newsColumn',
		scroll: true,
		delay: 250,
		opacity: 0.45,
		tolerance: 'pointer',
		helper: 'clone',
		start: function(event, ui){
			WDN.jQuery(ui.item).children('.storyTools').hide();
		},
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
		WDN.jQuery('#'+resultIntro[i]+' form input[name=sort_order]').attr('value', i*3+1);
		WDN.jQuery.post(WDN.jQuery('#'+resultIntro[i]+' form').attr('action'), WDN.jQuery('#'+resultIntro[i]+' form').serialize());
	}
	for(i = 0; i<result1.length; i++) {
		WDN.jQuery('#'+result1[i]+' form input[name=sort_order]').attr('value', i*3+2);
		WDN.jQuery.post(WDN.jQuery('#'+result1[i]+' form').attr('action'), WDN.jQuery('#'+result1[i]+' form').serialize());
	}
	for(i = 0; i<result2.length; i++) {
		WDN.jQuery('#'+result2[i]+' form input[name=sort_order]').attr('value', i*3);
		WDN.jQuery.post(WDN.jQuery('#'+result2[i]+' form').attr('action'), WDN.jQuery('#'+result2[i]+' form').serialize());
	}
};



/* JS from events for controlling manager actions 
 **********************************/

function checkInput() {
    var flag = 0;
    var inputUncheck = WDN.jQuery('a.uncheckall');
    var inputCheck = WDN.jQuery('a.checkall');
    var f = document.enewsManage;
    var checks = f.getElementsByTagName('input');
    
    for(var k=0;k<checks.length;k++){
        if(checks[k].checked == true){
            flag = 1;
        }
    }
    if (flag == 0){ 
        manager.storyselected = false;
    } else { 
        manager.storyselected = true;
    }
};

 

/**
 * Namespace for manager javascript.
 */
var manager = function() {
    return {
        list : 'unset',
        storyselected : false,
        /* Updates elements to which actions can be selected.  */
        updateActionMenus : function(sel) {
            sel.selectedIndex = 0;
            if (manager.storyselected) {
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
         
        
        /* This function is called when an action is selected within an event listing */
        actionMenuChange  : function(sel) {
            switch(sel[sel.selectedIndex].value) {
            case 'approved':
            case 'archived':
                var button = document.getElementById('moveto_approved');
                button.click();
                break;
            case 'pending':
                var button = document.getElementById('moveto_pending');
                button.click();
                break;
            case 'recommend':
                var form = document.getElementById('enewsManage');
                form.action = '?view=recommend';
                form.submit();
                break;
            case 'delete':
                var button = document.getElementById('delete_story');
                button.click();
                break;
            }
        }
    };
}();


 