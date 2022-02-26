<?php

use TLBM\Entity\Calendar;
use TLBM\Entity\CalendarSelection;
use TLBM\MainFactory;
use TLBM\Repository\Contracts\EntityRepositoryInterface;

$repository = MainFactory::get(EntityRepositoryInterface::class);

/**
 * Insert example Calendars (8)
 */
$calendarAlice = new Calendar("Alice");
$repository->saveEntity($calendarAlice);
$calendarBob = new Calendar("Bob");
$repository->saveEntity($calendarBob);
$calendarCarl = new Calendar("Carl");
$repository->saveEntity($calendarCarl);
$calendarDan = new Calendar("Dan");
$repository->saveEntity($calendarDan);
$calendarEmil = new Calendar("Emil");
$repository->saveEntity($calendarEmil);
$calendarFabian = new Calendar("Fabian");
$repository->saveEntity($calendarFabian);
$calendarGerd = new Calendar("Gerd");
$repository->saveEntity($calendarGerd);
$calendarAbc = new Calendar("ABCDEFGHIJKLMNOPQRSTUVWXYLOÜÖ");
$repository->saveEntity($calendarAbc);

/**
 * Calendar Groups
 */
$calgroup1 = new \TLBM\Entity\CalendarGroup("Group1",
                                            TLBM_BOOKING_DISTRIBUTION_EVENLY,
                                            new CalendarSelection(TLBM_CALENDAR_SELECTION_TYPE_ALL));
$repository->saveEntity($calgroup1);
$calgroup2 = new \TLBM\Entity\CalendarGroup("Group2",
                                            TLBM_BOOKING_DISTRIBUTION_EVENLY,
                                            new CalendarSelection(TLBM_CALENDAR_SELECTION_TYPE_ALL,
                                                                  [
                                                                      $calendarAbc, $calendarBob, $calendarCarl
                                                                  ]));
$repository->saveEntity($calgroup2);



