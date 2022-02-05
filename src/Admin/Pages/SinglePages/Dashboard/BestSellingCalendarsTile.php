<?php


namespace TLBM\Admin\Pages\SinglePages\Dashboard;


use DateInterval;
use DateTime;
use TLBM\Calendar\CalendarStatistics;
use TLBM\Calendar\Contracts\CalendarRepositoryInterface;

class BestSellingCalendarsTile extends DashboardTile
{

    /**
     * @var CalendarRepositoryInterface
     */
    private CalendarRepositoryInterface $calendarManager;

    public function __construct(CalendarRepositoryInterface $calendarManager)
    {
        $this->calendarManager = $calendarManager;

        parent::__construct(__("Top Calendars in last 30 days", TLBM_TEXT_DOMAIN));
    }

    public function displayBody(): void
    {
        $now = new DateTime();
        $now->sub(new DateInterval("P30D"));
        $bestselling = CalendarStatistics::GetBestSellingCalendars($now);
        arsort($bestselling);

        if (sizeof($bestselling) > 0) {
            echo "<ul class='tlbm-dashboard-tile-best-selling-list'>";
            $c = 0;
            foreach ($bestselling as $id => $num) {
                $cal = $this->calendarManager->getCalendar($id);
                echo "<li>";
                echo "<a href='" . get_edit_post_link($id) . "'>" . $cal->getTitle() . "</a><br>";
                echo $num . __(" Booking", TLBM_TEXT_DOMAIN);
                echo "</li>";
                $c++;

                if ($c == 5) {
                    break;
                }
            }
            echo "</ul>";
        } else {
            ?>
            <span class="tlbm-text-big-light"><?php
                _e("No bookings in the last 30 days", TLBM_TEXT_DOMAIN) ?></span>
            <?php
        }
    }
}