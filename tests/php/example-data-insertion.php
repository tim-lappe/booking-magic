<?php

use TLBM\Entity\Calendar;
use TLBM\Entity\CalendarGroup;
use TLBM\Entity\CalendarSelection;
use TLBM\Entity\Form;
use TLBM\Entity\Rule;
use TLBM\Entity\RuleAction;
use TLBM\Entity\RulePeriod;
use TLBM\MainFactory;
use TLBM\Repository\Contracts\EntityRepositoryInterface;

$repository = MainFactory::get(EntityRepositoryInterface::class);

/**
 * Insert example Calendars (8)
 */
$calendarAlice = new Calendar("Alice");         //ID: 1
$repository->saveEntity($calendarAlice);

$calendarBob = new Calendar("Bob");             //ID: 2
$repository->saveEntity($calendarBob);

$calendarCarl = new Calendar("Carl");           //ID: 3
$repository->saveEntity($calendarCarl);

$calendarDan = new Calendar("Dan");             //ID: 4
$repository->saveEntity($calendarDan);

$calendarEmil = new Calendar("Emil");           //ID: 5
$repository->saveEntity($calendarEmil);

$calendarFabian = new Calendar("Fabian");       //ID: 6
$repository->saveEntity($calendarFabian);

$calendarGerd = new Calendar("Gerd");           //ID: 7
$repository->saveEntity($calendarGerd);

$calendarAbc = new Calendar("ABCDEFGHIJKLMNOPQRSTUVWXYLOÜÖ");   //ID: 8
$repository->saveEntity($calendarAbc);

/**
 * Calendar Groups
 */
$calgroup1 = new CalendarGroup("Group1",
                                            TLBM_BOOKING_DISTRIBUTION_EVENLY,
                                            new CalendarSelection(TLBM_CALENDAR_SELECTION_TYPE_ALL));
$repository->saveEntity($calgroup1);
$calgroup2 = new CalendarGroup("Group2",
                                            TLBM_BOOKING_DISTRIBUTION_EVENLY,
                                            new CalendarSelection(TLBM_CALENDAR_SELECTION_TYPE_ONLY,
                                                                  [
                                                                      $calendarAbc, $calendarBob, $calendarCarl
                                                                  ]));
$repository->saveEntity($calgroup2);

$rule1 = new Rule("DefaultRule1", 0, new CalendarSelection(TLBM_CALENDAR_SELECTION_TYPE_ALL),
        [
            new RuleAction(null, "day_slot", "mo_to_fr", 0, 0, 1, ["mode" => "set",
                "amount" => 5
            ]),
        ]
);
$repository->saveEntity($rule1);

$rule2 = new Rule("DefaultRule2", 1, new CalendarSelection(TLBM_CALENDAR_SELECTION_TYPE_ALL),
       [new RuleAction(null, "day_slot", "every_day", 0, 0, 1, ["mode" => "add",
           "amount" => 5
       ]),
       ]
);
$repository->saveEntity($rule2);

$rule3 = new Rule("DefaultRule3", 2,
       new CalendarSelection(TLBM_CALENDAR_SELECTION_TYPE_ONLY, [
           $calendarAlice, $calendarFabian
           ]),
       [new RuleAction(null, "day_slot", "every_day", 0, 0, 1, ["mode" => "set",
           "amount" => 0
       ]),
       ],[
           new RulePeriod(null, 1646130842, true, 1646908442, true)
       ]
);
$repository->saveEntity($rule3);

$form = new Form();
$form->setFormData(json_decode(
    '{"children":[{"children":[],"canReceiveNewChildren":false,"id":0.17213805365944412,"formData":{"unique_name":"calendar","selected_calendar":"calendar_2","weekdays_form":"short","title":"Time","name":"time","required":"yes","css_classes":"","sourceId":"group_5"},"parent":null},{"children":[{"children":[{"children":[],"canReceiveNewChildren":false,"id":0.16417093847814856,"formData":{"unique_name":"field_first_name","title":"First Name","name":"first_name","required":"yes","css_classes":""},"parent":null}],"canReceiveNewChildren":true,"id":0.5259958468805612,"parent":null},{"children":[{"children":[],"canReceiveNewChildren":false,"id":0.9498564141784157,"formData":{"unique_name":"field_last_name","title":"Last Name","name":"last_name","required":"yes","css_classes":""},"parent":null}],"canReceiveNewChildren":true,"id":0.5056189795786189,"parent":null}],"canReceiveNewChildren":false,"id":0.21156212442778732,"formData":{"unique_name":"2er_columns","split_1":"1","split_2":"1","css_classes":""},"parent":null},{"children":[],"canReceiveNewChildren":false,"id":0.18965774693354165,"formData":{"unique_name":"field_address_line","title":"Address","name":"address","required":"yes","css_classes":""},"parent":null},{"children":[{"children":[{"children":[],"canReceiveNewChildren":false,"id":0.38940300391995863,"formData":{"unique_name":"field_zip_code","title":"Zip","name":"zip","required":"yes","css_classes":""},"parent":null}],"canReceiveNewChildren":true,"id":0.4127321115096815,"parent":null},{"children":[{"children":[],"canReceiveNewChildren":false,"id":0.7055132894975013,"formData":{"unique_name":"field_city","title":"City","name":"city","required":"yes","css_classes":""},"parent":null}],"canReceiveNewChildren":true,"id":0.937963153596163,"parent":null}],"canReceiveNewChildren":false,"id":0.6706747626671175,"formData":{"unique_name":"2er_columns","split_1":"1","split_2":"3","css_classes":""},"parent":null},{"children":[],"canReceiveNewChildren":false,"id":0.18276736145568806,"formData":{"unique_name":"field_contact_email","title":"E-Mail","name":"contact_email","required":"yes","css_classes":""},"parent":null}],"canReceiveNewChildren":false,"id":0.5775626864069493}',
                   true)
);
$repository->saveEntity($form);