var manager = function($) {
	return {
		list : 'unset',
		storyselected : false,

		initialize : function() {
			$('.checkall').click(function() {
				$('.storylisting input[type=checkbox]').attr('checked', true);
				manager.checkInput();
				return false;
			});
			$('.uncheckall').click(function() {
				$('.storylisting input[type=checkbox]').attr('checked', false);
				manager.storyselected = false;
				manager.checkInput();
				return false;
			});
			$('.storylisting input[type=checkbox]').click(function() { 
				manager.checkInput();
				return this;
			});
			
			/* Make entire row clickable */
			$('table.storylisting tr td + td').click(function() {
				var editHref = $(this).parent().children('td').children('a.edit').attr('href');
				window.location = editHref;
				return false;
			}).hover(function() {
				$(this).css({'cursor':'pointer'});
			});
		},

		checkInput : function() {
			var flag = 0;
			var inputUncheck = $('a.uncheckall');
			var inputCheck = $('a.checkall');
			var f = document.enewsManage;
			var checks = f.getElementsByTagName('input');
			
			for (var k=0;k<checks.length;k++) {
				if (checks[k].checked === true) {
					flag = 1;
				}
			}
			if (flag === 0) {
				manager.storyselected = false;
			} else {
				manager.storyselected = true;
			}
		},

		/* Updates elements to which actions can be selected */
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

		/* Called when an action is selected within an event listing */
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
}(WDN.jQuery);
