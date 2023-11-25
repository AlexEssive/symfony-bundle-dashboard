<?php

/**
 * This file is part of a Spipu Bundle
 *
 * (c) Laurent Minguet
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spipu\DashboardBundle\Service\Ui\Source\DataProvider;

use DateInterval;
use DateTimeInterface;
use Spipu\DashboardBundle\Entity\Source\Source as SourceDefinition;
use Spipu\DashboardBundle\Service\PeriodService;
use Spipu\DashboardBundle\Service\Ui\WidgetRequest;

abstract class AbstractDataProvider implements DataProviderInterface
{
    /**
     * @var WidgetRequest
     */
    protected WidgetRequest $request;

    /**
     * @var SourceDefinition
     */
    protected SourceDefinition $definition;

    /**
     * @var array|null
     */
    private ?array $filters = null;

    /**
     * @return WidgetRequest
     */
    public function getRequest(): WidgetRequest
    {
        return $this->request;
    }

    /**
     * @param WidgetRequest $request
     * @return void
     */
    public function setSourceRequest(WidgetRequest $request): void
    {
        $this->request = $request;
    }

    /**
     * @return SourceDefinition
     */
    public function getDefinition(): SourceDefinition
    {
        return $this->definition;
    }

    /**
     * @param SourceDefinition $definition
     * @return void
     */
    public function setSourceDefinition(SourceDefinition $definition): void
    {
        $this->definition = $definition;
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        if (is_array($this->filters)) {
            return $this->filters;
        }

        return $this->request->getFilters();
    }

    /**
     * @return DateTimeInterface[]
     */
    protected function getPreviousPeriodDate(): array
    {
        $period = $this->request->getPeriod();
        $currentFrom = $period->getDateFrom();
        $currentTo = $period->getDateTo();

        if ($period->getType() === PeriodService::PERIOD_DAY_CURRENT) {
            $dateFrom = (clone $currentFrom)->sub(new DateInterval('P1D'));
            $dateTo   = (clone $dateFrom)->add($currentFrom->diff($currentTo));
            return [$dateFrom, $dateTo];
        }

        $dateFrom = (clone $currentFrom)->add($currentTo->diff($currentFrom));
        $dateTo   = (clone $currentFrom);
        return [$dateFrom, $dateTo];
    }

    /**
     * @return array
     */
    public function getSpecificValues(): array
    {
        return [];
    }
}
