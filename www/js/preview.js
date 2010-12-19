var preview = function($) {
	var qsParams = {};
	var initQS = function() {
		var querystring = window.location.search.replace('?', '').split('&');
		for (var i = 0; i<querystring.length; i++) {
			var nvPair = querystring[i].split('=');
			qsParams[decodeURI(nvPair[0])] = decodeURI(nvPair[1]);
		}
	}();
	var presentationCache = {};
	return {
		initialize : function() {
			$(function() {
				$('table .story').each(function(){ //add the tools to all the stories on the page
					preview.setupTools(this);
				});
				// stop actions on all links in a story
				$('table .story .story-content a').live('click', function(e) {
					return false;
				})
				WDN.loadJS('/wdn/templates_3.0/scripts/plugins/hoverIntent/jQuery.hoverIntent.min.js', preview.setupToolsHover);
				preview.setupDragAndSort();
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
		
		removeStory : function() {
			var theStory = $(this).closest('.story');
			//remove the db record for this story
			$.post(window.location.toString(), {
				"_type":"removestory",
				"story_id":theStory.data("id")
			}, function() {
				if (theStory.data('orig_presentaion_id')) {
					theStory.data('presentation_id', theStory.data('orig_presentaion_id')).removeData('orig_presentaion_id');
				}
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
				
				theStory.removeClass('story').addClass('dragItem').appendTo(storyList);
				preview.initDraggable(theStory);
				
				//update the stored story orders
				preview.saveStoryOrder(theStory.closest('.ui-sortable'));
			});
			
			return false;
		},
		
		setupTools : function(el) {
			$(el).append('<div class="storyTools"><a class="edit" href="?view=submit&id='+$(el).data('id')+'"><span />Edit</a><a class="remove" href="#"><span />Remove</a><a class="layout" href="#"><span />Layout</a></div>');
			$('a.remove', el).click(preview.removeStory);
			$('a.layout', el).click(function() {
				var theStory = $(this).closest('.story');
				if (!theStory.data('orig_presentaion_id')) {
					theStory.data('orig_presentaion_id', theStory.data('presentation_id'));
				}
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
									$.post(window.location.toString(), {
										"_type":"setpresentation",
										"story_id":theStory.data('id'),
										"presentation_id":presentation_id
									}, function() {
										theStory.data('presentation_id', presentation_id);
										theStory.children('.story-content').text('Loading...');
										$.get("", {
											"view" : "previewStory",
											"id" : $('form input[name=id]').attr('value'),
											"story_id" : theStory.data("id"),
											"format" : "partial"
										}, function(data) {
											theStory.children('.story-content').remove();
											theStory.prepend(data);
										});
									});
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
			el = el || $('#maincontent table .story');
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
			$(el).draggable({ 
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
							dragList.append("<p>Sorry, no unused/available stories.</p>");
						}
						$(this).sortable('refresh');
						ignoreUpdate = this;
						ui.item.text('Loading...');
						preview.saveStoryOrder(this, function() {
							$.get("", {
								"view" : "previewStory",
								"id" : $('form input[name=id]').attr('value'),
								"story_id" : ui.item.data("id"),
								"format" : "partial"
							}, function(data) {
								ui.item.html(data);
								preview.setupTools(ui.item);
								preview.setupToolsHover(ui.item);
							});
						});
					}
				}
			});
			preview.initDraggable('.dragItem');
			$('.newsColumn').disableSelection(); //This keeps content from being highlighted and instead draggable
		},
		
		saveStoryOrder : function(sortable, callback) { //this function determines the order of the stories and sends it to the DB.
			sortable = sortable || '.newsColumn';
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
						offset = 1;
						break;
					case 'newsColumn1':
						offset = 2;
						break;
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
