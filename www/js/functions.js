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
	
	preview.initialize();
});

/**
 * 
 *  Namespace for the newsletter preview functionality
 * 
 */

var preview = function() {
	return {
		initialize : function() {
			WDN.jQuery('#drag_story_list > .dragItem, table .story').each(function(){ //add the tools to all the stories on the page
				WDN.jQuery(this).append('<div class="storyTools"><a class="edit" href="?view=submit&id='+WDN.jQuery(this).children('form').children('input[name="story_id"]').attr('value')+'"><span></span>Edit</a><a class="remove" href="#"><span></span>Remove</a></div>');
			});
			WDN.loadJS('/wdn/templates_3.0/scripts/plugins/hoverIntent/jQuery.hoverIntent.min.js', preview.setupEditRemove);
			preview.setupDragAndSort();
			
		},
		
		bindEdit : function() {
			WDN.jQuery('a.edit').click(preview.editStory);
		},
		
		bindRemove : function() {
			WDN.jQuery('a.remove').click(preview.removeStory);
		},
		
		editStory : function() {
			
			//return false;
		},
		
		removeStory : function() {
			WDN.jQuery(this).parent('.storyTools').hide().parents('.story').unbind().removeClass('story').addClass('dragItem').appendTo('#drag_story_list');
			WDN.jQuery(this).parent('.storyTools').siblings("form").children("input[name='_type']").attr('value', 'removestory');
			
			//update the stored story orders
			preview.saveStoryOrder();
			
			//remove the db record for this story
			WDN.jQuery.post(WDN.jQuery(this).parent('.storyTools').siblings("form").attr('action'), WDN.jQuery(this).parent('.storyTools').siblings("form").serialize());
			
			preview.setupDragAndSort();
			
			return false;
		},
		
		setupEditRemove : function(){
			hoverConfig = {
				over : function() { WDN.jQuery(this).children('.storyTools').fadeIn(800) },
				timeout : 300,
				interval : 300,
				out : function() { WDN.jQuery(this).children('.storyTools').fadeOut() }
			};
			WDN.jQuery('#maincontent table .story').each(function() {
				WDN.jQuery(this).removeClass('ui-draggable');
				WDN.jQuery(this).hoverIntent(hoverConfig);
			});
			preview.bindEdit();
			preview.bindRemove();
		},
		
		setupDragAndSort : function(){ //make all the stories movable
			WDN.jQuery('.dragItem').draggable({ 
				revert: 'invalid',
				snap: '.newsColumn',
				snapMode : 'inner',
				connectToSortable: '.newsColumn',
				helper: 'clone',
				opacity: 0.45,
				stop: function(event, ui){
					WDN.jQuery(this).remove();
					ui.helper.find("input[name='_type']").attr('value', 'addstory');
				}
			});
			WDN.jQuery('#newsColumn1, #newsColumn2, #newsColumnIntro').sortable({ //make all the stories on the newsletter sortable
				revert: false,
				connectWith: '.newsColumn',
				scroll: true,
				delay: 250,
				opacity: 0.45,
				tolerance: 'pointer',
				helper: 'clone',
				start: function(event, ui){
					WDN.jQuery(this).children('.storyTools').hide();
					WDN.jQuery('.ui-sortable-helper .storyTools').hide();
				},
				stop: function(event, ui){
					preview.saveStoryOrder();
					ui.item.children('.storyTools').hide();
				}
			});
			WDN.jQuery('#newsColumn1, #newsColumn2, #newsColumnIntro').disableSelection(); //This keeps content from being highlighted and instead draggable
			WDN.jQuery('.newsColumn').droppable({ 
				drop: function(event, ui) {
					ui.draggable.addClass('story').removeAttr('style').removeClass('dragItem');
					ui.helper.remove();
					preview.setupEditRemove();
					//WDN.jQuery.post(WDN.jQuery(this).find('form').attr('action'), WDN.jQuery(this).find('form').serialize());
				}
			});
			
			//All items in the left column are there ready to be added to the newsletter... let's make sure they are setup to be added
			WDN.jQuery('#drag_story_list input[name="_type"]').each(function(){
				WDN.jQuery(this).attr('value', 'addstory'); 
			});
		},
		
		saveStoryOrder : function() { //this function determines the order of the stories and sends it to the DB.
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
		}
		
		
	}
}();


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
 * 
 * Namespace for manager javascript.
 * 
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
					sel[3].disabled = null;
					sel[4].disabled = null;
				} else if (manager.list == 'search') {
					sel[1].disabled = null;
					sel[2].disabled = null;
					sel[3].disabled = null;
					sel[4].disabled = 'disabled';
				} else if (manager.list == 'none') {
					sel[1].disabled = null;
				} else {
					sel[1].disabled = null;
					sel[2].disabled = 'disabled';
					sel[3].disabled = null;
					sel[4].disabled = null;
				}
			} else {
				for (i=1;i<sel.options.length;i++) {
					sel[i].disabled = 'disabled';
				}
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


 