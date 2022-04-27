<?php

namespace TLBM\Admin\Pages\SinglePages;

use TLBM\Admin\Tables\BookingListTable;
use TLBM\Admin\Tables\RulesListTable;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\ApiUtils\Contracts\TimeUtilsInterface;
use TLBM\Entity\Calendar;
use TLBM\Entity\ManageableEntity;
use TLBM\MainFactory;
use TLBM\Repository\Contracts\EntityRepositoryInterface;
use TLBM\Repository\Query\BookingsQuery;
use TLBM\Repository\Query\RulesQuery;
use TLBM\Validation\ValidatorFactory;

/**
 * @extends EntityEditPage<Calendar>
 */
class CalendarEditPage extends EntityEditPage
{

    /**
     * @var EntityRepositoryInterface
     */
    private EntityRepositoryInterface $entityRepository;

    /**
     * @var LocalizationInterface
     */
    protected LocalizationInterface $localization;

    public function __construct(EntityRepositoryInterface $entityRepository, LocalizationInterface $localization)
    {
        $this->localization     = $localization;
        $this->entityRepository = $entityRepository;
        parent::__construct($this->localization->getText("Calendar", TLBM_TEXT_DOMAIN), "calendar-edit", "booking-calendar-edit", false);
    }

    /**
     * @return void
     */
    public function displayEntityEditForm(): void
    {
        $calendar = $this->getEditingEntity();
        if ( !$calendar) {
            $calendar = new Calendar();
        }

        ?>

        <div class="tlbm-admin-page-tile">
            <input value="<?php echo $this->escaping->escAttr($calendar->getTitle()); ?>" placeholder="<?php
            $this->localization->echoText("Enter Title here", TLBM_TEXT_DOMAIN) ?>" type="text" name="title" class="tlbm-admin-form-input-title">
        </div>
        <?php
        if ($this->getEditingEntity()): ?>
            <div class="tlbm-admin-page-tile-row">
                <div class="tlbm-admin-page-tile">
                    <h2><?php
                        $this->localization->echoText("Last Bookings", TLBM_TEXT_DOMAIN) ?></h2>
                    <?php
                    $bookingsTable = MainFactory::create(BookingListTable::class);
                    $bookingsTable->setSlim(true);

                    $query = MainFactory::create(BookingsQuery::class);
                    $query->setFilterCalendars([$calendar]);
                    $query->setLimit(5);
                    $bookingsTable->setFixedItems(iterator_to_array($query->getResult()));
                    $bookingsTable->prepare_items();
                    $bookingsTable->display();
                    ?>
                </div>
                <div class="tlbm-admin-page-tile">
                    <h2><?php
                        $this->localization->echoText("Rules", TLBM_TEXT_DOMAIN) ?></h2>
                    <?php
                    $rulesTable = MainFactory::create(RulesListTable::class);
                    $rulesTable->setSlim(true);

                    $query = MainFactory::create(RulesQuery::class);
                    $query->setFilterCalendarsIds([$calendar->getId()]);
                    $query->setLimit(10);
                    $rulesTable->setFixedItems(iterator_to_array($query->getResult()));
                    $rulesTable->prepare_items();
                    $rulesTable->display();
                    ?>
                </div>
            </div>
        <?php
        endif; ?>

        <?php
    }

    /**
     * @param mixed $vars
     * @param ManageableEntity|null $savedEntity
     *
     * @return array
     */
    public function onSaveEntity($vars, ?ManageableEntity &$savedEntity): array
    {
        $timeUtils = MainFactory::get(TimeUtilsInterface::class);
        $calendar  = $this->getEditingEntity();
        if (!$calendar) {
            $calendar = new Calendar();
        }

        $calendarValidator = ValidatorFactory::createCalendarValidator($calendar);
        $calendar->setTimestampEdited($timeUtils->time());
        $calendar->setTitle($this->sanitizing->sanitizeTextfield($vars['title']));

        $validationResult = $calendarValidator->getValidationErrors();

        if(count($validationResult) == 0) {
            if($this->entityRepository->saveEntity($calendar)) {
                $savedEntity = $calendar;

                return ["success" => $this->localization->getText("Calendar has been saved", TLBM_TEXT_DOMAIN)
                ];
            } else {
                return ["error" => $this->localization->getText("An internal error occured. ", TLBM_TEXT_DOMAIN)
                ];
            }
        }

        return $validationResult;
    }

    /**
     * @param int $id
     *
     * @return ManageableEntity|null
     */
    protected function getEntityById(int $id): ?ManageableEntity
    {
        return $this->entityRepository->getEntity(Calendar::class, $id);
    }
}