(function ($) {
	'use strict';

	/**
	 * JavaScript (jQuery) admin source code
	 */

})(jQuery(document).ready(function ($) {

	$("#form1").submit(function (e) {
		var valid = true;

		if (!$('div.checkbox-group1.required :checkbox:checked').length > 0) {
			$('#errorContainer1').show();
			valid = false;
		}
		if (!$('div.checkbox-group2.required :radio:checked').length > 0) {
			$('#errorContainer2').show();
			valid = false;
		}
		if (valid) {
			//disable the submit button
			$("#submit").attr("disabled", true);
			var formData = $('form').serializeArray();
			$.ajax({
				data: formData,
				type: 'post',
				url: ajaxurl,
				success: function (data) {
					console.log(data); 
					if (data == 'true') {
						$("#fountainG").show();
						id = 0;
						count = 0;

						function getdata(id) {
							var data = {
								'action': 'process_chunk',
								'counter': count
							};
							//
							jQuery.post(ajaxurl, data, function (response) {
								console.log('response: ' + response);
								if (response == 'true') {
									++count;
									getdata(count);
								} else {
									console.log('FIN');
									window.location.href = window.location.href + "&admin_add_notice=success";
								}
							});
						}
						getdata(count);
					}
					else {
						$('#errorContainer3').show();
						$("#submit").attr("disabled", false);
					}
				}
			});
		}
		event.preventDefault();
	})

	$('.checkbox-group1').change(function () {
		$('#errorContainer1').hide();
		$('#errorContainer3').hide();
	});

	$('.checkbox-group2').change(function () {
		$('#errorContainer2').hide();
		$('#errorContainer3').hide();
	});

})
);
