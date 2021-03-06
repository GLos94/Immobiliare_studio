<?php
/**
 * ElasticPress WooCommerce feature
 *
 * @since  2.1
 * @package elasticpress
 */

/**
 * Index Woocommerce meta
 *
 * @param   array $meta Existing post meta.
 * @param   array $post Post arguments array.
 * @since   2.1
 * @return  array
 */
function ep_wc_whitelist_meta_keys( $meta, $post ) {
	return array_unique( array_merge( $meta, array(
		'_thumbnail_id',
		'_product_attributes',
		'_wpb_vc_js_status',
		'_swatch_type',
		'total_sales',
		'_downloadable',
		'_virtual',
		'_regular_price',
		'_sale_price',
		'_tax_status',
		'_tax_class',
		'_purchase_note',
		'_featured',
		'_weight',
		'_length',
		'_width',
		'_height',
		'_visibility',
		'_sku',
		'_sale_price_dates_from',
		'_sale_price_dates_to',
		'_price',
		'_sold_individually',
		'_manage_stock',
		'_backorders',
		'_stock',
		'_upsell_ids',
		'_crosssell_ids',
		'_stock_status',
		'_product_version',
		'_product_tabs',
		'_override_tab_layout',
		'_suggested_price',
		'_min_price',
		'_customer_user',
		'_variable_billing',
		'_wc_average_rating',
		'_product_image_gallery',
		'_bj_lazy_load_skip_post',
		'_min_variation_price',
		'_max_variation_price',
		'_min_price_variation_id',
		'_max_price_variation_id',
		'_min_variation_regular_price',
		'_max_variation_regular_price',
		'_min_regular_price_variation_id',
		'_max_regular_price_variation_id',
		'_min_variation_sale_price',
		'_max_variation_sale_price',
		'_min_sale_price_variation_id',
		'_max_sale_price_variation_id',
		'_default_attributes',
		'_swatch_type_options',
		'_order_key',
		'_billing_company',
		'_billing_address_1',
		'_billing_address_2',
		'_billing_city',
		'_billing_postcode',
		'_billing_country',
		'_billing_state',
		'_billing_email',
		'_billing_phone',
		'_shipping_address_1',
		'_shipping_address_2',
		'_shipping_city',
		'_shipping_postcode',
		'_shipping_country',
		'_shipping_state',
		'_billing_last_name',
		'_billing_first_name',
		'_shipping_first_name',
		'_shipping_last_name',
	) ) );
}

/**
 * Prevent order fields search meta query
 *
 * @since  2.1
 */
function ep_wc_shop_order_search_fields() {
	return array();
}

/**
 * Make sure all loop shop post ins are IDS. We have to pass post objects here since we override
 * the fields=>id query for the layered filter nav query
 *
 * @param   array $posts Post object array.
 * @since   2.1
 * @return  array
 */
function ep_wc_convert_post_object_to_id( $posts ) {
	$new_posts = array();

	foreach ( $posts as $post ) {
		if ( is_object( $post ) ) {
			$new_posts[] = $post->ID;
		} else {
			$new_posts[] = $post;
		}
	}

	return $new_posts;
}


/**
 * Index Woocommerce taxonomies
 *
 * @param   array $taxonomies Index taxonomies array.
 * @param   array $post Post properties array.
 * @since   2.1
 * @return  array
 */
function ep_wc_whitelist_taxonomies( $taxonomies, $post ) {
	$woo_taxonomies = array();

	$product_type       = get_taxonomy( 'product_type' );
	if( false !== $product_type ){
		$woo_taxonomies[] = $product_type;
	}

	$product_visibility = get_taxonomy( 'product_visibility' );
	if( false !== $product_visibility ){
		$woo_taxonomies[] = $product_visibility;
	}

	/**
	 * Note product_shipping_class, product_cat, and product_tag are already public. Make
	 * sure to index non-attribute taxonomies.
	 */
	if ( $attribute_taxonomies = wc_get_attribute_taxonomies() ) {
		foreach ( $attribute_taxonomies as $tax ) {
			if ( $name = wc_attribute_taxonomy_name( $tax->attribute_name ) ) {
				if ( empty( $tax->attribute_) ) {
					$woo_taxonomies[] = get_taxonomy( $name );
				}
			}
		}
	}

	return array_merge( $taxonomies, $woo_taxonomies );
}

