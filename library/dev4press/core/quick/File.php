<?php
/**
 * Name:    Dev4Press\v50\Core\Quick\File
 * Version: v5.0
 * Author:  Milan Petrovic
 * Email:   support@dev4press.com
 * Website: https://www.dev4press.com/
 *
 * @package Dev4PressLibrary
 *
 * == Copyright ==
 * Copyright 2008 - 2024 Milan Petrovic (email: support@dev4press.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>
 */

namespace Dev4Press\v50\Core\Quick;

use Exception;
use PharData;
use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class File {
	public static function size_format( $size, $decimals = 2, $sep = ' ', $strong = true ) : string {
		$_size = intval( $size );

		if ( strlen( $_size ) >= 10 ) {
			$_size = number_format( $_size / 1073741824, $decimals );
			$unit  = 'GB';
		} else if ( strlen( $_size ) <= 9 && strlen( $_size ) >= 7 ) {
			$_size = number_format( $_size / 1048576, $decimals );
			$unit  = 'MB';
		} else if ( strlen( $_size ) <= 6 && strlen( $_size ) >= 4 ) {
			$_size = number_format( $_size / 1024, $decimals );
			$unit  = 'KB';
		} else {
			$unit = 'B';
		}

		if ( floatval( $_size ) == intval( $_size ) ) {
			$_size = intval( $_size );
		}

		if ( $strong ) {
			return '<strong>' . $_size . '</strong>' . $sep . $unit;
		} else {
			return $_size . $sep . $unit;
		}
	}

	public static function get_contents( $path ) {
		global $wp_filesystem;

		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';

			WP_Filesystem();
		}

		return $wp_filesystem->get_contents( $path );
	}

	public static function put_contents( $path, $content ) {
		global $wp_filesystem;

		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';

			WP_Filesystem();
		}

		return $wp_filesystem->put_contents( $path, $content );
	}

	public static function gzip_uncompressed_size( $file_path ) {
		$fp = fopen( $file_path, 'rb' ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		fseek( $fp, - 4, SEEK_END );
		$buf = fread( $fp, 4 ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		$elm = unpack( 'V', $buf );

		return end( $elm );
	}

	public static function scan_dir( $path, $filter = 'files', $extensions = array(), $reg_expr = '', $full_path = false ) : array {
		$extensions = (array) $extensions;
		$filter     = ! in_array( $filter, array( 'folders', 'files', 'all' ) ) ? 'files' : $filter;
		$path       = str_replace( '\\', '/', $path );

		$final = array();

		if ( file_exists( $path ) ) {
			$files = scandir( $path );

			$path = rtrim( $path, '/' ) . '/';
			foreach ( $files as $file ) {
				$ext = pathinfo( $file, PATHINFO_EXTENSION );

				if ( empty( $extensions ) || in_array( $ext, $extensions ) ) {
					if ( substr( $file, 0, 1 ) != '.' ) {
						if (
							( is_dir( $path . $file ) && ( in_array( $filter, array( 'folders', 'all' ) ) ) ) ||
							( is_file( $path . $file ) && ( in_array( $filter, array( 'files', 'all' ) ) ) ) ||
							( ( is_file( $path . $file ) || is_dir( $path . $file ) ) && ( in_array( $filter, array( 'all' ) ) ) ) ) {
							$add = $full_path ? $path : '';

							if ( $reg_expr == '' ) {
								$final[] = $add . $file;
							} else if ( preg_match( $reg_expr, $file ) ) {
								$final[] = $add . $file;
							}
						}
					}
				}
			}
		}

		return $final;
	}

	public static function unpack_tar_gz( $file, $path ) {
		$result = false;
		$error  = '';

		try {
			$tar = new PharData( $file );

			$result = $tar->extractTo( $path, null, true );
		} catch ( Exception $e ) {
			$error = $e->getMessage();
		}

		if ( $result === true ) {
			return true;
		} else {
			return new WP_Error( 'extract', $error );
		}
	}
}
