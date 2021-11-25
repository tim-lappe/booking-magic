<?php
namespace TLBM\Model;

use DateInterval;
use DateTime;
use Exception;

if( ! defined( 'ABSPATH' ) ) {
	return;
}

class Calendar {

	/**
	 * @var int
	 */
	public $wp_post_id;


    /**
     * @var string
     */
	public $title;


	public function __construct() { }
}