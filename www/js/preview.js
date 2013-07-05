var preview = function($) {
	var presentationCache = {};
	var loadStoryContent = function(element, doSetupTools) {
		if (undefined === doSetupTools) {
			doSetupTools = true;
		}
		
		var loadingElem = element;
		if (!doSetupTools) {
			loadingElem = element.children('.story-content');
		}
		loadingElem.text('Loading...')
		
		$.get("", {
			"view" : "previewStory",
			"id" : $('form input[name=id]').attr('value'),
			"story_id" : element.data("id"),
			"format" : "partial"
		}, function(data) {
			if (doSetupTools) {
				element.html(data);
				preview.setupTools(element);
				preview.setupToolsHover(element);
			} else {
				element.children('.story-content').remove();
				element.prepend(data);
			}
		});
	};
	return {
		initialize : function() {
			$(function() {
				$('.story').each(function(){ //add the tools to all the stories on the page
					preview.setupTools(this);
				});
				// stop actions on all links in a story
				$('.story .story-content a').live('click', function(e) {
					return false;
				})
				WDN.loadJS('/wdn/templates_3.1/scripts/plugins/hoverIntent/jQuery.hoverIntent.min.js', preview.setupToolsHover);
				preview.setupDragAndSort();
				preview.initDraggable($('.adArea .story'));
			});
			$('#releaseDate').attr('autocomplete', 'off').change(function(){
				preview.updateAvailableStories(['news', 'event', 'ad'], $(this).val());
				preview.updateDates($(this).val());
			});
			$('#detailsForm input[type="text"]').change(function(){ //auto save newsletter details
				preview.saveDetails.newsletter();
			});
			$('.email_addresses h5').click(function(){
				$('.email_addresses ul').slideToggle();
			});
			$('.emailIndicator input[type="checkbox"]').change(function(){
				preview.saveDetails.emails(this);
			});
			$('h3 a.showHide').click(function() {
				$(this).parent('h3').nextUntil('h3').slideToggle();
				$(this).toggleClass('show');
				return false;
			});
		},
		
		saveDetails : function() {
			return {
				newsletter : function() {
					$.post(
						$('#detailsForm').attr('action'), 
						$('#detailsForm').serialize(),
						function(data) {
							WDN.log('saved!');
							$('#detailsForm input[type="submit"]').attr('disabled' , 'disabled');
						}
					);
				},
				
				emails : function(ls) {
					if ($(ls).attr('checked')){
						action = "add";
					} else {
						action = "remove";
					}
					form = $(ls).closest('li').children('form.'+action);
					$.post($(form).attr('action'), $(form).serialize());
				}
			};
		}(),
		
		removeStory : function(theStory) {
			var sortable = theStory.closest('.newsColumn, .adArea');
			//remove the db record for this story
			$.post(window.location.toString(), {
				"_type":"removestory",
				"story_id":theStory.data("id")
			}, function() {
				theStory.data('presentation_id', theStory.data('default_presentation_id'));
				//remove the story-content and rebuild the grip
				theStory.children().remove();
				var grippy = $('<div class="story-grip" />');
				grippy.append('<h4>' + theStory.data('title') + '</h4>');
				var reqDates = [theStory.data('request_publish_start').split(' ')];
				if (theStory.data('request_publish_end')) {
					reqDates.push(theStory.data('request_publish_end').split(' '));
				}
				var datesSpan = $('<span class="requestedDates"/>');
				$.each(reqDates, function(idx, val) {
					datesSpan.append('<span class="dateRange"><span class="month">' + val[0] + '</span><span class="day">' + val[1] + '</span></span>');
				});
				grippy.append(datesSpan).appendTo(theStory);
				
				var storyList = '#' + theStory.data('type') + 'Available .storyItemWrapper';
				
				$(storyList + ' > p').remove();
				
				theStory.removeClass('story').addClass('dragItem').css('display', 'inline-block').appendTo(storyList);
				preview.initDraggable(theStory);
				
				//update the stored story orders
				preview.saveStoryOrder(sortable);
			});
			return false;
		},
		
		setStoryPresentation : function(theStory, presentation_id) {
			$.post(window.location.toString(), {
				"_type":"setpresentation",
				"story_id":theStory.data('id'),
				"presentation_id":presentation_id
			}, function() {
				theStory.data('presentation_id', presentation_id);
				loadStoryContent(theStory, false);
			});
		},
		
		setupTools : function(el) {
			$(el).append('<div class="storyTools"><a class="edit" href="?view=submit&id='+$(el).data('id')+'"><span />Edit</a><a class="remove" href="#"><span />Remove</a><a class="layout" href="#"><span />Layout</a></div>');
			$('a.remove', el).click(function() {
				preview.removeStory($(this).closest('.story'));
				return false;
			});
			$('a.layout', el).click(function() {
				var theStory = $(this).closest('.story');
				var displayPresentationDialog = function (data) {
					if ($.isEmptyObject(data)) {
						alert("This story's type does not currently support different layouts.");
						return;
					}
					presentationCache[theStory.data('type')] = data;
					var dialog = $('<div title="Select a Layout"><form style="text-align:center;"><fieldset /></form></div>');
					var selector = $('<select name="presentation_id" />');
					$.each(data, function(key, label) {
						var option = $('<option />').text(label).attr("value", key);
						if (theStory.data('presentation_id') == key) {
							option.attr("selected", "selected");
						}
						option.appendTo(selector);
					});
					dialog.find('fieldset').append(selector);
					dialog.dialog({
						height: 150,
						width: 350,
						modal: true,
						buttons: {
							"Save": function() {
								var presentation_id = $('select', this).val();
								if (presentation_id != theStory.data('presentation_id')) {
									preview.setStoryPresentation(theStory, presentation_id);
								}
								$(this).dialog("close");
							},
							"Cancel": function() {
								$(this).dialog("close");
								$(this).remove();
							}
						},
						close: function() {
							$(this).remove();
						}
					});
				};
				
				var presData = presentationCache[theStory.data('type')];
				if (!presData) {
					$.getJSON("", {
						"view" : "presentationList",
						"type" : theStory.data("type"),
						"format" : "partial"
					}, displayPresentationDialog);
				} else {
					displayPresentationDialog(presData);
				}
				
				return false;
			});
		},
		
		setupToolsHover : function(el){
			el = el || $('#maincontent .story');
			var hoverConfig = {
				over : function() { 
					$(this).children('.storyTools').fadeIn(800);
				},
				timeout : 300,
				interval : 300,
				out : function() { 
					$(this).children('.storyTools').fadeOut()
				}
			};
			el.hoverIntent(hoverConfig);
		},
		
		initDraggable : function(el) {
			$(el).each(function(){
				if ($(this).data('type') == 'ad') {
					if ($(this).data('draggable')) {
						return true;
					}
					$(this).draggable({
						revert: 'invalid',
						snap: '.adArea',
						snapMode : 'inner',
						helper: 'clone',
						opacity: 0.45,
						start: function(ev, ui) {
							ui.helper.children('.storyTools').hide();
							$(this).children('.storyTools').hide();
						}
					});
				} else {
					$(this).draggable({ 
						revert: 'invalid',
						snap: '.newsColumn',
						snapMode : 'inner',
						connectToSortable: '.newsColumn',
						helper: 'clone',
						opacity: 0.45,
						stop: function(ev, ui) {
							if (!$(this).hasClass('dragItem')) {
								$(this).draggable('destroy');
							}
						}
					});
				}
			});
		},
		
		setupDragAndSort : function(){ //make all the stories movable
			var dragClone, ignoreUpdate;
			$('.newsColumn').sortable({ //make all the stories on the newsletter sortable
				revert: false,
				connectWith: '.newsColumn',
				scroll: true,
				delay: 250,
				opacity: 0.45,
				tolerance: 'pointer',
				helper: 'clone',
				start: function(event, ui){
					ui.helper.children('.storyTools').hide();
					ui.item.children('.storyTools').hide();
				},
				beforeStop: function(event, ui){
					if (ui.item.hasClass('dragItem')) {
						dragClone = ui.item;
					}
				},
				update: function(e, ui) {
					if (ignoreUpdate == this) {
						ignoreUpdate = null;
						return;
					}
					preview.saveStoryOrder(this);
				},
				receive: function(e, ui) {
					if (ui.item.hasClass('dragItem')) {
						var dragList = ui.item.parent();
						ui.item.removeClass('dragItem').addClass('story');
						ui.item.children().remove();
						ui.item.insertBefore(dragClone);
						dragClone.remove();
						dragClone = null;
						if (!dragList.children('.dragItem').length) {
							dragList.append("<p>No Available Items</p>");
						}
						$(this).sortable('refresh');
						ignoreUpdate = this;
						preview.saveStoryOrder(this, function() {
							loadStoryContent(ui.item);
						});
					}
				}
			});
			
			$('.adArea').droppable({
				accept: function(draggable) {
					/* STRICT CONSTRAINTS: 
					if (draggable.data('type') == 'ad' && $('.story', this).length < 1) {
						var adCount = $('.adArea .story').length
						if (draggable.hasClass('dragItem')) {
							if (this.id == 'adAreaIntro') {
								return !adCount;
							} else {
								return adCount < 2 && !$('#adAreaIntro .story').length;
							}
						} else if (draggable.closest('.adArea')[0] != this) {
							var adCount = $('.adArea .story').length - 1; // ignore the cloned helper
							if (this.id == 'adAreaIntro') {
								return adCount <= 1;
							} else {
								return adCount <= 2;
							}
						}
					}
					*/
					
					/* RELEXED CONSTRAINTS (use with layout fixes): */
					if (draggable.data('type') == 'ad') {
						if (draggable.hasClass('story') && draggable.closest('.adArea')[0] == this) {
							return false;
						}
						
						return true;
					}
					
					return false;
				},
				hoverClass: "ui-state-active",
				drop: function(e, ui) {
					ui.helper.remove();
					var droppable = this;
					
					//BEGIN LAYOUT FIXES
					if (this.id == 'adAreaIntro') {
						$('.adArea .story').not(ui.draggable).each(function() {
							preview.removeStory($(this));
						});
					} else {
						var existing = $('.story', this)
						if (ui.draggable.hasClass('story')) {
							if (existing.length) {
								// do swap
								var whence = ui.draggable.closest('.adArea');
								$(this).bind('afterappend', function() {
									existing.appendTo(whence);
									preview.saveStoryOrder(whence, function() {
										loadStoryContent(existing, false);
									});
								});
							}
						} else {
							$('#adAreaIntro .story').each(function() {
								preview.removeStory($(this));
							});
							
							if (existing.length) {
								var otherAreaId = (this.id == 'adArea1') ? 'adArea2' : 'adArea1';
								var newDest = $('#' + otherAreaId);
								if (!$('.story', newDest).length) {
									existing.appendTo(newDest);
									preview.saveStoryOrder(newDest, function() {
										loadStoryContent(existing, false);
									});
								} else {									
									preview.removeStory($('.story', this));
								}
							}
						}
					}
					//END LAYOUT FIXES
					
					// NOTICE: The save functions below are based on the assumption that each droppable 
					// can contain one story
					if (!ui.draggable.hasClass('story')) {
						var dragList = ui.draggable.parent();
						ui.draggable.removeClass('dragItem').addClass('story');
						ui.draggable.children().remove();
						ui.draggable.appendTo(this);
						if (!dragList.children('.dragItem').length) {
							dragList.append("<p>No Available Items</p>");
						}
						
						preview.saveStoryOrder(droppable, function() {
							loadStoryContent(ui.draggable);
						});
					} else {
						ui.draggable.appendTo(droppable);
						$(this).triggerHandler('afterappend')
						$(this).unbind('afterappend');
						
						preview.saveStoryOrder(droppable, function() {
							loadStoryContent(ui.draggable, false);
						});
					}
				}
			});
			preview.initDraggable('.dragItem');
			$('.newsColumn, .adArea').disableSelection(); //This keeps content from being highlighted and instead draggable
		},
		
		saveStoryOrder : function(sortable, callback) { //this function determines the order of the stories and sends it to the DB.
			sortable = sortable || '.newsColumn, .adArea';
			callback = callback || $.noop;
			var numColumns = 3;
			var postData = {
				"_type":"addstory",
				"story_id":{}
			};
			$(sortable).each(function() {
				var offset;
				switch (this.id) {
					case 'newsColumnIntro':
					case 'adAreaIntro':
						offset = 1;
						break;
					case 'newsColumn1':
					case 'adArea1':
						offset = 2;
						break;
					case 'adArea2':
					case 'newsColumn2':
						offset = 0;
						break;
				};
				$(this).children('.story').each(function(idx) {
					var story = $(this);
					postData['story_id'][story.data('id')] = { "sort_order" : idx * numColumns + offset };
				});
			});
			if (!$.isEmptyObject(postData["story_id"])) {
				$.post(window.location.toString(), postData, callback);
			}
		},
		
		updateAvailableStories : function(type, date) {
			if (!$.isArray(type)) {
				type = [type];
			}
			$.each(type, function(i, val) {
				$('#'+val+'Available .storyItemWrapper').html('Loading');
				$.get("", {
					"view" : "unpublishedStories",
					"type" : val,
					"date" : date,
					"limit" : -1,
					"format" : "partial"
				}, function(data) {
					$('#'+val+'Available .storyItemWrapper').html(data);
					preview.initDraggable('#' + val + 'Available .dragItem');
				});
			});
		},
		
		updateDates : function(selectedDate) {
			var date = new Date(selectedDate);
			date.setUTCHours(6);
			var weekday = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
			var month = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
			$('.newsletterDate').html(weekday[date.getDay()]+', '+ month[date.getMonth()]+' '+date.getDate()+', '+date.getFullYear());
		}
	}
}(WDN.jQuery);
