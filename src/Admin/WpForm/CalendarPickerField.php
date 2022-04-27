<?php


namespace TLBM\Admin\WpForm;

if ( !defined('ABSPATH')) {
    return;
}

use TLBM\Admin\WpForm\Contracts\FormFieldReadVarsInterface;
use TLBM\ApiUtils\Contracts\EscapingInterface;
use TLBM\Entity\Calendar;
use TLBM\Entity\CalendarSelection;
use TLBM\MainFactory;
use TLBM\Repository\Contracts\EntityRepositoryInterface;
use TLBM\Repository\Query\CalendarQuery;

class CalendarPickerField extends FormFieldBase implements FormFieldReadVarsInterface
{

	/**
	 * @var EntityRepositoryInterface
	 */
    private EntityRepositoryInterface $entityRepository;

	/**
	 * @var EscapingInterface
	 */
    private EscapingInterface $escaping;

    /**
     * @param EntityRepositoryInterface $entityRepository
     * @param string $name
     * @param string $title
     */
    public function __construct(
        EntityRepositoryInterface $entityRepository,
        string $name,
        string $title
    ) {
        $this->entityRepository = $entityRepository;
        $this->escaping = MainFactory::get(EscapingInterface::class);

        parent::__construct($name, $title);
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

        $calendarQuery = MainFactory::create(CalendarQuery::class);
        $cals      = $calendarQuery->getResult();
        $calendars = array();
        foreach ($cals as $cal) {
            $calendars[$cal->getId()] = empty($cal->getTitle()) ? $cal->getId() : $cal->getTitle();
        }

        ?>
        <tr>
            <th scope="row"><label for="<?php echo $this->escaping->escAttr($this->name) ?>"><?php echo $this->escaping->escHtml($this->title) ?></label></th>
            <td>
                <div
                        data-json="<?php echo $this->escaping->escAttr(urlencode(json_encode($value))); ?>"
                        data-calendars="<?php echo $this->escaping->escAttr(urlencode(json_encode($calendars))); ?>"
                        data-name="<?php echo $this->escaping->escAttr($this->name) ?>"
                        class="tlbm-calendar-picker"></div>
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
            $json        = json_decode($decoded_var);
            if ($json) {
                if (isset($json->calendar_ids) && isset($json->selection_mode)) {
                    $selection = new CalendarSelection();
                    if ($selection->setSelectionMode($json->selection_mode)) {
                        foreach ($json->calendar_ids as $calendar_id) {
                            $calendar = $this->entityRepository->getEntity(Calendar::class,intval($calendar_id));
                            if ($calendar) {
                                $selection->addCalendar($calendar);
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