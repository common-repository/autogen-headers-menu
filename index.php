<?php
/*
   Plugin Name: Autogen Header's Menu
   Plugin URI: http://amirshk.com/blog/wordpress-autogen-headers-menu-plugin/
   Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=Z3W2VRXFHK2KY   
   Description: Automatically create a main-menu from the headers in your post using a simple and configurable shortcode.
   Version: 1.0.1
   Author: Amirshk
   Author URI: http://amirshk.com/
   License: GPLv2 or later
*/

if (!class_exists("AmirshkAutogenHeaderMenu")) {
	class AmirshkAutogenHeaderMenu {
		var $menu;
		var $show_top_link = FALSE;
		var $show_header_numbers = FALSE;
		var $topStr;

		function AmirshkAutogenHeaderMenu() { //constructor
			$this->menu = array();
			$this->topStr = __('Top','autogen-headers-menu-plugin');
		}

		function pluginInit() {
			$plugin_dir = basename(dirname(__FILE__));
			load_plugin_textdomain( 'autogen-headers-menu-plugin', false, $plugin_dir );
			wp_register_style( 'autogen-headers-menu-plugin', plugins_url("css/style.css", __FILE__) );
		}

		function headerReplace($matches) {
			$level = $matches[1];
			$extra = $matches[2];
			$title = $matches[3];
			$id = strtolower(preg_replace("'\s'",'-',$title));
			if (preg_match('/tag=[\'\"]autogen_ignore[\"\']/', $extra)) {
				return "<h$level id='$id' $extra>$title</h$level>";
			}
			array_push($this->menu, array($id,$title,$level));
			$numbers = "";
			if ($this->show_header_numbers == TRUE) {
				$numbers = "class='autogen_menu'";
			}
			if ($this->show_top_link == TRUE) {
				return "<h$level id='$id' $numbers $extra>$title<a class='alignright top_link' href='#top'>$this->topStr</a></h$level>";
			}
			return "<h$level id='$id' $extra>$title</h$level>";
		}

		function menuToString($menuStr, $head_class, $div_class, $ol_class, $li_class, $a_class, $depth) {
			if ($li_class != "") $li_class = " class='$li_class'";
			if ($a_class != "") $a_class = " class='$a_class'";
			if ($head_class != "") $head_class = " class='$head_class'";
			if ($ol_class != "") $ol_class = " class='$ol_class'";
			if ($div_class != "") $div_class = " class='$div_class'";

			$str = "<div$div_class>\n<h2$head_class>$menuStr</h2>\n";
			$last = 0;
			$list_depth = 1;
			foreach ($this->menu as &$value) {
				$cur_level = $value[2];
				$cur_title = $value[1];
				$cur_id = $value[0];
				if ($cur_level > $depth) continue;
				if ($cur_level > $last) {
					$list_depth++;
					$space = str_repeat("\t",$list_depth);
					$str .= "$space<ol$ol_class tag='$list_depth'>\n";
				}
				else if ($cur_level < $last) {
					while ($list_depth > $cur_level) {
						$space = str_repeat("\t",$list_depth);
						$list_depth--;
						$str .= "$space</ol tag='$list_depth'>\n";
					}
				}
				$space = str_repeat("\t",$list_depth);
				$str .= "$space\t<li$li_class tag='$list_depth'><a$a_class href='#$cur_id'>$cur_title</a></li>\n";
				$last = $cur_level;
			}
			$space = str_repeat("\t",$list_depth);
			$str .= "$space</ol>\n</div>\n";
			return $str;
		}
		
		function addContent($content = '') {
			global $post;
			$show_top = get_post_meta($post->ID, "autogen_menu_show_top", TRUE);
			$use_numbers = get_post_meta($post->ID, "autogen_menu_show_numbering", TRUE);
			if (strtolower($show_top) == "true") {
				$this->show_top_link = TRUE;
			}
			if (strtolower($use_numbers) == "true") {
				$this->show_header_numbers = TRUE;
			}
			wp_enqueue_style('autogen-headers-menu-plugin');
			$pattern = "'<h(\d)(.*?)>(.*?)</h\d>'si";
			$content = preg_replace_callback(
					$pattern,
					array($this,'headerReplace'),
					$content);
			return $content;
		}
		
		function showMenu__shortcode($atts) {
			extract(shortcode_atts( array(
							'title' => __('Menu', 'autogen-headers-menu-plugin'),
							'head_class' => '',
							'div_class' => '',
							'ol_class' => 'autogen_ol_menu',
							'li_class' => '',
							'a_class' => '',
							'depth' => '10'
						     ), $atts ) );
			return $this->menuToString($title, $head_class, $div_class, $ol_class, $li_class, $a_class, $depth);
		}

	}

} //End Class AmirshkAutogenHeaderMenu

if (!class_exists("AmirshkAutogenHeaderMenuWidget")) {
	class AmirshkAutogenHeaderMenuWidget extends WP_Widget {

		public function __construct() {
			parent::__construct(
				'autogen_menu_widget', // Base ID
				'Autogen Menu Widget', // Name
				array( 'description' => __('Show the auto-generated menu for the post', 'autogen-headers-menu-plugin'), ) // Args
			);
		}

		function widget( $args, $instance ) {
			extract( $args );
			$title = apply_filters( 'widget_title', $instance['title'] );
			global $dl_autogenHeaderMenu;
			echo $dl_autogenHeaderMenu->menuToString($title,'autogen_head_w','div_w','autogen_ol_w','autogen_li_w','autogen_a_w',3);
		}

		function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );

			return $instance;
		}

		function form( $instance ) {
			if ( isset( $instance[ 'title' ] ) ) {
				$title = $instance[ 'title' ];
			}
			else {
				$title = __('Menu', 'autogen-headers-menu-plugin');
			}
			?>
			<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<?php 
		}
	}
}

if (class_exists("AmirshkAutogenHeaderMenu")) {
	$dl_autogenHeaderMenu = new AmirshkAutogenHeaderMenu();
}

function amirshk_autogen_register_widgets() {
	register_widget( 'AmirshkAutogenHeaderMenuWidget' );
}

//Actions and Filters
if (isset($dl_autogenHeaderMenu)) {
	//Actions
	add_action('plugins_loaded', array(&$dl_autogenHeaderMenu, 'pluginInit'));
	add_action('widgets_init', 'amirshk_autogen_register_widgets');
	//Filters
	add_filter('the_content', array(&$dl_autogenHeaderMenu, 'addContent'));
	//Shortcodes
	add_shortcode('autogen_menu', array(&$dl_autogenHeaderMenu, 'showMenu__shortcode'));
}

?>
