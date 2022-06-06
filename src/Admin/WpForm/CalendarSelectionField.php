<?php


namespace TLBM\Admin\WpForm;

if ( !defined('ABSPATH')) {
    return;
}

use TLBM\Admin\WpForm\Contracts\FormFieldReadVarsInterface;
use TLBM\Calendar\CalendarHelper;
use TLBM\Entity\Calendar;
use TLBM\Entity\CalendarCategory;
use TLBM\Entity\CalendarSelection;
use TLBM\MainFactory;
use TLBM\Repository\Contracts\EntityRepositoryInterface;

class CalendarSelectionField extends FormFieldBase implements FormFieldReadVarsInterface
{

	/**
	 * @var EntityRepositoryInterface
	 */
    private EntityRepositoryInterface $entityRepository;

    /**
     * @var bool
     */
    protected bool $allowTagSelection;

    /**
     * @param string $name
     * @param string $title
     * @param bool $allowTagSelection
     */
    public function __construct(
        string $name,
        string $title,
        bool $allowTagSelection = true
    ) {
        parent::__construct($name, $title);

        $this->entityRepository = MainFactory::get(EntityRepositoryInterface::class);
        $this->allowTagSelection = $allowTagSelection;
    }

    /**
     * @param mixed $value
     *
     * @return void
     */
    public function displayContent($value): void
    {
        if ( !$value) {
            $value = new CalendarSelection();
        }

        $calendarHelper = MainFactory::create(CalendarHelper::class);
        $calendarKeyValues      = $calendarHelper->getCalendarKeyValues();

        $tagKeyValues = [];
        if($this->allowTagSelection) {
            $tagKeyValues = $calendarHelper->getTagKeyValues();
        }
        ?>
        <tr>
            <th scope="row"><label for="<?php echo $this->escaping->escAttr($this->name) ?>"><?php echo $this->escaping->escHtml($this->title) ?></label></th>
            <td>
                <div
                        data-json="<?php echo $this->escaping->escAttr(urlencode(json_encode($value))); ?>"
                        data-calendars="<?php echo $this->escaping->escAttr(urlencode(json_encode($calendarKeyValues))); ?>"
                        data-tags="<?php echo $this->escaping->escAttr(urlencode(json_encode($tagKeyValues))); ?>"
                        data-name="<?php echo $this->escaping->escAttr($this->name) ?>"
                        class="tlbm-calendar-picker">
                </div>
            </td>
        </tr>
        <?php
    }

    /**
     * @param string $name
     * @param mixed $vars
     *
     * @return CalendarSelection
     */
    public function readFromVars(string $name, $vars): CalendarSelection
    {
        if (isset($vars[$name])) {
            $decoded_var = urldecode($vars[$name]);
            $jsonObject        = json_decode($decoded_var);
            if ($jsonObject) {
                if (isset($jsonObject->calendar_ids) && isset($jsonObject->tag_ids) && isset($jsonObject->selection_mode)) {
                    $selection = new CalendarSelection();
                    if ($selection->setSelectionMode($jsonObject->selection_mode)) {
                        foreach ($jsonObject->calendar_ids as $calendar_id) {
                            $calendar = $this->entityRepository->getEntity(Calendar::class,intval($calendar_id));
                            if ($calendar) {
                                $selection->addCalendar($calendar);
                            }
                        }

                        foreach ($jsonObject->tag_ids as $tag_id) {
                            $category = $this->entityRepository->getEntity(CalendarCategory::class,intval($tag_id));
                            if ($category) {
                                $selection->addCalendarCategory($category);
                            }
                        }
                    }

                    return $selection;
                }
            }
        }

        return new CalendarSelection();
    }
}