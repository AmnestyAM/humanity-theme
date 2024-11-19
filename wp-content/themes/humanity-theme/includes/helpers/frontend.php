<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_render_custom_select' ) ) {
	/**
	 * Render an accessible custom select-type element
	 *
	 * @package Amnesty
	 *
	 * @param array $params {
	 *     @type string $name    the identifier for the select
	 *     @type string $label   the label shown for the select
	 *     @type bool   $is_form whether to output a form or a div
	 *     @type string $type    type of form. used when $is_form = true. accepts 'nav', 'filter'
	 *     @type string $active  the pre-selected option's value
	 *     @type array  $options the list of options in value=>label pairs
	 * }
	 *
	 * @return void
	 */
	function amnesty_render_custom_select( array $params = [] ): void {
		$params_to_attributes = [
			'active'     => 'active',
			'class'      => 'className',
			'disabled'   => 'isDisabled',
			'is_control' => 'isControl',
			'is_form'    => 'isForm',
			'is_nav'     => 'isNav',
			'label'      => 'label',
			'multiple'   => 'multiple',
			'name'       => 'name',
			'options'    => 'options',
			'show_label' => 'showLabel',
			'type'       => 'type',
		];

		$params = wp_parse_args(
			$params,
			[
				/* translators: [front] AM not sure yet */
				'label'      => __( 'Choose an option', 'amnesty' ),
				'show_label' => false,
				'name'       => amnesty_rand_str( 8 ),
				'is_form'    => false,
				'is_control' => true,
				'is_nav'     => false,
				'multiple'   => false,
				'disabled'   => false,
				'active'     => '',
				'class'      => '',
				'type'       => 'filter',
				'options'    => [
					/* translators: [front] AM not sure yet */
					'' => __( 'Choose an option', 'amnesty' ),
				],
			]
		);

		$attributes = [];
		foreach ( $params as $key => $value ) {
			if ( isset( $params_to_attributes[ $key ] ) ) {
				$attributes[ $params_to_attributes[ $key ] ] = $value;
			}
		}

		$block = '<!-- wp:amnesty-core/custom-select ' . wp_json_encode( $attributes ) . ' /-->';

		echo wp_kses( do_blocks( $block ), 'filter' );
	}
}

if ( ! function_exists( 'amnesty_get_query_var' ) ) {
	/**
	 * Retrieve a query variable
	 *
	 * Prefers WP_Query query vars, falls back to WP query vars
	 *
	 * @package Amnesty
	 *
	 * @param string $variable the variable to retrieve
	 *
	 * @return mixed
	 */
	function amnesty_get_query_var( string $variable ) {
		/** Global WP object @var \WP $wp */
		global $wp;

		return get_query_var( $variable ) ?: ( $wp->query_vars[ $variable ] ?? '' );
	}
}

if ( ! function_exists( 'amnesty_render_blocks' ) ) {
	/**
	 * Render an array of blocks
	 *
	 * @package Amnesty
	 *
	 * @param array<int,array<string,mixed>> $blocks the blocks to render
	 *
	 * @return string
	 */
	function amnesty_render_blocks( array $blocks ): string {
		return implode( '', array_map( 'render_block', $blocks ) );
	}
}

if ( ! function_exists( 'amnesty_get_sidebar_id' ) ) {
	/**
	 * Get the correct sidbar ID for the current object
	 *
	 * @return int
	 */
	function amnesty_get_sidebar_id(): int {
		// post-level override
		$sidebar_id = absint( get_post_meta( get_the_ID(), '_sidebar_id', true ) );

		if ( $sidebar_id ) {
			return $sidebar_id;
		}

		// global default
		return match ( get_post_type() ) {
			'page'  => absint( amnesty_get_option( '_default_sidebar_page' )[0] ?? 0 ),
			default => absint( amnesty_get_option( '_default_sidebar' )[0] ?? 0 ),
		};
	}
}

if ( ! function_exists( 'amnesty_is_sidebar_available' ) ) {
	/**
	 * Check whether a sidebar is available to render for the current object
	 *
	 * @return bool
	 */
	function amnesty_is_sidebar_available(): bool {
		$sidebar = get_post( amnesty_get_sidebar_id() );

		return is_a( $sidebar, WP_Post::class ) &&
			'publish' === $sidebar->post_status &&
			'sidebar' === $sidebar->post_type;
	}
}
