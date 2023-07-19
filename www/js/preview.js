define([
	'jquery',
	'wdn',
	'require',
], function($, WDN, require) {

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
				plugin.setupTools(element);
				plugin.setupToolsHover(element);
			} else {
				element.children('.story-content').remove();
				element.prepend(data);
			}
		});
	};

	var plugin = {
		initialize : function() {
			$(function() {
				$('.story').each(function(){ //add the tools to all the stories on the page
					plugin.setupTools(this);
				});
				// stop actions on all links in a story
				$('.story .story-content').on('click', 'a', function(e) {
					return false;
				})
				WDN.loadJS(ENEWS_HOME+'/js/jQuery.hoverIntent.js', plugin.setupToolsHover);
				plugin.setupDragAndSort();
				plugin.initDraggable($('.adArea .story'));
			});
			$('#releaseDate').attr('autocomplete', 'off').change(function(){
				plugin.updateAvailableStories(['news', 'event', 'ad'], new Date($(this).val()).toISOString().split('T')[0]);
				plugin.updateDates($(this).val());
			});
			$('#detailsForm input[type="text"]').change(function(){ //auto save newsletter details
				plugin.saveDetails.newsletter();
			});
			$('.emailIndicator input[type="checkbox"]').change(function(){
				plugin.saveDetails.emails(this);
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
					if ($(ls).prop('checked')){
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
				"csrf_name": document.querySelector('input[name="csrf_name"]').value,
				"csrf_value": document.querySelector('input[name="csrf_value"]').value,
				"_type":"removestory",
				"story_id":theStory.data("id")
			}, function() {
				theStory.data('presentation_id', theStory.data('default_presentation_id'));
				//remove the story-content and rebuild the grip
				theStory.children().remove();
				var grippy = $('<div class="story-grip" />');
				grippy.append('<h4 class="dcf-txt-xs dcf-regular dcf-mb-2">' + theStory.data('title') + '</h4>');
				var reqDates = [theStory.data('request_publish_start').split(' ')];
				if (theStory.data('request_publish_end')) {
					reqDates.push(theStory.data('request_publish_end').split(' '));
				}
				var datesSpan = $('<span class="requestedDates"/>');
				$.each(reqDates, function(idx, val) {
					datesSpan.append('<span class="dateRange"><span class="month">' + val[0] + '</span><span class="day">' + val[1] + '</span></span>');
				});
				grippy.append(datesSpan).appendTo(theStory);
				
				var storyList = '#againAvailable .storyItemWrapper';
				
				$(storyList + ' > p').remove();
				
				theStory.removeClass('story').addClass('dragItem').css('display', 'inline-block').appendTo(storyList);
				plugin.initDraggable(theStory);
				
				//update the stored story orders
				plugin.saveStoryOrder(sortable);
			});
			return false;
		},
		
		setStoryPresentation : function(theStory, presentation_id) {
			$.post(window.location.toString(), {
				"_type":"setpresentation",
				"story_id":theStory.data('id'),
				"csrf_name": document.querySelector('input[name="csrf_name"]').value,
				"csrf_value": document.querySelector('input[name="csrf_value"]').value,
				"presentation_id":presentation_id
			}, function() {
				theStory.data('presentation_id', presentation_id);
				loadStoryContent(theStory, false);
			});
		},
		
		setupTools : function(el) {
			$(el).append('<div class="storyTools"><a class="edit" href="?view=submit&id='+$(el).data('id')+'"><span />Edit</a><a class="remove" href="#"><span />Remove</a><a class="layout" href="#"><span />Layout</a></div>');
			$('a.remove', el).click(function() {
				plugin.removeStory($(this).closest('.story'));
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
					var dialog = $('<div title="Select a Layout"><form class="dcf-form"></form></div>');
					var selector = $('<select name="presentation_id" />');
					$.each(data, function(key, label) {
						var option = $('<option />').text(label).attr("value", key);
						if (theStory.data('presentation_id') == key) {
							option.attr("selected", "selected");
						}
						option.appendTo(selector);
					});
					dialog.find('form').append(selector);
					dialog.dialog({
						height: 'auto',
						width: 'auto',
						modal: true,
						buttons: {
							"Save": function() {
								var presentation_id = $('select', this).val();
								if (presentation_id != theStory.data('presentation_id')) {
									plugin.setStoryPresentation(theStory, presentation_id);
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
			el = el || $('#dcf-main .story');
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
					plugin.saveStoryOrder(this);
				},
				receive: function(e, ui) {
					if (ui.item.hasClass('dragItem')) {
						var dragList = ui.item.parent();
						ui.item.removeClass('dragItem').addClass('story');
						ui.item.children().remove();
						ui.item.insertBefore(dragClone);
						dragClone.remove();
						dragClone = null;
						$(this).sortable('refresh');
						ignoreUpdate = this;
						plugin.saveStoryOrder(this, function() {
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
				tolerance: 'pointer',
				hoverClass: "ui-state-active",
				drop: function(e, ui) {
					ui.helper.remove();
					var droppable = this;
					
					//BEGIN LAYOUT FIXES
					if (this.id == 'adAreaIntro') {
						$('.adArea .story').not(ui.draggable).each(function() {
							plugin.removeStory($(this));
						});
					} else {
						var existing = $('.story', this)
						if (ui.draggable.hasClass('story')) {
							if (existing.length) {
								// do swap
								var whence = ui.draggable.closest('.adArea');
								$(this).bind('afterappend', function() {
									existing.appendTo(whence);
									plugin.saveStoryOrder(whence, function() {
										loadStoryContent(existing, false);
									});
								});
							}
						} else {
							$('#adAreaIntro .story').each(function() {
								plugin.removeStory($(this));
							});
							
							if (existing.length) {
								var otherAreaId = (this.id == 'adArea1') ? 'adArea2' : 'adArea1';
								var newDest = $('#' + otherAreaId);
								if (!$('.story', newDest).length) {
									existing.appendTo(newDest);
									plugin.saveStoryOrder(newDest, function() {
										loadStoryContent(existing, false);
									});
								} else {									
									plugin.removeStory($('.story', this));
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
						
						plugin.saveStoryOrder(droppable, function() {
							loadStoryContent(ui.draggable);
						});
					} else {
						ui.draggable.appendTo(droppable);
						$(this).triggerHandler('afterappend')
						$(this).unbind('afterappend');
						
						plugin.saveStoryOrder(droppable, function() {
							loadStoryContent(ui.draggable, false);
						});
					}
				}
			});
			plugin.initDraggable('.dragItem');
			$('.newsColumn, .adArea').disableSelection(); //This keeps content from being highlighted and instead draggable
		},

		saveStoryOrder : function(sortable, callback) { //this function determines the order of the stories and sends it to the DB.
			sortable = sortable || '.newsColumn, .adArea';
			callback = callback || $.noop;
			var numColumns = 3;
			var postData = {
				"csrf_name": document.querySelector('input[name="csrf_name"]').value,
				"csrf_value": document.querySelector('input[name="csrf_value"]').value,
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
				$('#drag_story_list_unpublished .'+val+'Available .storyItemWrapper').html('Loading');
				$.get("", {
					"view" : "unpublishedStories",
					"type" : val,
					"date" : date,
					"limit" : -1,
					"status" : "approved",
					"format" : "partial"
				}, function(data) {
					$('#drag_story_list_unpublished .'+val+'Available .storyItemWrapper').html(data);
					plugin.initDraggable('#drag_story_list_unpublished .' + val + 'Available .dragItem');
				});
			});
			$.each(type, function(i, val) {
				$('#drag_story_list_reusable .'+val+'Available .storyItemWrapper').html('Loading');
				$.get("", {
					"view" : "reusableStories",
					"type" : val,
					"date" : date,
					"limit" : -1,
					"format" : "partial"
				}, function(data) {
					$('#drag_story_list_reusable .'+val+'Available .storyItemWrapper').html(data);
					plugin.initDraggable('#drag_story_list_reusable .' + val + 'Available .dragItem');
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
	};

	return plugin;
});
