<?php


namespace TLBM\Form;

use TLBM\Model\Form;
use WP_Post;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

class FormManager {

	/**
	 * @param $id
	 *
	 * @return false|Form
     */
	public static function GetForm($id) {
		$form_post = get_post($id);
		if($form_post instanceof WP_Post) {
			$form = new Form();
			$form->wp_post_id = $form_post->ID;
			$form->frontend_html = get_post_meta($form_post->ID, "frontend-html", true);
			$form->form_data =  get_post_meta($form_post->ID, "form-data", true);
			$form->title = $form_post->post_title;
			return $form;
		}

		return false;
	}

	/**
	 * @param array $get_posts_options
	 * @param string $orderby
	 * @param string $order
	 *
	 * @return Form[]
	 */
	public static function GetAllForms($get_posts_options = array(), $orderby = "priority", $order = "desc"): array {
		$wp_posts = get_posts(array(
          "post_type" => TLBM_PT_FORMULAR,
          "numberposts" => -1
        ) + $get_posts_options);

		$forms = array();
		foreach ($wp_posts as $post) {
			$forms[] = self::GetForm($post->ID);
		}

		usort($forms, function ($a, $b) use ($orderby, $order) {
			if(strtolower($order) == "asc") {
				return $a->{$orderby} > $b->{$orderby};
			}
			if(strtolower($order) == "desc") {
				return $a->{$orderby} < $b->{$orderby};
			}
			return $a->{$orderby} < $b->{$orderby};
		});

		return $forms;
	}
}