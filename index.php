<?php
/*
Plugin Name: Kontak
Plugin URI: http://www.tekinoypi.com/
Description: Displays a contact form with captcha that does not use sessions and submits the data by email to the specified email address.
Author: Koree Monteloyola
Version: 1.0
Author URI: http://www.tekinoypi.com/
*/
/*
Kontak web form for Wordpress
Copyright (C) 2012  Koree S. Monteloyola

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

include_once('includes/Kontak.php');
//add_action( 'init', 'Kontak::register_chunk_post_type');
add_action('init','Kontak::load_scripts');
//add_action( 'init', 'Kontak::register_shortcodes');
add_filter('the_content', 'Kontak::get_chunk', 7);
add_action('admin_menu', 'Kontak::create_admin_menu');
add_filter('plugin_action_links', 'Kontak::add_plugin_settings_link', 10, 2 );
/*EOF*/