/**
 * Disallow duplicated ES queries on Orders page.
 *
 * @since 2.4
 *
 * @param array    $value Original filter values.
 * @param WP_Query $query WP_Query
 *
 * @return array
 */
function ep_wc_disallow_duplicated_query( $value, $query ) {
	global $pagenow;

	/**
	 * Make sure we're on edit.php in admin dashboard.
	 */
	if ( 'edit.php' !== $pagenow || ! is_admin() || 'shop_order' !== $query->get( 'post_type' ) ) {
		return $value;
	}

	/**
	 * Check if EP API request was already done. If request was sent return its results.
	 */
	if ( isset( $query->elasticsearch_success ) && $query->elasticsearch_success ) {
		return $query->posts;
	}

	return $value;

}

/**
 * Translate args to ElasticPress compat format. This is the meat of what the feature does
 *
 * @param  WP_Query $query
 * @since  2.1
 */
function ep_wc_translate_args( $query ) {

	// Lets make sure this doesn't interfere with the CLI
	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		return;
	}

	if ( apply_filters( 'ep_skip_query_integration', false, $query ) ||
	     ( isset( $query->query_vars['ep_integrate'] ) && false === $query->query_vars['ep_integrate'] ) ) {
		return;
	}

	$admin_integration = apply_filters( 'ep_admin_wp_query_integration', false );

	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		if ( ! apply_filters( 'ep_ajax_wp_query_integration', false ) ) {
			return;
		} else {
			$admin_integration = true;
		}
	}

	if ( is_admin() && ! $admin_integration ) {
		return;
	}

	$product_name = $query->get( 'product', false );

	$post_parent = $query->get( 'post_parent', false );

	/**
	 * Do nothing for single product queries
	 */
	if ( ! empty( $product_name ) || $query->is_single() ) {
		return;
	}

	/**
	 * ElasticPress does not yet support post_parent queries
	 */
	if ( ! empty( $post_parent ) ) {
		return;
	}

	/**
	 * If this is just a preview, let's not use Elasticsearch.
	 */
	if ( $query->get( 'preview', false ) ) {
		return;
	}

	/**
	 * Cant hook into WC API yet
	 */
	if ( defined( 'WC_API_REQUEST' ) && WC_API_REQUEST ) {
		return;
	}

	// Flag to check and make sure we are in a WooCommerce specific query
	$integrate = false;

	/**
	 * Force ElasticPress if we are querying WC taxonomy
	 */
	$tax_query = $query->get( 'tax_query', array() );

	$supported_taxonomies = array(
		'product_cat',
		'pa_brand',
		'product_tag',
		'product_type',
		'pa_sort-by',
		'product_visibility',
		'product_shipping_class',
	);

	/**
	 * Add support for custom taxonomies.
	 *
	 * @param array $supported_taxonomies An array of default taxonomies.
	 *
	 * @since 2.3.0
	 */
	$supported_taxonomies = apply_filters( 'ep_woocommerce_supported_taxonomies', $supported_taxonomies );

	if ( ! empty( $tax_query ) ) {

		/**
		 * First check if already set taxonomies are supported WC taxes
		 */
		foreach ( $tax_query as $taxonomy_array ) {
			if ( isset( $taxonomy_array['taxonomy'] ) && in_array( $taxonomy_array['taxonomy'], $supported_taxonomies ) ) {
				$integrate = true;
			}
		}
	}

	/**
	 * Next check if any taxonomies are in the root of query vars (shorthand form)
	 */
	foreach ( $supported_taxonomies as $taxonomy ) {
		$term = $query->get( $taxonomy, false );

		if ( ! empty( $term ) ) {
			$integrate = true;

			$terms          = (array)$term;
			$children_terms = array();

			// to add child terms to the tax query
			if ( is_taxonomy_hierarchical( $taxonomy ) ) {
				foreach ( $terms as $term ) {
					$term_object = get_term_by( 'slug', $term, $taxonomy );
					if ( $term_object && property_exists( $term_object, 'term_id' ) ) {
						$children = get_term_children( $term_object->term_id, $taxonomy );
						if ( $children ) {
							foreach ( $children as $child ) {
								$child_object = get_term( $child, $taxonomy );
								if ( $child_object && ! is_wp_error( $child_object ) && property_exists( $child_object, 'slug' ) ) {
									$children_terms[] = $child_object->slug;
								}
							}
						}
					}
				}
			}
			$terms = array_merge( $terms, $children_terms );
			$tax_query[] = array(
				'taxonomy' => $taxonomy,
				'field'    => 'slug',
				'terms'    => $terms,
			);
		}
	}

	/**
	 * Force ElasticPress if product post type query
	 */
	$post_type = $query->get( 'post_type', false );

	// Act only on a defined subset of all indexable post types here
	$supported_post_types = array_intersect(
		array(
			'product',
			'shop_order',
			'shop_order_refund',
			'product_variation'
		),
		ep_get_indexable_post_types()
	);

	// For orders it queries an array of shop_order and shop_order_refund post types, hence an array_diff
	if ( ! empty( $post_type ) && ( in_array( $post_type, $supported_post_types ) || ( is_array( $post_type ) && ! array_diff( $post_type, $supported_post_types ) ) ) ) {
		$integrate = true;
	}

	/**
	 * If we have a WooCommerce specific query, lets hook it to ElasticPress and make the query ElasticSearch friendly
	 */
	if ( $integrate ) {
		// Set tax_query again since we may have added things
		$query->set( 'tax_query', $tax_query );

		// Default to product if no post type is set
		if ( empty( $post_type ) ) {
			$post_type = 'product';
			$query->set( 'post_type', 'product' );
		}

		// Handles the WC Top Rated Widget
		if ( has_filter( 'posts_clauses', array( WC()->query, 'order_by_rating_post_clauses' ) ) ) {
			remove_filter( 'posts_clauses', array( WC()->query, 'order_by_rating_post_clauses' ) );
			$query->set( 'orderby', 'meta_value_num' );
			$query->set( 'meta_key', '_wc_average_rating' );
		}

		/**
		 * WordPress have to be version 4.6 or newer to have "fields" support
		 * since it requires the "posts_pre_query" filter.
		 *
		 * @see WP_Query::get_posts
		 */
		$fields = $query->get( 'fields', false );
		if ( ! version_compare( get_bloginfo( 'version' ), '4.6', '>=' ) && ( 'ids' === $fields || 'id=>parent' === $fields ) ) {
			$query->set( 'fields', 'default' );
		}

		/**
		 * Handle meta queries
		 */
		$meta_query = $query->get( 'meta_query', array() );
		$meta_key = $query->get( 'meta_key', false );
		$meta_value = $query->get( 'meta_value', false );

		if ( ! empty( $meta_key ) && ! empty( $meta_value ) ) {
			$meta_query[] = array(
				'key' => $meta_key,
				'value' => $meta_value,
			);

			$query->set( 'meta_query', $meta_query );
		}

		/**
		 * Make sure filters are suppressed
		 */
		$query->query['suppress_filters'] = false;
		$query->set( 'suppress_filters', false );

		$orderby = $query->get( 'orderby' );

		if ( ! empty( $orderby ) && 'rand' === $orderby ) {
			$query->set( 'orderby', false ); // Just order by relevance.
		}

		$s = $query->get( 's' );

		$query->query_vars['ep_integrate'] = true;
		$query->query['ep_integrate'] = true;

		if ( ! empty( $s ) ) {
			$query->set( 'orderby', false ); // Just order by relevance.

			/**
			 * Default order when doing search in Woocommerce is 'ASC'
			 * These lines will change it to 'DESC' as we want to most relevant result
			 */
			if ( empty( $_GET['orderby'] ) && $query->is_main_query() ) {
				$query->set( 'order', 'DESC' );
			}

			// Search query
			if ( 'shop_order' === $post_type ) {
				$search_fields = $query->get( 'search_fields', array( 'post_title', 'post_content', 'post_excerpt' ) );

				$search_fields['meta'] = array_map( 'wc_clean', apply_filters( 'shop_order_search_fields', array(
					'_order_key',
					'_billing_company',
					'_billing_address_1',
					'_billing_address_2',
					'_billing_city',
					'_billing_postcode',
					'_billing_country',
					'_billing_state',
					'_billing_email',
					'_billing_phone',
					'_shipping_address_1',
					'_shipping_address_2',
					'_shipping_city',
					'_shipping_postcode',
					'_shipping_country',
					'_shipping_state',
					'_billing_last_name',
					'_billing_first_name',
					'_shipping_first_name',
					'_shipping_last_name',
					'_items',
				) ) );

				$query->set( 'search_fields', $search_fields );
			} elseif ( 'product' === $post_type ) {
				$search_fields = $query->get( 'search_fields', array( 'post_title', 'post_content', 'post_excerpt' ) );

				// Remove author_name from this search.
				$search_fields = ep_wc_remove_author($search_fields);

				// Make sure we search skus on the front end
				$search_fields['meta'] = array( '_sku' );

				// Search by proper taxonomies on the front end
				$search_fields['taxonomies'] = array( 'category', 'post_tag', 'product_tag', 'product_cat' );

				$query->set( 'search_fields', $search_fields );
			}
		} else {
			/**
			 * For default sorting by popularity (total_sales) and rating
	         * Woocommerce doesn't set the orderby correctly.
	         * These lines will check the meta_key and correct the orderby based on that.
	         * And this won't run in search result and only run in main query
			 */
			$meta_key = $query->get( 'meta_key', false );
			if ( $meta_key && $query->is_main_query() ){
				switch ( $meta_key ){
					case 'total_sales':
						$query->set( 'orderby', ep_wc_get_orderby_meta_mapping( 'total_sales' ) );
						$query->set( 'order', 'DESC' );
						break;
					case '_wc_average_rating':
						$query->set( 'orderby', ep_wc_get_orderby_meta_mapping( '_wc_average_rating' ) );
						$query->set( 'order', 'DESC' );
						break;
				}
			}
		}

		/**
		 * Set orderby from GET param
		 * Also make sure the orderby param affects only the main query
		 */
		if ( ! empty( $_GET['orderby'] ) && $query->is_main_query() ) {

			switch ( $_GET['orderby'] ) {
				case 'popularity':
					$query->set( 'orderby', ep_wc_get_orderby_meta_mapping( 'total_sales' ) );
					$query->set( 'order', 'DESC' );
					break;
				case 'price':
					$query->set( 'order', 'ASC' );
					$query->set( 'orderby', ep_wc_get_orderby_meta_mapping( '_price' ) );
					break;
				case 'price-desc':
					$query->set( 'order', 'DESC' );
					$query->set( 'orderby', ep_wc_get_orderby_meta_mapping( '_price' ) );
					break;
				case 'rating' :
					$query->set( 'orderby', ep_wc_get_orderby_meta_mapping( '_wc_average_rating' ) );
					$query->set( 'order', 'DESC' );
					break;
				case 'date':
					$query->set( 'orderby', ep_wc_get_orderby_meta_mapping( 'date' ) );
					break;
				case 'ID':
					$query->set( 'orderby', ep_wc_get_orderby_meta_mapping( 'ID' ) );
					break;
				default:
					$query->set( 'orderby', ep_wc_get_orderby_meta_mapping( 'menu_order' ) ); // Order by menu and title.
			}
		}
	}
}

