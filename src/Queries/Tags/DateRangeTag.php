<?php

/**
 * Contains CBS\SmarterU\Queries\Tags\DateRangeTag
 *
 * @author      CORE Software Team
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/07/13
 */

declare(strict_types=1);

namespace CBS\SmarterU\Queries\Tags;

use DateTimeInterface;

/**
 * This class represents a range of dates to pass into a query.
 */
class DateRangeTag {
    /**
     * The first date to include in the DateRange filter.
     */
    protected DateTimeInterface $dateFrom;

    /**
     * The last date to include in the DateRange filter.
     */
    protected DateTimeInterface $dateTo;

    /**
     * Return the first date to include in the DateRange filter.
     *
     * @return DateTimeInterface the first date to include in the DateRange filter.
     */
    public function getDateFrom(): DateTimeInterface {
        return $this->dateFrom;
    }

    /**
     * Set the first date to include in the DateRange filter.
     *
     * @param DateTimeInterface $dateFrom the first date to include in the DateRange filter
     * @return self
     */
    public function setDateFrom(DateTimeInterface $dateFrom): self {
        $this->dateFrom = $dateFrom;
        return $this;
    }

    /**
     * Return the last date to include in the DateRange filter.
     *
     * @return DateTimeInterface the last date to include in the DateRange filter.
     */
    public function getDateTo(): DateTimeInterface {
        return $this->dateTo;
    }

    /**
     * Set the last date to include in the DateRange filter.
     *
     * @param DateTimeInterface $dateTo the last date to include in the DateRange filter
     * @return self
     */
    public function setDateTo(DateTimeInterface $dateTo): self {
        $this->dateTo = $dateTo;
        return $this;
    }
}
