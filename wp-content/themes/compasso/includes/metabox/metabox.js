jQuery(function($) {

	function layout_options(page_template) {
		var hide_option = [];
		if (page_template == 'page-categories.php') {
			hide_option = ['0', '3'];
		}
		var layout_select = $('#_g7_layout');
		var selected = layout_select.val();
		var layout_options = {};
		layout_options[0] = 'Default Layout';
		layout_options[1] = 'Right Sidebar';
		layout_options[2] = 'Left Sidebar';
		layout_options[3] = 'Full Width';
		/*layout_select.find('option').each(function() {
			layout_options[$(this).val()] = $(this).text();
		});*/
		layout_select.empty();
		$.each(layout_options, function(k, v) {
			if ($.inArray(k, hide_option) == -1) {
				layout_select.append($('<option>').val(k).text(v));
			}
		});
		layout_select.val(selected);
	}

	function show_meta_boxes() {
		var page_template = $('#page_template').val();

		//console.log(g7mb);
		//console.log(g7mb2);

		$.each(g7mb, function(k, v) {
			$('#' + k).hide();
			if ($.inArray(page_template, v) > -1) {
				$('#' + k).show();
			} else if (v.length === 0) {
				$('#' + k).show();
			}
		});

		$.each(g7mb2, function(k, v) {
			if ($.inArray(page_template, v) > -1) {
				$('#' + k).hide();
			}
		});

		layout_options(page_template);
	}
	show_meta_boxes();
	$('#page_template').change(show_meta_boxes);

	var page_layout = $('#_g7_layout');
	if (page_layout == 3) {
		//
	}

	$('.g7-dragdrop').on('click', '.widget-action', function(e) {
		e.preventDefault();
		$(this)
			.closest('.widget')
				.find('.widget-inside')
					.slideToggle('fast');
	});

	$('.g7-dragdrop').on('click', '.widget-control-remove', function(e) {
		e.preventDefault();
		$(this).closest('.widget')
			.animate({height: 0, opacity: 0}, 'slow', function() {
				$(this).remove();
			});
	});

	$('.g7-dragdrop').on('change', '.g7-category-dropdown', function() {
		var title = $(this).find('option:selected').text();
		title = $.trim(title);
		$(this).closest('.widget')
			.find('.widget-title > h4')
				.text(title);
	});

	$('.g7-dragdrop').sortable({
		handle: '.widget-top',
		placeholder: 'widget-placeholder',
		update: function () {
			var sorted = $('.g7-dragdrop').sortable('serialize');
		}
	});

	$('.g7-add-item a').click(function(e) {
		e.preventDefault();
		var row_container = $(this).closest('.g7mb-input').find('.g7-dragdrop');
		$(this).closest('.g7mb-input')
			.find('.g7-dragdrop-item > .widget')
				.clone()
				.hide()
				.appendTo(row_container)
				.fadeIn();
	});

	function rating_slider() {
		$('.g7-slider').each(function() {
			var el = $(this);
			el.slider({
				min: el.data('min'),
				max: el.data('max'),
				step: el.data('step'),
				value: el.parent().next().children('input').val(),
				slide: function(event, ui) {
					el.parent().next().children('input').val(ui.value);
					set_overall_rating();
				}
			});
		});
	}
	rating_slider();

	function get_overall_rating() {
		var total = 0;
		var count = 0;
		var overall_rating = 0;
		$('.g7-rating > tbody > tr').each(function() {
			var row = $(this);
			var rating = parseFloat(row.find('[name^="_g7_rating"]').val());
			if (rating > 0) {
				total += rating;
				count++;
			}
		});
		if (count == 0) {
			return '';
		}
		overall_rating = total / count;
		overall_rating = overall_rating.toFixed(2);
		return overall_rating;
	}

	function set_overall_rating() {
		var overall = get_overall_rating();
		$('#_g7_overall_rating').val(overall);
	}

	$('.g7-rating-add a').click(function(e) {
		e.preventDefault();
		var row_container = $(this).parent().next('.g7-rating').find('tbody');
		row_container
			.children('tr:last')
				.clone(false)
				.appendTo(row_container)
				.find('[name^="_g7_criteria"]')
					.val('')
					.focus()
					.end()
				.find('[name^="_g7_rating"]')
					.val('')
					.end();
		rating_slider();
		set_overall_rating();
	});

	$('.g7-rating').on('click', '.g7-rating-delete', function(e) {
		e.preventDefault();
		var row_count = $(this).closest('tbody').children('tr').length;
		if (row_count > 1) {
			$(this).closest('tr').remove();
		} else {
			$(this).closest('tr').find('.g7-slider').slider('value', 0);
			$(this).closest('tr').find('[name^="_g7_criteria"]').val('');
			$(this).closest('tr').find('[name^="_g7_rating"]').val('');
		}
		set_overall_rating();
	});

});