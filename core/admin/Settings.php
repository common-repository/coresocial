<?php

namespace Dev4Press\Plugin\coreSocial\Admin;

use Dev4Press\Plugin\coreSocial\Basic\Helper;
use Dev4Press\v50\Core\Options\Element as EL;
use Dev4Press\v50\Core\Options\Settings as BaseSettings;
use Dev4Press\v50\Core\Options\Type;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Settings extends BaseSettings {
	public static function instance() : Settings {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new Settings();
		}

		return $instance;
	}

	protected function value( $name, $group = 'settings', $default = null ) {
		return coresocial_settings()->get( $name, $group, $default );
	}

	protected function init() {
		$this->settings = array(
			'basic'        => array(
				'basic_log'    => array(
					'name'     => __( 'Logging', 'coresocial' ),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'settings', 'log_logging_active', __( 'Active', 'coresocial' ), __( 'Log each time the share dialog has been activated or the user has Liked the content.', 'coresocial' ), Type::BOOLEAN, $this->value( 'log_logging_active' ) ),
								EL::i( 'settings', 'log_visitors_hash', __( 'Visitors IP Hash', 'coresocial' ), __( 'For visitors (users that are not logged in), store hash value of their IP. This is used only for the Like operations to try and have Like stored for each user or visitor once per item. For share actions, this is not used.', 'coresocial' ), Type::BOOLEAN, $this->value( 'log_visitors_hash' ) ),
							),
						),
					),
				),
				'basic_counts' => array(
					'name'     => __( 'Showing Counts', 'coresocial' ),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'settings', 'show_counts', __( 'Method', 'coresocial' ), __( 'Some of the networks can return share or engagement counts for your URLs. You can choose how to show the counts, combining internal tracking and online counts.', 'coresocial' ), Type::SELECT, $this->value( 'show_counts' ) )->data( 'array', Helper::get_counts_methods() ),
								EL::i( 'settings', 'short_counts', __( 'Short Form', 'coresocial' ), __( 'Use short form number display (1000 becomes 1K, 1000000 becomes 1M) where possible to make sure that count doesn\'t take too much space in the button. Short numbers are always rounded!', 'coresocial' ), Type::BOOLEAN, $this->value( 'short_counts' ) ),
							),
						),
					),
				),
				'basic_url'    => array(
					'name'     => __( 'Share URLs', 'coresocial' ),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'settings', 'skip_url_args', __( 'Ignore URL arguments', 'coresocial' ), __( 'One or more arguments to ignore in the URL when using URL for social sharing.', 'coresocial' ), Type::EXPANDABLE_TEXT, $this->value( 'skip_url_args' ) ),
								EL::i( 'settings', 'skip_archive_paged_pages', __( 'Ignore archives Page number', 'coresocial' ), __( 'For archive pages that support paging, ignore page number in the URL.', 'coresocial' ), Type::BOOLEAN, $this->value( 'skip_archive_paged_pages' ) ),
								EL::i( 'settings', 'skip_search_paged_pages', __( 'Ignore search Page number', 'coresocial' ), __( 'For search results pages that support paging, ignore page number in the URL.', 'coresocial' ), Type::BOOLEAN, $this->value( 'skip_archive_paged_pages' ) ),
							),
						),
					),
				),
				'basic_online' => array(
					'name'     => __( 'Online Counts Checks', 'coresocial' ),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'settings', 'online_counts_active', __( 'Check online counts', 'coresocial' ), __( 'Some networks have API methods to get number of shares for each URL on the network. Plugin can get those data to use for the display of number of shares.', 'coresocial' ), Type::BOOLEAN, $this->value( 'online_counts_active' ) )->more( array(
									__( 'Online checks are done in the background, and values retrieved are stored in the database.', 'coresocial' ),
									__( 'There is no way to do these checks in real time due to the API restrictions networks have in place.', 'coresocial' ),
									__( 'Depending on the number of pages you have, this process may take a long time, and it is done in background threads.', 'coresocial' ),
								) ),
								EL::i( 'settings', 'online_counts_posts_only', __( 'Checkup posts only', 'coresocial' ), __( 'Check online counts only for URLs that belong to posts, pages and custom post types.', 'coresocial' ), Type::BOOLEAN, $this->value( 'online_counts_posts_only' ) ),
								EL::i( 'settings', 'online_counts_period', __( 'Checkup frequency', 'coresocial' ), __( 'Check every URL once a week or once a day at the most.', 'coresocial' ), Type::SELECT, $this->value( 'online_counts_period' ) )->data( 'array', $this->get_online_check_period() ),
							),
						),
					),
				),
			),
			'profiles'     => array(
				'data_title' => array(
					'name'     => __( 'Social Networks Profiles', 'coresocial' ),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'profiles', 'list', __( 'List of Profiles', 'coresocial' ), __( 'Add profiles you want to use. You can add multiple profiles for same networks. If you don\'t provide the name of the profile, plugin will set it to the network name, but, make sure to make them unique so you can identify them easier later.', 'coresocial' ) . ' ' . __( 'For some profiles, instead of URL provide: for Phone - phone number, for Email: email address and for Skype: Skype account.', 'coresocial' ), 'social_profiles', $this->value( 'list', 'profiles' ) ),
							),
						),
					),
				),
			),
			'data'         => array(
				'data_title' => array(
					'name'     => __( 'Post Title', 'coresocial' ),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'settings', 'title_source', __( 'Source', 'coresocial' ), __( 'Choose how the plugin will get current page title. If the simple method is selected, it will have WordPress function as a fallback.', 'coresocial' ), Type::SELECT, $this->value( 'title_source' ) )->data( 'array', $this->get_title_source() ),
								EL::i( 'settings', 'title_with_site_name', __( 'Append website name', 'coresocial' ), __( 'This is recommended for use with Simple method for previous option. And, it might lead to having site name included twice.', 'coresocial' ), Type::BOOLEAN, $this->value( 'title_with_site_name' ) ),
							),
						),
					),
				),
			),
			'files'        => array(
				'files_all' => array(
					'name'     => __( 'When to load files', 'coresocial' ),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'settings', 'load_files_on_demand', __( 'When needed', 'coresocial' ), __( 'Load JS and CSS files only when they are needed.', 'coresocial' ), Type::BOOLEAN, $this->value( 'load_files_on_demand' ) ),
							),
						),
					),
				),
				'files_css' => array(
					'name'     => __( 'CSS files', 'coresocial' ),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'settings', 'css_font_embedded', __( 'Embedded Font', 'coresocial' ), __( 'The plugin will load font CSS file with WOFF and WOFF2 fonts embedded.', 'coresocial' ), Type::BOOLEAN, $this->value( 'css_font_embedded' ) ),
								EL::i( 'settings', 'css_font_packed', __( 'All in one Packed Styling', 'coresocial' ), __( 'The plugin will load packed CSS file containing main styling and font with icons.', 'coresocial' ), Type::BOOLEAN, $this->value( 'css_font_packed' ) ),
							),
						),
					),
				),
			),
			'display'      => array(
				'display_popup' => array(
					'name'     => __( 'Sharing popup', 'coresocial' ),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'settings', 'popup_width', __( 'Width', 'coresocial' ), '', Type::ABSINT, $this->value( 'popup_width' ) )->args( array( 'label_unit' => '(px)' ) ),
								EL::i( 'settings', 'popup_height', __( 'Height', 'coresocial' ), '', Type::ABSINT, $this->value( 'popup_height' ) )->args( array( 'label_unit' => '(px)' ) ),
							),
						),
					),
				),
			),
			'advanced'     => array(
				'advanced-wizard' => array(
					'name'     => __( 'Setup Wizard', 'coresocial' ),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'settings', 'show_setup_wizard', __( 'Show Setup Wizard', 'coresocial' ), __( 'If enabled, the Setup Wizard item will be included in the plugin admin side navigation.', 'coresocial' ), Type::BOOLEAN, $this->value( 'show_setup_wizard' ) ),
							),
						),
					),
				),
			),
			'internal'     => array(
				'internal_like'    => array(
					'name'     => __( 'Like', 'coresocial' ),
					'sections' => array(
						array(
							'label'    => __( 'Available in coreSocial Pro', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								El::info( __( 'Information', 'coresocial' ), '<strong>' . __( 'Like button is available in coreSocial Pro version only!', 'coresocial' ) . '</strong><br/>' . __( 'Share blocks can include Like button, and each user can Like the post or page only once.', 'coresocial' ) )->buttons( array(
									array(
										'type'   => 'a',
										'link'   => 'https://www.dev4press.com/plugins/coresocial/',
										'class'  => 'button-primary',
										'title'  => __( 'Upgrade to coreSocial Pro', 'coresocial' ),
										'target' => '_blank',
									),
								) ),
							),
						),
					),
				),
				'internal_qrcode'  => array(
					'name'     => __( 'QR Code', 'coresocial' ),
					'sections' => array(
						array(
							'label'    => __( 'Available in coreSocial Pro', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								El::info( __( 'Information', 'coresocial' ), '<strong>' . __( 'QR Code button is available in coreSocial Pro version only!', 'coresocial' ) . '</strong><br/>' . __( 'Share blocks can include QR Code button, and when clicked, this button will display overlay with the QR Code that leads to the page, allowing for quick sharing of the page to a mobile device.', 'coresocial' ) )->buttons( array(
									array(
										'type'   => 'a',
										'link'   => 'https://www.dev4press.com/plugins/coresocial/',
										'class'  => 'button-primary',
										'title'  => __( 'Upgrade to coreSocial Pro', 'coresocial' ),
										'target' => '_blank',
									),
								) ),
							),
						),
					),
				),
				'internal_copyurl' => array(
					'name'     => __( 'Copy URL', 'coresocial' ),
					'sections' => array(
						array(
							'label'    => __( 'Available in coreSocial Pro', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								El::info( __( 'Information', 'coresocial' ), '<strong>' . __( 'Copy URL button is available in coreSocial Pro version only!', 'coresocial' ) . '</strong><br/>' . __( 'Share blocks can include Copy URL button, and when clicked, page URL will be copied into clipboard.', 'coresocial' ) )->buttons( array(
									array(
										'type'   => 'a',
										'link'   => 'https://www.dev4press.com/plugins/coresocial/',
										'class'  => 'button-primary',
										'title'  => __( 'Upgrade to coreSocial Pro', 'coresocial' ),
										'target' => '_blank',
									),
								) ),
							),
						),
					),
				),
				'internal_mailto'  => array(
					'name'     => __( 'Email', 'coresocial' ),
					'toggle'   => array(
						'option' => 'mailto_active',
						'group'  => 'networks',
						'value'  => $this->value( 'mailto_active', 'networks' ),
						'label'  => __( 'This is main switch to enable the support for Email.', 'coresocial' ),
					),
					'off'      => array(
						'notice' => __( 'The Email support is currently deactivated. Enable it, to see all the available settings.', 'coresocial' ),
					),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'mailto_active', __( 'Active', 'coresocial' ), __( 'This is main switch to enable the MailTo/Email.', 'coresocial' ), Type::BOOLEAN, $this->value( 'mailto_active', 'networks' ) )->args( array( 'skip_render' => true ) ),
								EL::i( 'networks', 'mailto_name', __( 'Name', 'coresocial' ), '', Type::TEXT, $this->value( 'mailto_name', 'networks' ) ),
							),
						),
						array(
							'label'    => __( 'Colors', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'mailto_color_primary', __( 'Primary', 'coresocial' ), '', Type::COLOR, $this->value( 'mailto_color_primary', 'networks' ) ),
								EL::i( 'networks', 'mailto_color_text', __( 'Text', 'coresocial' ), '', Type::COLOR, $this->value( 'mailto_color_text', 'networks' ) ),
								EL::i( 'networks', 'mailto_color_icon', __( 'Icon', 'coresocial' ), '', Type::COLOR, $this->value( 'mailto_color_icon', 'networks' ) ),
							),
						),
						array(
							'label'    => __( 'Label', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'mailto_label', __( 'Label', 'coresocial' ), '', Type::TEXT, $this->value( 'mailto_label', 'networks' ) ),
							),
						),
					),
				),
				'internal_printer' => array(
					'name'     => __( 'Print', 'coresocial' ),
					'toggle'   => array(
						'option' => 'printer_active',
						'group'  => 'networks',
						'value'  => $this->value( 'printer_active', 'networks' ),
						'label'  => __( 'This is main switch to enable the support for Print.', 'coresocial' ),
					),
					'off'      => array(
						'notice' => __( 'The Print support is currently deactivated. Enable it, to see all the available settings.', 'coresocial' ),
					),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'printer_active', __( 'Active', 'coresocial' ), __( 'This is main switch to enable the Print method.', 'coresocial' ), Type::BOOLEAN, $this->value( 'printer_active', 'networks' ) )->args( array( 'skip_render' => true ) ),
								EL::i( 'networks', 'printer_name', __( 'Name', 'coresocial' ), '', Type::TEXT, $this->value( 'printer_name', 'networks' ) ),
							),
						),
						array(
							'label'    => __( 'Colors', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'printer_color_primary', __( 'Primary', 'coresocial' ), '', Type::COLOR, $this->value( 'printer_color_primary', 'networks' ) ),
								EL::i( 'networks', 'printer_color_text', __( 'Text', 'coresocial' ), '', Type::COLOR, $this->value( 'printer_color_text', 'networks' ) ),
								EL::i( 'networks', 'printer_color_icon', __( 'Icon', 'coresocial' ), '', Type::COLOR, $this->value( 'printer_color_icon', 'networks' ) ),
							),
						),
						array(
							'label'    => __( 'Label', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'printer_label', __( 'Label', 'coresocial' ), '', Type::TEXT, $this->value( 'printer_label', 'networks' ) ),
							),
						),
					),
				),
			),
			'networks'     => array(
				'networks_twitter'   => array(
					'name'     => __( 'Twitter', 'coresocial' ),
					'toggle'   => array(
						'option' => 'twitter_active',
						'group'  => 'networks',
						'value'  => $this->value( 'twitter_active', 'networks' ),
						'label'  => __( 'This is main switch to enable the support for Twitter.', 'coresocial' ),
					),
					'off'      => array(
						'notice' => __( 'The Twitter support is currently deactivated. Enable it, to see all the available settings.', 'coresocial' ),
					),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'twitter_active', __( 'Active', 'coresocial' ), __( 'This is main switch to enable the support for Twitter.', 'coresocial' ), Type::BOOLEAN, $this->value( 'twitter_active', 'networks' ) )->args( array( 'skip_render' => true ) ),
								EL::i( 'networks', 'twitter_name', __( 'Name', 'coresocial' ), '', Type::TEXT, $this->value( 'twitter_name', 'networks' ) ),
								EL::i( 'networks', 'twitter_url', __( 'Share URL', 'coresocial' ), '', Type::SELECT, $this->value( 'twitter_name', 'networks' ) )->data( 'array', $this->get_twitter_url() ),
							),
						),
						array(
							'label'    => 'X',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'twitter_x', __( 'Show X Icon', 'coresocial' ), __( 'Instead of classic Twitter icon, show the X icon.', 'coresocial' ), Type::BOOLEAN, $this->value( 'twitter_x', 'networks' ) ),
							),
						),
						array(
							'label'    => __( 'Online Counts', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::info( __( 'Not Available', 'coresocial' ), __( 'There are no viable ways to get share or engagements counts from Twitter. Their API no longer supports that, and there are no other replacement methods currently working.', 'coresocial' ) ),
							),
						),
						array(
							'label'    => __( 'Colors', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'twitter_color_primary', __( 'Primary', 'coresocial' ), '', Type::COLOR, $this->value( 'twitter_color_primary', 'networks' ) ),
								EL::i( 'networks', 'twitter_color_text', __( 'Text', 'coresocial' ), '', Type::COLOR, $this->value( 'twitter_color_text', 'networks' ) ),
								EL::i( 'networks', 'twitter_color_icon', __( 'Icon', 'coresocial' ), '', Type::COLOR, $this->value( 'twitter_color_icon', 'networks' ) ),
							),
						),
						array(
							'label'    => __( 'Label', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'twitter_label', __( 'Label', 'coresocial' ), '', Type::TEXT, $this->value( 'twitter_label', 'networks' ) ),
							),
						),
						array(
							'label'    => __( 'Advanced', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'twitter_account', __( 'Account', 'coresocial' ), '', Type::TEXT, $this->value( 'twitter_account', 'networks' ) ),
								EL::i( 'networks', 'twitter_hashtags', __( 'Hashtags', 'coresocial' ), '', Type::EXPANDABLE_TEXT, $this->value( 'twitter_hashtags', 'networks' ) ),
							),
						),
					),
				),
				'networks_facebook'  => array(
					'name'     => __( 'Facebook', 'coresocial' ),
					'toggle'   => array(
						'option' => 'facebook_active',
						'group'  => 'networks',
						'value'  => $this->value( 'facebook_active', 'networks' ),
						'label'  => __( 'This is main switch to enable the support for Facebook.', 'coresocial' ),
					),
					'off'      => array(
						'notice' => __( 'The Facebook support is currently deactivated. Enable it, to see all the available settings.', 'coresocial' ),
					),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'facebook_active', __( 'Active', 'coresocial' ), __( 'This is main switch to enable the support for Facebook.', 'coresocial' ), Type::BOOLEAN, $this->value( 'facebook_active', 'networks' ) )->args( array( 'skip_render' => true ) ),
								EL::i( 'networks', 'facebook_name', __( 'Name', 'coresocial' ), '', Type::TEXT, $this->value( 'facebook_name', 'networks' ) ),
							),
						),
						array(
							'label'    => __( 'Online Counts', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'facebook_online', __( 'Available', 'coresocial' ), __( 'The plugin will attempt to get URL share engagements from Facebook. The App token is required for this operation.', 'coresocial' ), Type::BOOLEAN, $this->value( 'facebook_online', 'networks' ) ),
								EL::i( 'networks', 'facebook_app_token', __( 'App Token', 'coresocial' ), __( 'If you want to be able to get real engagement counts for your URLs from Facebook API, you will need to setup the Facebook App, and get App token for it. If the valid app token is added here, plugin will attempt to get real engagements counts for each shared URL. There is no guarantee that this will return results for every URL. All the results will be cached for the period of 24 hours to avoid Facebook API limits.', 'coresocial' ), Type::TEXT, $this->value( 'facebook_app_token', 'networks' ) ),
							),
						),
						array(
							'label'    => __( 'Colors', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'facebook_color_primary', __( 'Primary', 'coresocial' ), '', Type::COLOR, $this->value( 'facebook_color_primary', 'networks' ) ),
								EL::i( 'networks', 'facebook_color_text', __( 'Text', 'coresocial' ), '', Type::COLOR, $this->value( 'facebook_color_text', 'networks' ) ),
								EL::i( 'networks', 'facebook_color_icon', __( 'Icon', 'coresocial' ), '', Type::COLOR, $this->value( 'facebook_color_icon', 'networks' ) ),
							),
						),
						array(
							'label'    => __( 'Label', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'facebook_label', __( 'Label', 'coresocial' ), '', Type::TEXT, $this->value( 'facebook_label', 'networks' ) ),
							),
						),
						array(
							'label'    => __( 'Share Link Format', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'facebook_method', __( 'Method', 'coresocial' ), __( 'Legacy method still works, but the Facebook may disable it at any time. Application based method requires valid live application APP ID and Redirect URL.  Without these values, plugin will go back to the legacy method.', 'coresocial' ), Type::SELECT, $this->value( 'facebook_method', 'networks' ) )->data( 'array', $this->get_facebook_method() ),
								EL::i( 'networks', 'facebook_app_id', __( 'APP ID', 'coresocial' ), __( 'To get APP ID, you need to create Facebook application and configure it in live mode. To start with the application creation go to Facebook Developers website.', 'coresocial' ) . ' <a target="_blank" rel="nofollow noopener" href="https://developers.facebook.com/apps/">Facebook Developers</a>', Type::TEXT, $this->value( 'facebook_app_id', 'networks' ) ),
								EL::i( 'networks', 'facebook_redirect', __( 'Redirect URL', 'coresocial' ), __( 'Redirect URL domain has to be in the list of approved domains for your application.', 'coresocial' ), Type::TEXT, $this->value( 'facebook_redirect', 'networks' ) ),
							),
						),
						array(
							'label'    => __( 'Advanced', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'facebook_hashtag', __( 'Hashtag', 'coresocial' ), __( 'Only available with the Application Share Dialog method! It must include the hash symbol (#).', 'coresocial' ), Type::TEXT, $this->value( 'facebook_hashtag', 'networks' ) ),
							),
						),
					),
				),
				'networks_reddit'    => array(
					'name'     => __( 'Reddit', 'coresocial' ),
					'toggle'   => array(
						'option' => 'reddit_active',
						'group'  => 'networks',
						'value'  => $this->value( 'reddit_active', 'networks' ),
						'label'  => __( 'This is main switch to enable the support for Reddit.', 'coresocial' ),
					),
					'off'      => array(
						'notice' => __( 'The Reddit support is currently deactivated. Enable it, to see all the available settings.', 'coresocial' ),
					),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'reddit_active', __( 'Active', 'coresocial' ), __( 'This is main switch to enable the support for Reddit.', 'coresocial' ), Type::BOOLEAN, $this->value( 'reddit_active', 'networks' ) )->args( array( 'skip_render' => true ) ),
								EL::i( 'networks', 'reddit_name', __( 'Name', 'coresocial' ), '', Type::TEXT, $this->value( 'reddit_name', 'networks' ) ),
							),
						),
						array(
							'label'    => __( 'Online Counts', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::info( __( 'Not Available', 'coresocial' ), __( 'There are no viable ways to get share or engagements counts from Reddit. Their API no longer supports that, and there are no other replacement methods currently working.', 'coresocial' ) ),
							),
						),
						array(
							'label'    => __( 'Colors', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'reddit_color_primary', __( 'Primary', 'coresocial' ), '', Type::COLOR, $this->value( 'reddit_color_primary', 'networks' ) ),
								EL::i( 'networks', 'reddit_color_text', __( 'Text', 'coresocial' ), '', Type::COLOR, $this->value( 'reddit_color_text', 'networks' ) ),
								EL::i( 'networks', 'reddit_color_icon', __( 'Icon', 'coresocial' ), '', Type::COLOR, $this->value( 'reddit_color_icon', 'networks' ) ),
							),
						),
						array(
							'label'    => __( 'Label', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'reddit_label', __( 'Label', 'coresocial' ), '', Type::TEXT, $this->value( 'reddit_label', 'networks' ) ),
							),
						),
					),
				),
				'networks_tumblr'    => array(
					'name'     => __( 'Tumblr', 'coresocial' ),
					'toggle'   => array(
						'option' => 'tumblr_active',
						'group'  => 'networks',
						'value'  => $this->value( 'tumblr_active', 'networks' ),
						'label'  => __( 'This is main switch to enable the support for Tumblr.', 'coresocial' ),
					),
					'off'      => array(
						'notice' => __( 'The Tumblr support is currently deactivated. Enable it, to see all the available settings.', 'coresocial' ),
					),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'tumblr_active', __( 'Active', 'coresocial' ), __( 'This is main switch to enable the support for Tumblr.', 'coresocial' ), Type::BOOLEAN, $this->value( 'tumblr_active', 'networks' ) )->args( array( 'skip_render' => true ) ),
								EL::i( 'networks', 'tumblr_name', __( 'Name', 'coresocial' ), '', Type::TEXT, $this->value( 'tumblr_name', 'networks' ) ),
							),
						),
						array(
							'label'    => __( 'Online Counts', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'tumblr_online', __( 'Available', 'coresocial' ), __( 'The plugin will attempt to get share counts from Tumblr. Data will be updated at most only once every 24 hours to avoid hitting Tumblr API limits. No API key is required for this operation at this time.', 'coresocial' ), Type::BOOLEAN, $this->value( 'tumblr_online', 'networks' ) ),
							),
						),
						array(
							'label'    => __( 'Colors', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'tumblr_color_primary', __( 'Primary', 'coresocial' ), '', Type::COLOR, $this->value( 'tumblr_color_primary', 'networks' ) ),
								EL::i( 'networks', 'tumblr_color_text', __( 'Text', 'coresocial' ), '', Type::COLOR, $this->value( 'tumblr_color_text', 'networks' ) ),
								EL::i( 'networks', 'tumblr_color_icon', __( 'Icon', 'coresocial' ), '', Type::COLOR, $this->value( 'tumblr_color_icon', 'networks' ) ),
							),
						),
						array(
							'label'    => __( 'Label', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'tumblr_label', __( 'Label', 'coresocial' ), '', Type::TEXT, $this->value( 'tumblr_label', 'networks' ) ),
							),
						),
					),
				),
				'networks_linkedin'  => array(
					'name'     => __( 'LinkedIn', 'coresocial' ),
					'toggle'   => array(
						'option' => 'linkedin_active',
						'group'  => 'networks',
						'value'  => $this->value( 'linkedin_active', 'networks' ),
						'label'  => __( 'This is main switch to enable the support for LinkedIn.', 'coresocial' ),
					),
					'off'      => array(
						'notice' => __( 'The LinkedIn support is currently deactivated. Enable it, to see all the available settings.', 'coresocial' ),
					),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'linkedin_active', __( 'Active', 'coresocial' ), __( 'This is main switch to enable the support for LinkedIn.', 'coresocial' ), Type::BOOLEAN, $this->value( 'linkedin_active', 'networks' ) )->args( array( 'skip_render' => true ) ),
								EL::i( 'networks', 'linkedin_name', __( 'Name', 'coresocial' ), '', Type::TEXT, $this->value( 'linkedin_name', 'networks' ) ),
							),
						),
						array(
							'label'    => __( 'Online Counts', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::info( __( 'Not Available', 'coresocial' ), __( 'There are no viable ways to get share or engagements counts from Linkedin. Their API no longer supports that, and there are no other replacement methods currently working.', 'coresocial' ) ),
							),
						),
						array(
							'label'    => __( 'Colors', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'linkedin_color_primary', __( 'Primary', 'coresocial' ), '', Type::COLOR, $this->value( 'linkedin_color_primary', 'networks' ) ),
								EL::i( 'networks', 'linkedin_color_text', __( 'Text', 'coresocial' ), '', Type::COLOR, $this->value( 'linkedin_color_text', 'networks' ) ),
								EL::i( 'networks', 'linkedin_color_icon', __( 'Icon', 'coresocial' ), '', Type::COLOR, $this->value( 'linkedin_color_icon', 'networks' ) ),
							),
						),
						array(
							'label'    => __( 'Label', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'linkedin_label', __( 'Label', 'coresocial' ), '', Type::TEXT, $this->value( 'linkedin_label', 'networks' ) ),
							),
						),
					),
				),
				'networks_mix'       => array(
					'name'     => __( 'Mix', 'coresocial' ),
					'toggle'   => array(
						'option' => 'mix_active',
						'group'  => 'networks',
						'value'  => $this->value( 'mix_active', 'networks' ),
						'label'  => __( 'This is main switch to enable the support for Mix.', 'coresocial' ),
					),
					'off'      => array(
						'notice' => __( 'The Mix support is currently deactivated. Enable it, to see all the available settings.', 'coresocial' ) . ' ' . __( 'At this time, Mix sharing no longer works, and Mix.com has no valid methods to submit posts or content.', 'coresocial' ),
					),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'mix_active', __( 'Active', 'coresocial' ), __( 'This is main switch to enable the support for Mix.', 'coresocial' ), Type::BOOLEAN, $this->value( 'mix_active', 'networks' ) )->args( array( 'skip_render' => true ) ),
								EL::i( 'networks', 'mix_name', __( 'Name', 'coresocial' ), '', Type::TEXT, $this->value( 'mix_name', 'networks' ) ),
							),
						),
						array(
							'label'    => __( 'Status', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::info( __( 'Not Working', 'coresocial' ), __( 'At this time, Mix sharing no longer works, and Mix.com has no valid methods to submit posts or content.', 'coresocial' ) . __( 'Support for Mix sharing maybe removed in the future if this is permanent change on Mix.com.', 'coresocial' ) ),
							),
						),
						array(
							'label'    => __( 'Online Counts', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::info( __( 'Not Available', 'coresocial' ), __( 'There are no viable ways to get share or engagements counts from Mix.', 'coresocial' ) ),
							),
						),
						array(
							'label'    => __( 'Colors', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'mix_color_primary', __( 'Primary', 'coresocial' ), '', Type::COLOR, $this->value( 'mix_color_primary', 'networks' ) ),
								EL::i( 'networks', 'mix_color_text', __( 'Text', 'coresocial' ), '', Type::COLOR, $this->value( 'mix_color_text', 'networks' ) ),
								EL::i( 'networks', 'mix_color_icon', __( 'Icon', 'coresocial' ), '', Type::COLOR, $this->value( 'mix_color_icon', 'networks' ) ),
							),
						),
						array(
							'label'    => __( 'Label', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'mix_label', __( 'Label', 'coresocial' ), '', Type::TEXT, $this->value( 'mix_label', 'networks' ) ),
							),
						),
					),
				),
				'networks_pinterest' => array(
					'name'     => __( 'Pinterest', 'coresocial' ),
					'toggle'   => array(
						'option' => 'pinterest_active',
						'group'  => 'networks',
						'value'  => $this->value( 'pinterest_active', 'networks' ),
						'label'  => __( 'This is main switch to enable the support for Pinterest.', 'coresocial' ),
					),
					'off'      => array(
						'notice' => __( 'The Pinterest support is currently deactivated. Enable it, to see all the available settings.', 'coresocial' ),
					),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'pinterest_active', __( 'Active', 'coresocial' ), __( 'This is main switch to enable the support for Pinterest.', 'coresocial' ), Type::BOOLEAN, $this->value( 'pinterest_active', 'networks' ) )->args( array( 'skip_render' => true ) ),
								EL::i( 'networks', 'pinterest_name', __( 'Name', 'coresocial' ), '', Type::TEXT, $this->value( 'pinterest_name', 'networks' ) ),
							),
						),
						array(
							'label'    => __( 'Online Counts', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'pinterest_online', __( 'Available', 'coresocial' ), __( 'The plugin will attempt to get share counts from Pinterest. Data will be updated at most only once every 24 hours to avoid hitting Yummly API limits. No API key is required for this operation at this time.', 'coresocial' ), Type::BOOLEAN, $this->value( 'pinterest_online', 'networks' ) ),
							),
						),
						array(
							'label'    => __( 'Colors', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'pinterest_color_primary', __( 'Primary', 'coresocial' ), '', Type::COLOR, $this->value( 'pinterest_color_primary', 'networks' ) ),
								EL::i( 'networks', 'pinterest_color_text', __( 'Text', 'coresocial' ), '', Type::COLOR, $this->value( 'pinterest_color_text', 'networks' ) ),
								EL::i( 'networks', 'pinterest_color_icon', __( 'Icon', 'coresocial' ), '', Type::COLOR, $this->value( 'pinterest_color_icon', 'networks' ) ),
							),
						),
						array(
							'label'    => __( 'Label', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'pinterest_label', __( 'Label', 'coresocial' ), '', Type::TEXT, $this->value( 'pinterest_label', 'networks' ) ),
							),
						),
					),
				),
				'networks_yummly'    => array(
					'name'     => __( 'Yummly', 'coresocial' ),
					'toggle'   => array(
						'option' => 'yummly_active',
						'group'  => 'networks',
						'value'  => $this->value( 'yummly_active', 'networks' ),
						'label'  => __( 'This is main switch to enable the support for Yummly.', 'coresocial' ),
					),
					'off'      => array(
						'notice' => __( 'The Yummly support is currently deactivated. Enable it, to see all the available settings.', 'coresocial' ),
					),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'yummly_active', __( 'Active', 'coresocial' ), __( 'This is main switch to enable the support for Yummly.', 'coresocial' ), Type::BOOLEAN, $this->value( 'yummly_active', 'networks' ) )->args( array( 'skip_render' => true ) ),
								EL::i( 'networks', 'yummly_name', __( 'Name', 'coresocial' ), '', Type::TEXT, $this->value( 'yummly_name', 'networks' ) ),
							),
						),
						array(
							'label'    => __( 'Online Counts', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'yummly_online', __( 'Available', 'coresocial' ), __( 'The plugin will attempt to get share counts from Yummly. Data will be updated at most only once every 24 hours to avoid hitting Yummly API limits. No API key is required for this operation at this time.', 'coresocial' ), Type::BOOLEAN, $this->value( 'yummly_online', 'networks' ) ),
							),
						),
						array(
							'label'    => __( 'Colors', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'yummly_color_primary', __( 'Primary', 'coresocial' ), '', Type::COLOR, $this->value( 'yummly_color_primary', 'networks' ) ),
								EL::i( 'networks', 'yummly_color_text', __( 'Text', 'coresocial' ), '', Type::COLOR, $this->value( 'yummly_color_text', 'networks' ) ),
								EL::i( 'networks', 'yummly_color_icon', __( 'Icon', 'coresocial' ), '', Type::COLOR, $this->value( 'yummly_color_icon', 'networks' ) ),
							),
						),
						array(
							'label'    => __( 'Label', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'networks', 'yummly_label', __( 'Label', 'coresocial' ), '', Type::TEXT, $this->value( 'yummly_label', 'networks' ) ),
							),
						),
					),
				),
			),
			'inline-embed' => array(
				'inline-embed-auto' => array(
					'name'     => __( 'Auto Embed', 'coresocial' ),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'inline', 'active', __( 'Active', 'coresocial' ), '', Type::BOOLEAN, $this->value( 'active', 'inline' ) ),
							),
						),
						array(
							'label'    => __( 'Embed', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'inline', 'location', __( 'Location', 'coresocial' ), '', Type::SELECT, $this->value( 'location', 'inline' ) )->data( 'array', $this->get_embed_locations() ),
								EL::i( 'inline', 'post_types', __( 'Post Types', 'coresocial' ), '', Type::CHECKBOXES, $this->value( 'post_types', 'inline' ) )->data( 'array', $this->get_embed_post_types() ),
							),
						),
						array(
							'label'    => __( 'Networks', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'inline', 'networks', __( 'List of Networks', 'coresocial' ), __( 'Only share networks enabled from the Networks and Internal panels will be listed here! To see additional networks, visit the settings panels to enable or disable them.', 'coresocial' ), Type::CHECKBOXES, $this->value( 'networks', 'inline' ) )->data( 'array', $this->get_embed_networks() )->buttons( array(
									array(
										'type'  => 'a',
										'link'  => coresocial_admin()->panel_url( 'settings', 'networks' ),
										'class' => 'button-secondary',
										'title' => __( 'List of Online Networks', 'coresocial' ),
									),
									array(
										'type'  => 'a',
										'link'  => coresocial_admin()->panel_url( 'settings', 'internal' ),
										'class' => 'button-secondary',
										'title' => __( 'List of Internal Shares', 'coresocial' ),
									),
								) ),
							),
						),
					),
				),
			),
			'inline'       => array(
				'inline_display' => array(
					'name'     => __( 'Display', 'coresocial' ),
					'sections' => array(
						array(
							'label'    => __( 'Layout', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'inline', 'align', __( 'Alignment', 'coresocial' ), '', Type::SELECT, $this->value( 'align', 'inline' ) )->data( 'array', $this->get_button_align() ),
								EL::i( 'inline', 'layout', __( 'Buttons Layout', 'coresocial' ), '', Type::SELECT, $this->value( 'layout', 'inline' ) )->data( 'array', $this->get_button_layout() ),
								EL::i( 'inline', 'color', __( 'Buttons Color', 'coresocial' ), '', Type::SELECT, $this->value( 'color', 'inline' ) )->data( 'array', $this->get_button_color() ),
							),
						),
						array(
							'label'    => __( 'Counts', 'coresocial' ),
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'inline', 'share_count_active', __( 'Active', 'coresocial' ), '', Type::BOOLEAN, $this->value( 'share_count_active', 'inline' ) ),
								EL::i( 'inline', 'share_count_hide_if_zero', __( 'Hide if Zero', 'coresocial' ), '', Type::BOOLEAN, $this->value( 'share_count_hide_if_zero', 'inline' ) ),
							),
						),
					),
				),
				'inline_style'   => array(
					'name'     => __( 'Styling', 'coresocial' ),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'inline', 'font_size', __( 'Font Size', 'coresocial' ), '', Type::CSS_SIZE, $this->value( 'font_size', 'inline' ) )->args( array( 'allowed' => array( 'px' ) ) ),
								EL::i( 'inline', 'button_size', __( 'Button Size', 'coresocial' ), '', Type::CSS_SIZE, $this->value( 'button_size', 'inline' ) )->args( array( 'allowed' => array( 'px' ) ) ),
								EL::i( 'inline', 'button_gap', __( 'Gap between buttons', 'coresocial' ), '', Type::CSS_SIZE, $this->value( 'button_gap', 'inline' ) ),
								EL::i( 'inline', 'styling', __( 'Styling', 'coresocial' ), '', Type::SELECT, $this->value( 'styling', 'inline' ) )->data( 'array', $this->get_button_styling() ),
								EL::i( 'inline', 'rounded', __( 'Rounded Size', 'coresocial' ), '', Type::CSS_SIZE, $this->value( 'rounded', 'inline' ) ),
							),
						),
					),
				),
			),
		);
	}

	public function get_twitter_url() : array {
		return array(
			'x-share'        => __( 'Share - X.com', 'coresocial' ),
			'x-intent'       => __( 'Intent - X.com', 'coresocial' ),
			'twitter-share'  => __( 'Share - Twitter.com', 'coresocial' ),
			'twitter-intent' => __( 'Intent - twitter.com', 'coresocial' ),
		);
	}

	public function get_embed_locations() : array {
		return array(
			'top'    => __( 'Top, before the post content', 'coresocial' ),
			'bottom' => __( 'Bottom, after the post content', 'coresocial' ),
			'both'   => __( 'Both', 'coresocial' ),
			'hide'   => __( 'Do not add', 'coresocial' ),
		);
	}

	public function get_embed_post_types() : array {
		return Helper::get_post_types();
	}

	public function get_embed_networks() : array {
		return coresocial_loader()->get_networks_list();
	}

	public function get_button_align() : array {
		return array(
			'none'    => __( 'None', 'coresocial' ),
			'left'    => __( 'Left', 'coresocial' ),
			'center'  => __( 'Center', 'coresocial' ),
			'right'   => __( 'Right', 'coresocial' ),
			'justify' => __( 'Justify', 'coresocial' ),
		);
	}

	public function get_button_layout() : array {
		return array(
			'icon_fill'  => __( 'Icon Only', 'coresocial' ),
			'left_fill'  => __( 'Icon on the Left', 'coresocial' ),
			'right_fill' => __( 'Icon on the Right', 'coresocial' ),
		);
	}

	public function get_button_color() : array {
		return array(
			'fill'  => __( 'Filled Background', 'coresocial' ),
			'plain' => __( 'Plain Background', 'coresocial' ),
		);
	}

	public function get_button_styling() : array {
		return array(
			'normal'  => __( 'Normal', 'coresocial' ),
			'rounded' => __( 'Rounded', 'coresocial' ),
			'round'   => __( 'Round', 'coresocial' ),
		);
	}

	public function get_title_source() : array {
		return array(
			'default' => __( 'WordPress page title function', 'coresocial' ),
			'simple'  => __( 'Simple Post/Term title only', 'coresocial' ),
		);
	}

	public function get_facebook_method() : array {
		return array(
			'legacy' => __( 'Legacy Share Popup', 'coresocial' ),
			'app'    => __( 'Application Share Dialog', 'coresocial' ),
		);
	}

	public function get_online_check_period() : array {
		return array(
			'day'  => __( 'Daily', 'coresocial' ),
			'week' => __( 'Weekly', 'coresocial' ),
		);
	}
}
