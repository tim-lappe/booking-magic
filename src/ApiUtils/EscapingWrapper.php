<?php

namespace TLBM\ApiUtils;

class EscapingWrapper implements Contracts\EscapingInterface {

	/**
	 * @inheritDoc
	 */
	public function escAttr( string $text ): string {
		return esc_attr($text);
	}

	/**
	 * @inheritDoc
	 */
	public function escHtml( string $text ): string {
		return esc_html($text);
	}

	/**
	 * @inheritDoc
	 */
	public function escJs( string $js ): string {
		return esc_js($js);
	}

	/**
	 * @inheritDoc
	 */
	public function escTextarea( string $textarea ): string {
		return esc_textarea($textarea);
	}

	/**
	 * @inheritDoc
	 */
	public function escUrl( string $url, array $protocols = null, string $context = 'display' ): string {
		return esc_url($url, $protocols, $context);
	}

	/**
	 * @inheritDoc
	 */
	public function escUrlRaw( string $url, array $protocols = null ): string {
		return esc_url_raw($url, $protocols);
	}
}