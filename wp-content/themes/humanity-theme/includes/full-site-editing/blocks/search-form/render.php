<?php

declare( strict_types = 1 );

if ( ! function_exists( 'render_search_form_block' ) ) {
	/**
	 * Render the site header block
	 *
	 * @return string
	 */
	function render_search_form_block(): string {
		spaceless();
		get_template_part( 'partials/search/horizontal-search' );

		return endspaceless( false );
	}
}