/**
 * Fetch the ES related meta mapping for orderby
 *
 * @param  $meta_key The meta key to get the mapping for.
 * @since  2.1
 * @return string    The mapped meta key.
 */
function ep_wc_get_orderby_meta_mapping( $meta_key ) {
	$mapping = apply_filters( 'orderby_meta_mapping',
		array(
			'ID'				 => 'ID',
			'menu_order'         => 'menu_order title date',
			'menu_order title'   => 'menu_order title date',
			'total_sales'        => 'meta.total_sales.long date',
			'_wc_average_rating' => 'meta._wc_average_rating.double date',
			'_price'             => 'meta._price.long date',
		) );

	if ( isset( $mapping[ $meta_key ] ) ) {
		return $mapping[ $meta_key ];
	}

	return 'date';
}

/**
 * Don't index legacy meta property. We want to to keep things light ot save space and memory.
 *
 * @param   array $post_args Post arguments to be indexed in ES.
 * @param   int   $post_id Post ID.
 * @since   2.1
 * @return  array
 */
function ep_wc_remove_legacy_meta( $post_args, $post_id ) {
	if ( ! empty( $post_args['post_meta'] ) ) {
		unset( $post_args['post_meta'] );
	}

	return $post_args;
}

/**
 * Make search coupons don't go through ES
 *
 * @param  bool $enabled
 * @param  object $query
 * @since  2.1
 * @return bool
 */
