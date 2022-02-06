<?php


namespace TLBM\Admin\Pages\SinglePages\Dashboard;


use DateInterval;
use DateTime;
use TLBM\Calendar\CalendarStatistics;
use TLBM\Entity\Calendar;
use TLBM\Repository\Contracts\EntityRepositoryInterface;

class BestSellingCalendarsTile extends DashboardTile
{

    /**
     * @var EntityRepositoryInterface
     */
    private EntityRepositoryInterface $entityRepository;

    public function __construct(EntityRepositoryInterface $entityRepository)
    {
        $this->entityRepository = $entityRepository;

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
                $cal = $this->entityRepository->getEntity(Calendar::class, $id);
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