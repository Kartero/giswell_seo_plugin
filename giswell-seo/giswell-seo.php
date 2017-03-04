<?php
/*
 Plugin Name: Giswell SEO tool
 Plugin URI:  http://giswell.fi
 Description: Lightweight Search Engine Optimization tool. Add metadata to pages without bloat
 Version:     0.1.0
 Author:      Tero Karhapää
 Author URI:  http://giswell.fi
 License:     GPL2
 License URI: https://www.gnu.org/licenses/gpl-2.0.html
 Text Domain: giswell-seo
 Domain Path: /languages
 
 Giswell SEO tool is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Giswell SEO tool is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Giswell SEO tool. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
 */

defined( 'ABSPATH' ) or die( 'No direct access, please!' );

const META_DESC = 'giswell_seo_meta_desc';
const META_KEYWORDS = 'giswell_seo_meta_keywords';

// Activation hook function
function giswell_seo_install() {
	add_option(META_DESC, 'Default text');
	add_option(META_KEYWORDS, 'Default, Base');
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'giswell_seo_install' );

// Deactivation hook function
function giswell_seo_deactivation() {
// 	delete_option( META_DESC );
// 	delete_option(META_KEYWORDS);
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'giswell_seo_deactivation' );

// Update meta
function giswell_update_meta() {
	$metadesc = get_option(META_DESC);
	$metakeys = get_option(META_KEYWORDS);
	echo '<meta name="description" content="' . $metadesc . '" />' . "\n";
	echo '<meta name="keywords" content="' . $metakeys . '" />' . "\n";
}
add_action('wp_head', 'giswell_update_meta');

// Add menu to admin and options page

function giswell_seo_menu() {
	add_options_page(
			'Giswell SEO asetukset',
			'Giswell SEO',
			'manage_options',
			'giswell-seo-menu',
			"giswell_options_page_html"
	);
}
add_action( 'admin_menu', 'giswell_seo_menu' );


function giswell_options_page_html()
{
	// check user capabilities
	if (!current_user_can('manage_options')) {
		return;
	}
	?>
    <div class="wrap">
        <h1><?= esc_html(get_admin_page_title()); ?></h1>
        <br>
        <form action="options.php" method="post">
            <?php
            settings_fields('giswell_seo_section');
            do_settings_sections('giswell-seo-menu');
            submit_button('Tallenna');
            ?>
        </form>
    </div>
    <?php
}

function giswell_seo_settings_init() {
	add_settings_section(
			'giswell_seo_section',
			'Giswell SEO setting section',
			null,
			'giswell-seo-menu'
			);

	add_settings_field(
			META_DESC,
			'Meta Description',
			'giswell_seo_meta_desc_callback',
			'giswell-seo-menu',
			'giswell_seo_section'
			);
	
	register_setting( 'giswell_seo_section', META_DESC );
	
	add_settings_field(
			META_KEYWORDS,
			'Meta Keywords',
			'giswell_seo_meta_keys_callback',
			'giswell-seo-menu',
			'giswell_seo_section'
			);
	
	register_setting( 'giswell_seo_section', META_KEYWORDS );
}

add_action( 'admin_init', 'giswell_seo_settings_init' );

function giswell_seo_meta_desc_callback() {
	?>
	<textarea maxlength="160" name="<?php echo META_DESC; ?>" id="<?php echo META_DESC; ?>" rows="6" style="width: 50%;"><?php echo get_option(META_DESC); ?></textarea>
	<?php
}

function giswell_seo_meta_keys_callback() {
	?>
	<textarea maxlength="160" name="<?php echo META_KEYWORDS; ?>" id="<?php echo META_KEYWORDS; ?>" rows="6" style="width: 50%;"><?php echo get_option(META_KEYWORDS); ?></textarea>
	<?php
}














