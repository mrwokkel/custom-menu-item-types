<?php
/**
 * Customize API: WP_Customize_Nav_Menu_Item_Control class
 *
 * @package WordPress
 * @subpackage Customize
 * @since 4.4.0
 */

namespace required\Custom_Menu_Item_Types;

/**
 * Customize control to represent the name field for a given menu.
 *
 * @since 4.3.0
 */
class WP_Customize_Custom_Menu_Item_Types_Control extends \WP_Customize_Nav_Menu_Item_Control {

	/**
	 * Constructor.
	 *
	 * @since 4.3.0
	 * @access public
	 *
	 * @see WP_Customize_Control::__construct()
	 *
	 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
	 * @param string               $id      The control ID.
	 * @param array                $args    Optional. Overrides class property defaults.
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );
	}

	/**
	 * JS/Underscore template for the control UI.
	 *
	 * @since 4.3.0
	 * @access public
	 */
	public function content_template() {
		?>
		<div class="menu-item-bar">
			<div class="menu-item-handle">
				<span class="item-type" aria-hidden="true">{{ data.item_type_label }}</span>
				<span class="item-title" aria-hidden="true">
					<span class="spinner"></span>
					<span class="menu-item-title<# if ( ! data.title ) { #> no-title<# } #>">{{ data.title || wp.customize.Menus.data.l10n.untitled }}</span>
				</span>
				<span class="item-controls">
					<button type="button" class="button-link item-edit" aria-expanded="false"><span class="screen-reader-text"><?php
						/* translators: 1: Title of a menu item, 2: Type of a menu item */
						printf( __( 'Edit menu item: %1$s (%2$s)' ), '{{ data.title || wp.customize.Menus.data.l10n.untitled }}', '{{ data.item_type_label }}' );
					?></span><span class="toggle-indicator" aria-hidden="true"></span></button>
					<button type="button" class="button-link item-delete submitdelete deletion"><span class="screen-reader-text"><?php
						/* translators: 1: Title of a menu item, 2: Type of a menu item */
						printf( __( 'Remove Menu Item: %1$s (%2$s)' ), '{{ data.title || wp.customize.Menus.data.l10n.untitled }}', '{{ data.item_type_label }}' );
					?></span></button>
				</span>
			</div>
		</div>

		<div class="menu-item-settings" id="menu-item-settings-{{ data.menu_item_id }}">
			<?php $nav_menu_item_fields = array(); ?>
			<?php ob_start(); ?>
			<# if ( 'custom' === data.item_type ) { #>
				<p class="field-url description description-thin">
					<label for="edit-menu-item-url-{{ data.menu_item_id }}">
						<?php _e( 'URL' ); ?><br />
						<input class="widefat code edit-menu-item-url" type="text" id="edit-menu-item-url-{{ data.menu_item_id }}" name="menu-item-url" />
					</label>
				</p>
			<# } #>
			<?php $nav_menu_item_fields['custom'] = ob_get_clean(); ?>
			<?php ob_start(); ?>
			<p class="description description-thin">
				<label for="edit-menu-item-title-{{ data.menu_item_id }}">
					<?php _e( 'Navigation Label' ); ?><br />
					<input type="text" id="edit-menu-item-title-{{ data.menu_item_id }}" class="widefat edit-menu-item-title" name="menu-item-title" />
				</label>
			</p>
			<?php $nav_menu_item_fields['title'] = ob_get_clean(); ?>
			<?php ob_start(); ?>
			<p class="field-link-target description description-thin">
				<label for="edit-menu-item-target-{{ data.menu_item_id }}">
					<input type="checkbox" id="edit-menu-item-target-{{ data.menu_item_id }}" class="edit-menu-item-target" value="_blank" name="menu-item-target" />
					<?php _e( 'Open link in a new tab' ); ?>
				</label>
			</p>
			<?php $nav_menu_item_fields['link-target'] = ob_get_clean(); ?>
			<?php ob_start(); ?>
			<p class="field-attr-title description description-thin">
				<label for="edit-menu-item-attr-title-{{ data.menu_item_id }}">
					<?php _e( 'Title Attribute' ); ?><br />
					<input type="text" id="edit-menu-item-attr-title-{{ data.menu_item_id }}" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title" />
				</label>
			</p>
			<?php $nav_menu_item_fields['attr-title'] = ob_get_clean(); ?>
			<?php ob_start(); ?>
			<p class="field-css-classes description description-thin">
				<label for="edit-menu-item-classes-{{ data.menu_item_id }}">
					<?php _e( 'CSS Classes' ); ?><br />
					<input type="text" id="edit-menu-item-classes-{{ data.menu_item_id }}" class="widefat code edit-menu-item-classes" name="menu-item-classes" />
				</label>
			</p>
			<?php $nav_menu_item_fields['css-classes'] = ob_get_clean(); ?>
			<?php ob_start(); ?>
			<p class="field-xfn description description-thin">
				<label for="edit-menu-item-xfn-{{ data.menu_item_id }}">
					<?php _e( 'Link Relationship (XFN)' ); ?><br />
					<input type="text" id="edit-menu-item-xfn-{{ data.menu_item_id }}" class="widefat code edit-menu-item-xfn" name="menu-item-xfn" />
				</label>
			</p>
			<?php $nav_menu_item_fields['xfn'] = ob_get_clean(); ?>
			<?php ob_start(); ?>
			<p class="field-description description description-thin">
				<label for="edit-menu-item-description-{{ data.menu_item_id }}">
					<?php _e( 'Description' ); ?><br />
					<textarea id="edit-menu-item-description-{{ data.menu_item_id }}" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description">{{ data.description }}</textarea>
					<span class="description"><?php _e( 'The description will be displayed in the menu if the current theme supports it.' ); ?></span>
				</label>
			</p>
			<?php $nav_menu_item_fields['description'] = ob_get_clean(); ?>

			<?php
			/**
			 * Filter the nav menu fields to edit.
			 *
			 * This filter applies on the nav menu item control template in the Customizer.
			 *
			 * @since 4.5.0
			 *
			 * @param array $nav_menu_item_fields Mapping of ID to the field paragraph HTML.
			 * @param array $context {
			 *     Context for applied filter.
			 *
			 *     @type WP_Customize_Nav_Menu_Item_Control $customize_control Customize control.
			 * }
			 */
			$nav_menu_item_fields = apply_filters( 'wp_customize_nav_menu_item_fields', $nav_menu_item_fields, array( 'customize_control' => $this ) );

			echo join( "\n", array_values( $nav_menu_item_fields ) );
			?>

			<div class="menu-item-actions description-thin submitbox">
				<# if ( ( 'post_type' === data.item_type || 'taxonomy' === data.item_type ) && '' !== data.original_title ) { #>
				<p class="link-to-original">
					<?php printf( __( 'Original: %s' ), '<a class="original-link" href="{{ data.url }}">{{ data.original_title }}</a>' ); ?>
				</p>
				<# } #>

				<button type="button" class="button-link item-delete submitdelete deletion"><?php _e( 'Remove' ); ?></button>
				<span class="spinner"></span>
			</div>
			<input type="hidden" name="menu-item-db-id[{{ data.menu_item_id }}]" class="menu-item-data-db-id" value="{{ data.menu_item_id }}" />
			<input type="hidden" name="menu-item-parent-id[{{ data.menu_item_id }}]" class="menu-item-data-parent-id" value="{{ data.parent }}" />
		</div><!-- .menu-item-settings-->
		<ul class="menu-item-transport"></ul>
		<?php
	}
}