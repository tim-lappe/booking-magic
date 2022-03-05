<?php

namespace TLBM\Rules\Actions;

use Composer\Cache;
use InvalidArgumentException;
use Iterator;
use TLBM\MainFactory;
use TLBM\Repository\Contracts\CacheManagerInterface;
use TLBM\Repository\Query\Contracts\FullRuleActionQueryInterface;
use TLBM\Rules\Actions\Merging\Context\MergeContext;
use TLBM\Rules\Actions\Merging\Merger\Merger;
use TLBM\Rules\Contracts\RuleActionsManagerInterface;
use TLBM\Utilities\ExtendedDateTime;

class ActionsMerging
{
    /**
     * @var RuleActionsManagerInterface
     */
    private RuleActionsManagerInterface $ruleActionsManager;

    /**
     * @var CacheManagerInterface
     */
    private CacheManagerInterface $cacheManager;

    /**
     * @var ?MergeContext
     */
    private ?MergeContext $mergeContext = null;

    /**
     * @var ?ExtendedDateTime
     */
    private ?ExtendedDateTime $dateTime = null;

    /**
     * @var ?ExtendedDateTime
     */
    private ?ExtendedDateTime $fromDateTime = null;

    /**
     *
     * @var ?ExtendedDateTime
     */
    private ?ExtendedDateTime $toDateTime = null;

    /**
     * @var int[]
     */
    private array $calendarIds = array();

    /**
     * @param RuleActionsManagerInterface $ruleActionsManager
     * @param CacheManagerInterface $cacheManager
     */
    public function __construct(RuleActionsManagerInterface $ruleActionsManager, CacheManagerInterface $cacheManager)
    {
        $this->cacheManager = $cacheManager;
        $this->ruleActionsManager = $ruleActionsManager;
    }

    /**
     * @param array $calendarIds
     *
     * @return FullRuleActionQueryInterface
     */
    private function createRuleActionQuery(array $calendarIds): FullRuleActionQueryInterface
    {
        $query = MainFactory::create(FullRuleActionQueryInterface::class);
        $query->setCalendarIds($calendarIds);

        if($this->fromDateTime != null && $this->toDateTime != null) {
            $query->setDateTimeRange($this->fromDateTime, $this->toDateTime);
        } elseif ($this->dateTime != null) {
            $query->setDateTime($this->dateTime);
        } else {
            throw new InvalidArgumentException("dateTime or fromDatetime and toDatetime have to be defined");
        }

        return $query;
    }

    /**
     * @return ?array
     */
    private function getHashObj(): ?array
    {
        if($this->dateTime != null) {
            return [
                "name" => "mergedActionsForCalendar",
                "calendarIds" => $this->calendarIds,
                "mergeContext" => $this->mergeContext,
                "dateTime" => $this->dateTime->format()
            ];
        }
        if($this->toDateTime != null && $this->fromDateTime != null) {
             return [
                "name" => "mergedActionsForCalendar",
                "calendarIds" => $this->calendarIds,
                "mergeContext" => $this->mergeContext,
                "fromDateTime" => $this->fromDateTime->format(),
                "toDateTime" => $this->toDateTime->format()
            ];
        }

        return null;
    }

    /**
     * @return Iterator
     */
    public function getRuleActionsMerged(): Iterator
    {
        $summedUpMergeDataArr = [];
        $hashObj = $this->getHashObj();
        $cachedSum = $this->cacheManager->getData($hashObj);
        if($cachedSum != null) {
            $summedUpMergeDataArr = $cachedSum;
        } else {
            foreach ($this->calendarIds as $calendarId) {
                $query = $this->createRuleActionQuery([$calendarId]);
                foreach ($this->getTimedMergeDataForResult($query->getTimedRulesResult()) as $timedMergeData) {
                    $mergedActions         = $timedMergeData->getMergeResult();
                    $sumedUpTimedMergeData = $this->searchTimedMergeData($summedUpMergeDataArr, $timedMergeData->getDateTime());
                    if ( !$sumedUpTimedMergeData) {
                        $summedUpMergeDataArr[] = $timedMergeData;
                    } else {
                        foreach ($mergedActions as $term => $mergedActionResult) {
                            $merger = $timedMergeData->getSingleMerger($term);
                            if ($merger != null) {
                                if(isset( $sumedUpTimedMergeData->getMergeResult()[$term])) {
                                    $summedUpActionResult = $sumedUpTimedMergeData->getMergeResult()[$term];
                                    $summedUpActionResult->sumResults($mergedActionResult);
                                    $sumedUpTimedMergeData->setSingleMergeResult($term, $summedUpActionResult);
                                } else {
                                    $sumedUpTimedMergeData->setSingleMergeResult($term, $mergedActionResult);
                                }
                            }
                        }

                        foreach ($sumedUpTimedMergeData->getMergeResult() as $term => $mergedActionResult) {
                            $merger = $sumedUpTimedMergeData->getSingleMerger($term);
                            if($merger != null) {
                                $mergedActionResult = $merger->lastStepModification($term, $this->calendarIds, $mergedActionResult);
                                $sumedUpTimedMergeData->setSingleMergeResult($term, $mergedActionResult);
                            }
                        }
                    }
                }
            }

            $this->cacheManager->setData($hashObj, $summedUpMergeDataArr);
        }


        foreach ($summedUpMergeDataArr as $item) {
            yield $item;
        }
    }

