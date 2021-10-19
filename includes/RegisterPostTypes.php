<?php

namespace TL_Booking;

if( !defined( 'ABSPATH' ) ) {
	return;
}

class RegisterPostTypes {

	public function __construct() {
		add_action("init", array($this, "register_post_types"));
	}

	public function register_post_types() {
		register_post_type(TLBM_PT_CALENDAR, array(
			"labels" => array(
				"name" => _x("TL Booking", TLBM_PT_CALENDAR, TLBM_TEXT_DOMAIN),
				"singular_name" => _x("Booking Calendar", TLBM_PT_CALENDAR, TLBM_TEXT_DOMAIN),
				"add_new" => _x("Add New", TLBM_PT_CALENDAR, TLBM_TEXT_DOMAIN),
				"add_new_item" => _x("Add New Calendar", TLBM_PT_CALENDAR, TLBM_TEXT_DOMAIN),
				"edit_item" => _x("Edit Calendar", TLBM_PT_CALENDAR, TLBM_TEXT_DOMAIN),
				"new_item" => _x("New Calendar", TLBM_PT_CALENDAR, TLBM_TEXT_DOMAIN),
				"view_item" => _x("View Calendar", TLBM_PT_CALENDAR, TLBM_TEXT_DOMAIN),
				"view_items" => _x("View Calendars", TLBM_PT_CALENDAR, TLBM_TEXT_DOMAIN),
				"search_items" => _x("Search Calendars", TLBM_PT_CALENDAR, TLBM_TEXT_DOMAIN),
				"not_found" => _x("No Calendars found", TLBM_PT_CALENDAR, TLBM_TEXT_DOMAIN),
				"not_found_in_trash" => _x("No Calendars found in trash", TLBM_PT_CALENDAR, TLBM_TEXT_DOMAIN),
				"parent_item_colon" => _x("Parent Calendar", TLBM_PT_CALENDAR, TLBM_TEXT_DOMAIN),
				"all_items" => _x("Calendars", TLBM_PT_CALENDAR, TLBM_TEXT_DOMAIN),
				"archives" => _x("Calendar Archives", TLBM_PT_CALENDAR, TLBM_TEXT_DOMAIN),
				"attributes" => _x("Calendar Attributes", TLBM_PT_CALENDAR, TLBM_TEXT_DOMAIN),
				"insert_into_item" => _x("Insert into Calendar", TLBM_PT_CALENDAR, TLBM_TEXT_DOMAIN),
				"uploaded_to_this_item" => _x("Uploaded to this Calendar", TLBM_PT_CALENDAR, TLBM_TEXT_DOMAIN),
			),
			"description" => _x("Booking Calendar", TLBM_PT_CALENDAR, TLBM_TEXT_DOMAIN),
			"public" => false,
			"hierachical" => false,
			"show_in_rest" => false,
			"rewrite" => array("slug" => "booking"),
			"show_ui" => true,
			"show_in_menu" => "admin.php?page=booking-magic-calendar",
			"menu_icon" => "dashicons-calendar-alt",
			"supports" => array("title")
		));


        register_post_type(TLBM_PT_BOOKING, array(
            "labels" => array(
                "name" => _x("Bookings", TLBM_PT_BOOKING, TLBM_TEXT_DOMAIN),
                "singular_name" => _x("Booking", TLBM_PT_BOOKING, TLBM_TEXT_DOMAIN),
                "add_new" => _x("Add Booking", TLBM_PT_BOOKING, TLBM_TEXT_DOMAIN),
                "add_new_item" => _x("Add New Booking", TLBM_PT_BOOKING, TLBM_TEXT_DOMAIN),
                "edit_item" => _x("Edit Booking", TLBM_PT_BOOKING, TLBM_TEXT_DOMAIN),
                "new_item" => _x("New Booking", TLBM_PT_BOOKING, TLBM_TEXT_DOMAIN),
                "view_item" => _x("View Booking", TLBM_PT_BOOKING, TLBM_TEXT_DOMAIN),
                "view_items" => _x("View Bookings", TLBM_PT_BOOKING, TLBM_TEXT_DOMAIN),
                "search_items" => _x("Search Bookings", TLBM_PT_BOOKING, TLBM_TEXT_DOMAIN),
                "not_found" => _x("No Bookings found", TLBM_PT_BOOKING, TLBM_TEXT_DOMAIN),
                "not_found_in_trash" => _x("No Bookings found in trash", TLBM_PT_BOOKING, TLBM_TEXT_DOMAIN),
                "parent_item_colon" => _x("Parent Booking", TLBM_PT_BOOKING, TLBM_TEXT_DOMAIN),
                "all_items" => _x("Bookings", TLBM_PT_BOOKING, TLBM_TEXT_DOMAIN),
                "archives" => _x("Bookings Archives", TLBM_PT_BOOKING, TLBM_TEXT_DOMAIN),
                "attributes" => _x("Bookings Attributes", TLBM_PT_BOOKING, TLBM_TEXT_DOMAIN),
                "insert_into_item" => _x("Insert into Booking", TLBM_PT_BOOKING, TLBM_TEXT_DOMAIN),
                "uploaded_to_this_item" => _x("Uploaded to this Booking", TLBM_PT_BOOKING, TLBM_TEXT_DOMAIN),
            ),
            "description" => _x("Bookings", TLBM_PT_BOOKING, TLBM_TEXT_DOMAIN),
            "public" => false,
            "hierachical" => false,
            "show_in_rest" => false,
            "show_in_menu" => false,
            "rewrite" => array("slug" => "booking"),
            "show_ui" => true,
            "menu_icon" => "dashicons-calendar-alt",
            "supports" => array("title"),
        ));

		register_post_type(TLBM_PT_RULES, array(
			"labels" => array(
				"name" => _x("Rules", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
				"singular_name" => _x("Rule", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
				"add_new" => _x("Add Rule", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
				"add_new_item" => _x("Add New Rule", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
				"edit_item" => _x("Edit Rule", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
				"new_item" => _x("New Rule", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
				"view_item" => _x("View Rule", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
				"view_items" => _x("View Rules", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
				"search_items" => _x("Search Rules", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
				"not_found" => _x("No Rules found", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
				"not_found_in_trash" => _x("No Rules found in trash", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
				"parent_item_colon" => _x("Parent Rule", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
				"all_items" => _x("Rules", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
				"archives" => _x("Rules Archives", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
				"attributes" => _x("Rules Attributes", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
				"insert_into_item" => _x("Insert into Rule", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
				"uploaded_to_this_item" => _x("Uploaded to this Rule", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
			),
			"description" => _x("Capacity Rule", TLBM_PT_CALENDAR, TLBM_TEXT_DOMAIN),
			"public" => false,
			"hierachical" => true,
			"show_in_rest" => false,
			"rewrite" => array("slug" => "rule"),
			"show_ui" => true,
			"show_in_menu" => false,
			"menu_icon" => "dashicons-calendar-alt",
			"supports" => array("title")
		));

		register_post_type(TLBM_PT_FORMULAR, array(
			"labels" => array(
				"name" => _x("Forms", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
				"singular_name" => _x("Form", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
				"add_new" => _x("Add Form", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
				"add_new_item" => _x("Add New Form", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
				"edit_item" => _x("Edit Form", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
				"new_item" => _x("New Form", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
				"view_item" => _x("View Form", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
				"view_items" => _x("View Forms", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
				"search_items" => _x("Search Forms", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
				"not_found" => _x("No Forms found", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
				"not_found_in_trash" => _x("No Forms found in trash", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
				"parent_item_colon" => _x("Parent Form", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
				"all_items" => _x("Forms", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
				"archives" => _x("Forms Archives", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
				"attributes" => _x("Forms Attributes", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
				"insert_into_item" => _x("Insert into Form", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
				"uploaded_to_this_item" => _x("Uploaded to this Form", TLBM_PT_RULES, TLBM_TEXT_DOMAIN),
			),
			"description" => _x("Forms", TLBM_PT_CALENDAR, TLBM_TEXT_DOMAIN),
			"public" => false,
			"hierachical" => false,
			"show_in_rest" => false,
			"rewrite" => array("slug" => "form"),
			"show_ui" => true,
			"menu_icon" => "dashicons-calendar-alt",
			"show_in_menu" => false,
			"supports" => array("title")
		));
	}
}
