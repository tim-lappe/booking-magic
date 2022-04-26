<?php

namespace TLBM\ApiUtils;

class SanitizingWrapper implements Contracts\SanitizingInterface {

	/**
	 * @inheritDoc
	 */
	public function sanitizeEmail( string $email ): string {
		return sanitize_email($email);
	}

	/**
	 * @inheritDoc
	 */
	public function sanitizeFilename( string $filename ): string {
		return sanitize_file_name($filename);
	}

	/**
	 * @inheritDoc
	 */
	public function sanitizeHexColor( string $hexColor ): string {
		return sanitize_hex_color($hexColor);
	}

	/**
	 * @inheritDoc
	 */
	public function sanitizeHexColorNoHash( string $hexColor ): string {
		return sanitize_hex_color_no_hash($hexColor);
	}

	/**
	 * @inheritDoc
	 */
	public function sanitizeHtmlClass( string $htmLClass, string $fallback = "" ): string {
		return sanitize_html_class($htmLClass, $fallback);
	}

	/**
	 * @inheritDoc
	 */
	public function sanitizeKey( string $key ): string {
		return sanitize_key($key);
	}

	/**
	 * @inheritDoc
	 */
	public function anitizeMeta( string $metaKey, $metaValue, string $objectType, string $objectSubtype = '' ): string {
		return sanitize_meta($metaKey, $metaValue, $objectType, $objectSubtype);
	}

	/**
	 * @inheritDoc
	 */
	public function sanitizeMimeType( string $mimeType ): string {
		return sanitize_mime_type($mimeType);
	}

	/**
	 * @inheritDoc
	 */
	public function sanitizeOption( string $option, $value ): string {
		return sanitize_option($option, $value);
	}

	/**
	 * @inheritDoc
	 */
	public function sanitizeSqlOrderby( string $orderBy ): string {
		return sanitize_sql_orderby($orderBy);
	}

	/**
	 * @inheritDoc
	 */
	public function sanitizeTextfield( string $textfield ): string {
		return sanitize_text_field($textfield);
	}

	/**
	 * @inheritDoc
	 */
	public function sanitizeTextareaField( string $textareaField ): string {
		return sanitize_textarea_field($textareaField);
	}

	/**
	 * @inheritDoc
	 */
	public function sanitizeTitle( string $title, string $fallbackTitle = '', string $context = 'save' ): string {
		return sanitize_title($title, $fallbackTitle, $context);
	}

	/**
	 * @inheritDoc
	 */
	public function sanitizeTitleForQuery( string $title ): string {
		return sanitize_title_for_query($title);
	}

	/**
	 * @inheritDoc
	 */
	public function sanitizeTitleWithDashes( string $title, string $rawTitle = '', string $context = 'display' ): string {
		return sanitize_title_with_dashes($title, $rawTitle, $context);
	}

	/**
	 * @inheritDoc
	 */
	public function sanitizeUser( string $username, bool $strict = false ): string {
		return sanitize_user($username, $strict);
	}

	/**
	 * @inheritDoc
	 */
	public function sanitizeUrl( string $url, array $protocol = null ): string {
		return esc_url_raw($url, $protocol);
	}

	/**
	 * @inheritDoc
	 */
	public function kses( string $string, $allowed_html, array $allowed_protocols = [] ): string {
		return wp_kses($string, $allowed_html, $allowed_protocols);
	}

	/**
	 * @inheritDoc
	 */
	public function ksesPost( string $data ): string {
		return wp_kses_post($data);
	}
}