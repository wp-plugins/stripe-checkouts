<?php
/**
 *
 * @package   Simple Stripe Checkout Companion
 * @author    Kyle M. Brown <kyle@kylembrown.com>
 * @license   GPL-2.0+
 * @link      http://kylembrown.com/stripe-checkout-pro-companion
 * @copyright 2014 Kyle M. Brown
 *
 * @wordpress-plugin
 * Plugin Name:       Simple Stripe Checkout Companion
 * Plugin URI:        http://kylembrown.com/stripe-checkout-pro-addon
 * Description:       The Stripe Checkout Pro Companion add-on makes is easy to insert shortcodes into your WordPress editor post and pages.
 * Version:           1.2.6
 * Author:            Kyle M. Brown
 * Author URI:        http://kylembrown.com/stripe-checkout-pro-companion
 * Text Domain:       simple-stripe-checkout-companion
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

/* Start Plugin Code */

/* Check it SCP is installed */
add_action( 'admin_init', 'stripe_inactive_notice');
function stripe_inactive_notice()
{
	if ( ! class_exists( 'Stripe_Checkout' ) ) {
		deactivate_plugins(plugin_basename( __FILE__ ));
		wp_die( sprintf( __(  'Stripe Checkout Companion requires Simple Stripe Checkout to run properly. Please install Simple Stripe Checkout before activating this plugin. <a href="%s">Return to Plugins</a>'), get_admin_url( '', 'plugins.php') ) );
	}
}

register_activation_hook(  __FILE__, 'install_stripe_companion');
register_uninstall_hook( __FILE__, 'uninstall_stripe_companion' );
add_action('init','install_stripe_companion');

function install_stripe_companion(){
	add_action('admin_menu', 'wpautop_control_menu');		
			
}
function wpautop_control_menu()
{
  add_submenu_page('stripe-checkout', 'Companion', 'Companion', 'manage_options', 'stripe-checkout-pro-companion', 'scpc_page_kyle');	 
}

add_action('init', 'scpc_add_mce_button');

function scpc_add_mce_button() {
	if( current_user_can('edit_posts') &&  current_user_can('edit_pages') ){
		add_filter( 'mce_external_plugins', 'pu_add_buttons');
		add_filter( 'mce_buttons', 'pu_register_buttons');
	}
}
			
function pu_add_buttons( $plugin_array ){
  $plugin_array['pushortcodes'] = plugin_dir_url( __FILE__ ) . '/js/scpc-tinymce-button.js';
  return $plugin_array;
}

function pu_register_buttons( $buttons ){
	array_push( $buttons, 'separator', 'pushortcodes' );
	return $buttons;
}

function scpc_mce_css() {
	wp_enqueue_style('stripeaddon_shortcodes-tc', plugins_url('/css/scpc_tinymce_style.css', __FILE__) );
}
add_action( 'admin_enqueue_scripts', 'scpc_mce_css' );

function uninstall_stripe_companion(){	
	deactivate_plugins(plugin_basename( __FILE__ ));
}

function scpc_page_kyle(){
	//Testing realtime source delivery of shortcodes
	echo '<iframe src="http://wpstripe.net/docs/shortcodes/stripe-checkout/" width="100%" height="800"></iframe>';
}

