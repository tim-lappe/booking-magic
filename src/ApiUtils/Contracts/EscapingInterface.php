<?php

namespace TLBM\ApiUtils\Contracts;

interface EscapingInterface {

	/**
	 * @param string $text
	 *
	 * @return string
	 */
	public function escAttr(string $text): string;

	/**
	 * @param string $text
	 *
	 * @return string
	 */
	public function escHtml(string $text): string;

	/**
	 * @param string $js
	 *
	 * @return string
	 */
	public function escJs(string $js): string;

	/**
	 * @param string $textarea
	 *
	 * @return string
	 */
	public function escTextarea(string $textarea): string;

	/**
	 * @param string $url
	 * @param array|null $protocols
	 * @param string $context
	 *
	 * @return string
	 */
	public function escUrl(string $url, array $protocols = null, string $context = 'display'): string;

	/**
	 * @param string $url
	 * @param array|null $protocols
	 *
	 * @return string
	 */
	public function escUrlRaw(string $url, array $protocols = null): string;
}