function ep_wc_blacklist_coupons( $enabled, $query ) {
	if ( method_exists( $query, 'get' ) && 'shop_coupon' === $query->get( 'post_type' ) ) {
		return false;
	}

	return $enabled;
}

/**
 * Allow order creations on the front end to get synced
 *
 * @since  2.1
 * @param  bool $override
 * @param  int $post_id
 * @return bool
 */
function ep_wc_bypass_order_permissions_check( $override, $post_id ) {
	if ( 'shop_order' === get_post_type( $post_id ) ) {
		return true;
	}

	return $override;
}

/**
 * Enhance WooCommerce search order by order id, email, phone number, name, etc..
 * What this function does:
 * 1. Reverse the woocommerce shop_order_search_custom_fields query
 * 2. If the search key is integer and it is an Order Id, just query with post__in
 * 3. If the search key is integer but not an order id ( might be phone number ), use ES to find it
 *
 * @param WP_Query $wp
 * @since  2.3
 */
function ep_wc_search_order( $wp ){
	global $pagenow;
	if ( 'edit.php' != $pagenow || empty( $wp->query_vars['post_type'] ) || 'shop_order' !== $wp->query_vars['post_type'] ||
	     ( empty( $wp->query_vars['s'] ) && empty( $wp->query_vars['shop_order_search'] ) ) ) {
		return;
	}

	$search_key_safe = str_replace( array( 'Order #', '#' ), '', wc_clean( $_GET['s'] ) );
	$order_id        = absint( $search_key_safe );

	/**
	 * Order ID 0 is not valid value.
	 */
	$order = $order_id > 0 ? wc_get_order( $order_id ) : false;

	//If the order doesn't exist, fallback to other fields
	if ( ! $order ) {
		unset( $wp->query_vars['post__in'] );
		$wp->query_vars['s'] = $search_key_safe;
	} else {
		//we found the order. don't query ES
		unset( $wp->query_vars['s'] );
		$wp->query_vars['post__in'] = array( absint( $search_key_safe ) );
	}
}

