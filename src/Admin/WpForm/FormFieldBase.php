<?php


namespace TLBM\Admin\WpForm;

use TLBM\ApiUtils\Contracts\EscapingInterface;
use TLBM\MainFactory;

if ( !defined('ABSPATH')) {
    return;
}

abstract class FormFieldBase
{

    /**
     * @var string
     */
    public string $name;

    /**
     * @var string
     */
    public string $title;

	/**
	 * @var EscapingInterface
	 */
	protected EscapingInterface $escaping;

    /**
     * @param string $name
     * @param string $title
     */
    public function __construct(string $name, string $title)
    {
        $this->name  = $name;
        $this->title = $title;

		$this->escaping = MainFactory::get(EscapingInterface::class);
    }

    /**
     * @param mixed $value
     *
     * @return void
     */
    abstract public function displayContent($value): void;
}