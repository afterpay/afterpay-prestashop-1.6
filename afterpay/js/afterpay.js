$(document).ready(() => {

	changeAfterpayInstalments();

    $(".afterpay-modal-popup-trigger").fancybox({

            afterShow: function() {
                $('#afterpay-modal-popup').find(".close-afterpay-button").on("click", function(event) {
                    event.preventDefault();
                    $.fancybox.close();
                })
            }
    });

    $(document).on("change", ".attribute_select", function(e){
		changeAfterpayInstalments();
	});

	$(document).on("click", ".attribute_radio", function(e){
		changeAfterpayInstalments();
	});

	$(document).on("click", ".color_pick", function(e){
		changeAfterpayInstalments();
	});

	function changeAfterpayInstalments() {
		setTimeout( function(e) {
			current_price = $("#our_price_display").text().replace(/[^\d\.]/g, '');

			current_price = Math.round( current_price / 4 * 100) / 100;

			$(".afterpay-installments-value").text(current_price);

		}, 200);
	}
});