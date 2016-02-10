jQuery(document).ready(function ($) {

	Array.max = function (array) {
		return Math.max.apply(Math, array);
	};

	/**
	 * Add a staff URL
	 */
	$("#add-url").click(function () {

		var ids = $("#staff-urls").find("input.original-order").map(function (val, el) {
			return el.value;
		}).get();

		var count = Array.max(ids);

		var data = {
			'count': count,
			'action': 'setmoreplus_add_url',
		};

		$.get(ajaxurl, data, function (response) {
			$("#staff-urls").append(response);
		});
	});

	/**
	 * Delete a staff URL
	 */
	$("#staff-urls").on("click", ".staff-delete", function () {
		var thisField = $(this).closest(".row");

		var id = thisField.find(".staff-id").html();
		id = parseInt(id);

		var yesno = confirm("Remove ID " + id + "?");

		if (yesno) {
			thisField.fadeOut(function () {
				$(this).remove();
				// reindex
				$("#staff-urls").find(".staff-id").each(function (index, el) {
					// Cannot actually change DOM input values. Original values will post instead. So use plain text.
					$(el).html(index + 1);
				});
			});
		}
	});
});
