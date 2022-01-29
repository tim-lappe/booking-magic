<?php
const TLBM_VERSION = "Dev 1.0";
const TLBM_DIR = __DIR__;
const TLBM_PLUGIN_DIR = __DIR__;

const TLBM_INCLUDES_DIR = TLBM_DIR . "/includes/";
const TLBM_MAIL_TEMPLATES = TLBM_DIR . "/templats/email/";

const TLBM_PT_CALENDAR = "tlbm_calendar";
const TLBM_PT_CALENDAR_GROUPS = "tlbm_calendar_groups";
const TLBM_PT_RULES = "tlbm_calendar_rules";
const TLBM_PT_FORMULAR = "tlbm_fomular";
const TLBM_PT_BOOKING = "tlbm_booking";

const TLBM_MB_PREFIX = "tlbm_mb_";
const TLBM_CALENDAR_META_CALENDAR_SETUP = "calendar-setup";
const TLBM_CALENDAR_META_CALENDAR_RULES = "calendar-rules";

const TLBM_MAIN_CSS_SLUG = "tlbm-main";
const TLBM_FRONTEND_JS_SLUG = "tlbm-script-frontend";
const TLBM_ADMIN_JS_SLUG = "tlbm-script-admin";

const TLBM_TEXT_DOMAIN = "tl-booking-calendar";
const TLBM_SHORTCODETAG_FORM = "booking_magic_form";
const TLBM_DELETE_DATA_ON_DEACTIVATION = true;


if(!defined("WP_PLUGIN_DIR")) {
    define("WP_PLUGIN_DIR", "");
}