    /**
     * @param TimedMergeData[] $timedMergeDataArr
     * @param ExtendedDateTime $dateTime
     *
     * @return ?TimedMergeData
     */
    private function searchTimedMergeData(array $timedMergeDataArr, ExtendedDateTime $dateTime): ?TimedMergeData {
        foreach ($timedMergeDataArr as $timedMergeData) {
            if($timedMergeData->getDateTime()->isEqualTo($dateTime)) {
                return $timedMergeData;
            }
        }

        return null;
    }

    private function getTimedMergeDataForResult(Iterator $timedRuleActions): Iterator
    {
        /**
         * @var TimedActions $timedRuleAction
         */
        foreach ($timedRuleActions as $timedRuleAction) {
            /**
             * @var Merger[] $actionMergeChains
             */
            $actionMergeChains = [];
            foreach ($timedRuleAction->getRuleActions() as $ruleAction) {
                $handler = $this->ruleActionsManager->getActionHandler($ruleAction);
                if ($handler) {
                    $mergeTerm  = $handler->getMergeTerm();
                    $nextMerger = $actionMergeChains[$mergeTerm] ?? null;
                    $merger     = $handler->getMerger($nextMerger);
                    $merger->setDateTimeContext($timedRuleAction->getDateTime());
                    $merger->setMergeContext($this->mergeContext);
                    $actionMergeChains[$mergeTerm] = $merger;
                }
            }

            $mergedActions = [];
            $usedMergers = [];
            foreach ($actionMergeChains as $term => $mergerChain) {
                $mergedActions[$term] = $mergerChain->merge();
                $usedMergers[$term] = $mergerChain;
            }

            yield new TimedMergeData($timedRuleAction->getDateTime(), $mergedActions, $usedMergers);
        }
    }

    /**
     * @return MergeContext|null
     */
    public function getMergeContext(): ?MergeContext
    {
        return $this->mergeContext;
    }

    /**
     * @param MergeContext|null $mergeContext
     */
    public function setMergeContext(?MergeContext $mergeContext): void
    {
        $this->mergeContext = $mergeContext;
    }

    /**
     * @return ExtendedDateTime|null
     */
    public function getDateTime(): ?ExtendedDateTime
    {
        return $this->dateTime;
    }

    /**
     * @param ExtendedDateTime|null $dateTime
     */
    public function setDateTime(?ExtendedDateTime $dateTime): void
    {
        $this->fromDateTime = null;
        $this->toDateTime = null;
        $this->dateTime = $dateTime;
    }

    /**
     * @return ExtendedDateTime|null
     */
    public function getFromDateTime(): ?ExtendedDateTime
    {
        return $this->fromDateTime;
    }

    /**
     * @return ExtendedDateTime|null
     */
    public function getToDateTime(): ?ExtendedDateTime
    {
        return $this->toDateTime;
    }

    /**
     * @param ExtendedDateTime|null $fromDateTime
     * @param ExtendedDateTime|null $toDateTime
     *
     * @return void
     */
    public function setDateTimeRange(?ExtendedDateTime $fromDateTime, ?ExtendedDateTime $toDateTime)
    {
        $this->fromDateTime = $fromDateTime;
        $this->toDateTime = $toDateTime;
        $this->dateTime = null;
    }

    /**
     * @return int[]
     */
    public function getCalendarIds(): array
    {
        return $this->calendarIds;
    }

    /**
     * @param int[] $calendarIds
     */
    public function setCalendarIds(array $calendarIds): void
    {
        $this->calendarIds = $calendarIds;
    }
}