/**
 * Add order items as a searchable string.
 *
 * This mimics how WooCommerce currently does in the order_itemmeta
 * table. They combine the titles of the products and put them in a
 * meta field called "Items".
 *
 * @since 2.4
 *
 * @param array $post_args
 * @param string|int $post_id
 *
 * @return array
 */
function ep_wc_add_order_items_search( $post_args, $post_id ) {
	// Make sure it is only WooCommerce orders we touch.
	if ( 'shop_order' !== $post_args['post_type'] ) {
		return $post_args;
	}

	// Get order items.
	$order     = wc_get_order( $post_id );
	$item_meta = array();
	foreach ( $order->get_items() as $delta => $product_item ) {
		// WooCommerce 3.x uses WC_Order_Item_Product instance while 2.x an array
		if ( is_object( $product_item ) && method_exists( $product_item, 'get_name' ) ) {
			$item_meta['_items'][] = $product_item->get_name( 'edit' );
		} elseif ( is_array( $product_item ) && isset( $product_item[ 'name' ] ) ) {
			$item_meta['_items'][] = $product_item[ 'name' ];
		}
	}

	// Prepare order items.
	$item_meta['_items'] = empty( $item_meta['_items'] ) ? '' : implode( '|', $item_meta['_items'] );
	$post_args['meta'] = array_merge( $post_args['meta'], EP_API::factory()->prepare_meta_types( $item_meta ) );

	return $post_args;
}

