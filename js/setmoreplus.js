jQuery(document).ready(function ($) {
    if ( typeof( setmoreplus ) !== 'undefined') {

		/**
         * Specific elements instead of simply using
         * `.setmore-iframe` because of menu link `li > a`.
         * Try to consolidate in future version.
         */
        $(".widget .setmore-iframe")
            .add("a.setmore-iframe")
            .add("input.setmore-iframe")
            .add("li.setmore-iframe > a")
			.add('a[href$="#setmoreplus"]')
	            .colorbox(setmoreplus);

		$('a[href$="#setmoreplus"]').click(function(e){
			e.preventDefault();
		});
    }
});
