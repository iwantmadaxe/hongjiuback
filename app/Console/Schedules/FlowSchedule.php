<?php

/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Console\Schedules;

use App\Models\Card;
use App\Models\Flow;
use Illuminate\Support\Collection;
use App\Services\TelecomApi\Core\DesUtils;
use App\Services\TelecomApi\Flow as FlowApi;
use App\Services\TelecomApi\Core\AbstractApi;
use App\Repositories\TelecomStatusRepository;
use App\Services\TelecomCard\TelecomCardManager;

/**
 * Class FlowSchedule
 * @package App\Console\Schedules
 */
abstract class FlowSchedule extends AbstractApi
{
    /**
     * @var \App\Services\TelecomApi\Flow
     */
    protected $flowApi;

    /**
     * @var Flow
     */
    protected $flowModel;

    /**
     * @var TelecomCardManager
     */
    protected $cardManager;

    protected $level;

    const LEVEL_REGULAR = 0;
    const LEVEL_MINUTE = 1;
    const LEVEL_HOUR = 2;


    /**
     * FlowSchedule constructor.
     * @param Flow $flow
     * @param TelecomCardManager $cardManager
     * @param \App\Services\TelecomApi\Flow $flowApi
     * @param DesUtils $desUtils
     * @param TelecomStatusRepository $telecomStatusRepository
     */
    public function __construct(Flow $flow, TelecomCardManager $cardManager,
                                FlowApi $flowApi, DesUtils $desUtils,
                                TelecomStatusRepository $telecomStatusRepository)
    {
        parent::__construct($desUtils, $telecomStatusRepository);
        $this->flowApi = $flowApi;
        $this->flowModel = $flow;
        $this->cardManager = $cardManager;
        $cards = $this->getCards();
        $this->handler($cards);
    }

    /**
     * @param Collection $cards
     * @return mixed
     */
    abstract function handler(Collection $cards);

    /**
     * 获取某个等级的card集合
     *
     * @return mixed
     */
    public function getCards()
    {
        $level = $this->level;
        if ($level) {
            $cards = Card::with(['flow'])->whereHas('flow', function ($query) use ($level) {
                $query->where('flow.level', '=', $level);
            })->where('is_forbidden', '!=', 1)->where('status', 1)->get();
        } else {
            $cards = Card::with('flow')->where(function ($query) {
                $query->doesntHave('flow')->orWhereHas('flow', function ($query) {
                    $query->where('flow.level', '=', 0);
                });
            })->where('is_forbidden', '!=', 1)->where('status', 1)->get();
        }

        return $cards;
    }
}