function scpc_page(){/* Original page listing codes replaced with scpc_page-kyle */ ?>
<style>
.row-1.odd {
    text-align: left;
}
</style>
	<div class="wrap">
		<h2>List all shortcode</h2>
		
		<p><strong>Shortcode Options</strong></p>
		<p>Below are the available options (attributes) for this shortcode.</p>

		<table class="tablepress tablepress-id-1" id="tablepress-1">
		<thead>
		<tr class="row-1 odd">
			<th class="column-1"><div>Option</div></th><th class="column-2"><div>Description</div></th><th class="column-3"><div>Default</div></th>
		</tr>
		</thead>
		<tbody>
		<tr class="row-2 even">
			<td class="column-1">name</td><td class="column-2">The name of your company or website.</td><td class="column-3">Site title</td>
		</tr>
		<tr class="row-3 odd">
			<td class="column-1">description</td><td class="column-2">A description of the product or service being purchased (optional).</td><td class="column-3"></td>
		</tr>
		<tr class="row-4 even">
			<td class="column-1">amount</td><td class="column-2">Amount in desired currency. Use smallest common currency unit U.S. amounts are in cents. Amount is required.</td><td class="column-3"></td>
		</tr>
		<tr class="row-5 odd">
			<td class="column-1">image_url</td><td class="column-2">A URL pointing to a square image of your brand or product. The recommended minimum size is 128x128px.</td><td class="column-3"></td>
		</tr>
		<tr class="row-6 even">
			<td class="column-1">currency</td><td class="column-2">Specify a specific currency by using it's 3-letter ISO code.</td><td class="column-3">USD</td>
		</tr>
		<tr class="row-7 odd">
			<td class="column-1">payment_button_label</td><td class="column-2">Text to display on the default payment button that launches the checkout overlay.</td><td class="column-3">Pay with Card</td>
		</tr>
		<tr class="row-8 even">
			<td class="column-1">billing</td><td class="column-2">Used to gather the billing address during the checkout process. (true or false)</td><td class="column-3">false</td>
		</tr>
		<tr class="row-9 odd">
			<td class="column-1">shipping</td><td class="column-2">Used to gather the shipping address during the checkout process. (true or false)</td><td class="column-3">false</td>
		</tr>
		<tr class="row-10 even">
			<td class="column-1">enable_remember</td><td class="column-2">Adds a "remember me" checkbox to the checkout form. (true or false)</td><td class="column-3">true</td>
		</tr>
		<tr class="row-11 odd">
			<td class="column-1">checkout_button_label</td><td class="column-2">Text to display on the final checkout button within the checkout overlay form. Insert {{amount}} where you'd like to display the amount. If {{amount}} is omitted, it will be appended at the end of the button label.</td><td class="column-3">Pay {{amount}}</td>
		</tr>
		<tr class="row-12 even">
			<td class="column-1">success_redirect_url</td><td class="column-2">The URL that the user should be redirected to after a <strong>successful</strong> payment.</td><td class="column-3">Originating page</td>
		</tr>
		<tr class="row-13 odd">
			<td class="column-1">failure_redirect_url</td><td class="column-2">The URL that the user should be redirected to after a <strong>failed</strong> payment.</td><td class="column-3">Originating page</td>
		</tr>
		<tr class="row-14 even">
			<td class="column-1">prefill_email</td><td class="column-2">Prefill the email address box with the email address of the current logged in user.</td><td class="column-3">false</td>
		</tr>
		<tr class="row-15 odd">
			<td class="column-1">verify_zip</td><td class="column-2">Verifies the zipcode of the card. You'll also need to enable this in your Stripe.com account and check the box to decline charges that fail zip code verification).</td><td class="column-3">false</td>
		</tr>
		<tr class="row-16 even">
			<td class="column-1">test_mode</td><td class="column-2">Puts this particular form into test mode even if live mode is selected in the main settings. </td><td class="column-3">false</td>
		</tr>
		<tr class="row-17 odd">
			<td class="column-1">payment_button_style</td><td class="column-2"><strong>(Pro only)</strong> Set to "stripe" to use Stripe's button styles. Set to "none" to ignore Stripe's button styles. Base button CSS class: <code>sc-payment-btn</code>.</td><td class="column-3">stripe</td>
		</tr>
		</tbody>
		</table>

		<p><strong>Basic product checkout:</strong></p>

		<ul style="list-style: disc outside none;margin-left: 50px;">
			<li>[stripe name="My Store" description="My Product" amount="1999"]</li>

			<li>Require shipping and billing addresses:<br/>
				[stripe name="My Store" description="My Product" amount="1999" shipping="true" billing="true"]</li>

			<li>Use a custom image on overlay:<br/>
				[stripe name="My Store" description="My Product" amount="1999" image_url="http://www.example.com/book_image.jpg"]</li>

			<li>Change the checkout button label and disable option to remember Stripe login:<br/>
				[stripe name="My Store" description="My Product" amount="1999" checkout_button_label="Now only {{amount}}!" enable_remember="false"]</li>

			<li>Pre-fill email of current logged in user:<br/>
				[stripe name="My Store" description="My Product" amount="1999" prefill_email="true"]</li>

			<li>Set to Mexican Peso currency:<br/>
				[stripe name="My Store" description="My Product" amount="250" currency="MXN"]</li>
		</ul>
	</div>

<?php
}
?>