/**
 * Setup all feature filters
 *
 * @since  2.1
 */
function ep_wc_setup() {
	if( function_exists( 'WC' ) ) {
		add_filter( 'ep_sync_insert_permissions_bypass', 'ep_wc_bypass_order_permissions_check', 10, 2 );
		add_filter( 'ep_elasticpress_enabled', 'ep_wc_blacklist_coupons', 10 ,2 );
		add_filter( 'ep_prepare_meta_allowed_protected_keys', 'ep_wc_whitelist_meta_keys', 10, 2 );
		add_filter( 'woocommerce_shop_order_search_fields', 'ep_wc_shop_order_search_fields', 9999 );
		add_filter( 'woocommerce_layered_nav_query_post_ids', 'ep_wc_convert_post_object_to_id', 10, 4 );
		add_filter( 'woocommerce_unfiltered_product_ids', 'ep_wc_convert_post_object_to_id', 10, 4 );
		add_filter( 'ep_sync_taxonomies', 'ep_wc_whitelist_taxonomies', 10, 2 );
		add_filter( 'ep_post_sync_args_post_prepare_meta', 'ep_wc_remove_legacy_meta', 10, 2 );
		add_filter( 'ep_post_sync_args_post_prepare_meta', 'ep_wc_add_order_items_search', 20, 2 );
		add_filter( 'ep_term_suggest_post_type', 'ep_wc_add_post_type' );
		add_action( 'pre_get_posts', 'ep_wc_translate_args', 11, 1 );
		add_action( 'ep_wp_query_search_cached_posts', 'ep_wc_disallow_duplicated_query', 10, 2 );
		add_action( 'parse_query', 'ep_wc_search_order', 11, 1 );
	}
}

/**
 * Output feature box summary
 *
 * @since 2.1
 */
function ep_wc_feature_box_summary() {
	?>
	<p><?php esc_html_e( '???I want a cotton, woman???s t-shirt, for under $15 that???s in stock.??? Faceted product browsing strains servers and increases load times. Your buyers can find the perfect product quickly, and buy it quickly.', 'elasticpress' ); ?></p>
	<?php
}

/**
 * Output feature box long
 *
 * @since 2.1
 */
function ep_wc_feature_box_long() {
	?>
	<p><?php esc_html_e( 'Most caching and performance tools can???t keep up with the nearly infinite ways your visitors might filter or navigate your products. No matter how many products, filters, or customers you have, ElasticPress will keep your online store performing quickly. If used in combination with the Protected Content feature, ElasticPress will also accelerate order searches and back end product management.', 'elasticpress' ); ?></p>
	<?php
}

/**
 * Determine WC feature reqs status
 *
 * @param  EP_Feature_Requirements_Status $status
 * @since  2.2
 * @return EP_Feature_Requirements_Status
 */
function ep_wc_requirements_status( $status ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		$status->code = 2;
		$status->message = esc_html__( 'WooCommerce not installed.', 'elasticpress' );
	}

	return $status;
}

/**
 * Remove the author_name from search fields.
 *
 * @param array $search_fields Array of search fields.
 *
 * @return array
 */
function ep_wc_remove_author( $search_fields ) {
	foreach ( $search_fields as $field_key => $field ) {
		if ( 'author_name' === $field ) {
			unset( $search_fields[ $field_key ] );
		}
	}

	return $search_fields;
}

/**
 * Add the product post type to an array of post types.
 * Use with filters.
 *
 * @param array $post_types Array of post types (e.g. post, page).
 *
 * @return array
 */
function ep_wc_add_post_type( $post_types ) {
	if ( ! in_array( 'product', $post_types, true ) ) {
		$post_types[] = 'product';
	}

	return $post_types;
}

/**
 * Register the feature
 */
ep_register_feature( 'woocommerce', array(
	'title' => 'WooCommerce',
	'setup_cb' => 'ep_wc_setup',
	'requirements_status_cb' => 'ep_wc_requirements_status',
	'feature_box_summary_cb' => 'ep_wc_feature_box_summary',
	'feature_box_long_cb' => 'ep_wc_feature_box_long',
	'requires_install_reindex' => true,
) );

