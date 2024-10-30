<?php

namespace Dev4Press\Plugin\coreSocial\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Help {
	protected $admin;

	public function __construct( $admin ) {
		$this->admin = $admin;

		if ( $this->panel() == 'items' ) {
			$this->_for_items();
		}

		if ( $this->panel() == 'settings' ) {
			switch ( $this->subpanel() ) {
				case 'display':
					$this->_for_settings_display();
					break;
				case 'networks':
					$this->_for_settings_networks();
					break;
				case 'internal':
					$this->_for_settings_internal();
					break;
				case 'profiles':
					$this->_for_settings_profiles();
					break;
				case 'inline':
				case 'inline-embed':
					$this->_for_settings_inline();
					break;
			}
		}
	}

	protected function a() : Plugin {
		return $this->admin;
	}

	protected function panel() : string {
		return $this->a()->panel;
	}

	protected function subpanel() : string {
		return $this->a()->subpanel;
	}

	protected function tab( $code, $title, $content ) {
		$this->a()->screen()->add_help_tab( array(
				'id'      => $this->a()->plugin . '-' . $this->a()->panel . '-' . $code,
				'title'   => $title,
				'content' => '<h2>' . $title . '</h2>' . $content,
			)
		);
	}

	private function _for_items() {
		$this->tab( 'display', __( 'Shared Items', 'coresocial' ),
			'<p>' . __( 'Here you can see all the website pages where the share block has appeared, with the counts of the shares logged, and the online share counts (if enabled and supported).', 'coresocial' ) . '</p>' .
			'<h4>' . __( 'Item Actions', 'coresocial' ) . '</h4>' .
			'<p>' . __( 'For each item, you can view the actual page (and for posts you also have edit link). But, more importantly, for each item there are 3 actions: delete item (will remove item, counts and log from the database, resetting all the data for it to zero), clear (just remove logged data, item remains in the database) and action to add item to queue for getting online share counts. All 3 actions are available as bulk actions as well.', 'coresocial' ) . '</p>'
		);
	}

	private function _for_settings_display() {
		$this->tab( 'display', __( 'Settings', 'coresocial' ),
			'<p>' . __( 'Sharing popup is displayed when user clicks on the share buttons. The size of the popup is important for desktop devices only.', 'coresocial' ) . '</p>'
		);
	}

	private function _for_settings_networks() {
		$this->tab( 'display', __( 'Networks', 'coresocial' ),
			'<p>' . __( 'All supported share networks are configured from this panel. For each network, you can change the network name and colors, and the label for the button. By default, text and icon use same color, and it is important to have good contrast between Primary and Text.', 'coresocial' ) .
			' ' . __( 'Networks disabled from this panel, will not appear in any other settings panel or blocks. Some networks support getting online counts from their respective APIs, and you need to enable which online count you want to get, and some may require additional configuration.', 'coresocial' ) . '</p>' .
			'<h4>' . __( 'For Twitter', 'coresocial' ) . '</h4>' .
			'<p>' . __( 'You can set your account name to be included at the end of each tweet, and you can add one or more hashtags. Both of these can be expanded for individual posts via post meta box.', 'coresocial' ) . '</p>' .
			'<h4>' . __( 'For Facebook', 'coresocial' ) . '</h4>' .
			'<p>' . __( 'Facebook has two share dialog formats, and it is highly recommended to use Application Share Dialog. But, for that, you need to provide Application ID.', 'coresocial' ) . '</p>'
		);
	}

	private function _for_settings_internal() {
		$this->tab( 'display', __( 'Internal', 'coresocial' ),
			'<p>' . __( 'Additional share methods have similar settings to the main share networks, again with options to change name, label and colors.', 'coresocial' ) . '</p>'
		);
	}

	private function _for_settings_profiles() {
		$this->tab( 'display', __( 'Social Profiles', 'coresocial' ),
			'<p>' . __( 'From this panel you can add one or more online social networks or website profiles and links. Each added network requires selection of the network (this will control the color and icon for the button), give name to the profile and URL to link the button to. You can add more then one profile for the same network.', 'coresocial' ) . '</p>'
		);
	}

	private function _for_settings_inline() {
		$this->tab( 'display', __( 'Inline Sharing', 'coresocial' ),
			'<p>' . __( 'Inline sharing block has default settings that are used as a basis for all the shared blocks. Styling settings can be changed for individual blocks, shortcodes or with individual function calls.', 'coresocial' ) . '</p>' .
			'<p>' . __( 'To automatically display share block, plugin depends on the theme using standard content filter to add shared block markup. That\'s why you need to select post types for auto embedding, and plugin will attempt to display only relevant, public post types that use normal WordPress loop and display methods.', 'coresocial' ) . '</p>' .
			'<p>' . __( 'For bbPress forums and topics, plugin has different embed method, and that\'s why bbPress post types are not displayed among auto embed post types.', 'coresocial' ) . '</p>'
		);
	}
}
