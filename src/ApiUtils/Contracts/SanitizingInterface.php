<?php

namespace TLBM\ApiUtils\Contracts;

interface SanitizingInterface {

	/**
	 * @param string $email
	 *
	 * @return string
	 */
	public function sanitizeEmail(string $email): string;

	/**
	 * @param string $filename
	 *
	 * @return string
	 */
	public function sanitizeFilename(string $filename): string;

	/**
	 * @param string $hexColor
	 *
	 * @return string
	 */
	public function sanitizeHexColor(string $hexColor): string;

	/**
	 * @param string $hexColor
	 *
	 * @return string
	 */
	public function sanitizeHexColorNoHash(string $hexColor): string;

	/**
	 * @param string $htmLClass
	 * @param string $fallback
	 *
	 * @return string
	 */
	public function sanitizeHtmlClass(string $htmLClass, string $fallback = ""): string;

	/**
	 * @param string $key
	 *
	 * @return string
	 */
	public function sanitizeKey(string $key): string;

	/**
	 * @param string $metaKey
	 * @param mixed $metaValue
	 * @param string $objectType
	 * @param string $objectSubtype
	 *
	 * @return string
	 */
	public function anitizeMeta(string $metaKey, $metaValue, string $objectType, string $objectSubtype = '' ): string;

	/**
	 * @param string $mimeType
	 *
	 * @return string
	 */
	public function sanitizeMimeType(string $mimeType): string;

	/**
	 * @param string $option
	 * @param mixed $value
	 *
	 * @return string
	 */
	public function sanitizeOption(string $option, $value): string;

	/**
	 * @param string $orderBy
	 *
	 * @return string
	 */
	public function sanitizeSqlOrderby(string $orderBy): string;

	/**
	 * @param string $textfield
	 *
	 * @return string
	 */
	public function sanitizeTextfield(string $textfield): string;

	/**
	 * @param string $textareaField
	 *
	 * @return string
	 */
	public function sanitizeTextareaField(string $textareaField): string;

	/**
	 * @param string $title
	 * @param string $fallbackTitle
	 * @param string $context
	 *
	 * @return string
	 */
	public function sanitizeTitle(string $title, string $fallbackTitle = '', string $context = 'save'): string;

	/**
	 * @param string $title
	 *
	 * @return string
	 */
	public function sanitizeTitleForQuery(string $title): string;

	/**
	 * @param string $title
	 * @param string $rawTitle
	 * @param string $context
	 *
	 * @return string
	 */
	public function sanitizeTitleWithDashes(string $title, string $rawTitle = '', string $context = 'display'): string;

	/**
	 * @param string $username
	 * @param bool $strict
	 *
	 * @return string
	 */
	public function sanitizeUser(string $username, bool $strict = false): string;

	/**
	 * @param string $url
	 * @param array|null $protocol
	 *
	 * @return string
	 */
	public function sanitizeUrl(string $url, array $protocol = null): string;

	/**
	 * @param string $string
	 * @param mixed $allowed_html
	 * @param array $allowed_protocols
	 *
	 * @return string
	 */
	public function kses(string $string, $allowed_html, array $allowed_protocols = [] ): string;

	/**
	 * @param string $data
	 *
	 * @return string
	 */
	public function ksesPost(string $data): string;
}