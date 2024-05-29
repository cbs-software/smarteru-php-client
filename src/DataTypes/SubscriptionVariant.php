<?php

/**
 * Contains CBS\SmarterU\DataTypes\SubscriptionVariant
 *
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 */

declare(strict_types=1);

namespace CBS\SmarterU\DataTypes;

/**
 * A SubscriptionVariant represents the subscriptions that are assigned to
 * a group.
 */
class SubscriptionVariant {
    /**
     * The system-generated identifier of the subscription variant that is
     * assigned to the group. This is the VariantID returned by the
     * listVariants method.
     */
    protected string $id;

    /**
     * Specifies if the variant is to be assigned to or remvoed from the group.
     * Acceptable values are 'Add' or 'Remove'. Only necessary when making an
     * updateGroup request to add or remove the subscription variant.
     */
    protected ?string $action = null;

    /**
     * Specifies whether enrollments in the subscription require credits.
     *
     * True: Subscription enrollments will require credits.
     * False: Subscription enrollments will not require credits.
     */
    protected bool $requiresCredits;

    /**
     * Get the system-generated identifier for the subscription variant.
     *
     * @return string The system-generated identifier of the subscription
     */
    public function getId(): string {
        return $this->id;
    }

    /**
     * Set the system-generated identifier for the subscription variant.
     *
     * @param string $Id The system-generated identifier of the subscription
     * @return self
     */
    public function setId(string $id): self {
        $this->id = $id;
        return $this;
    }

    /**
     * Get whether the variant is to be assigned to or removed from the group.
     *
     * @return ?string whether the variant is to be assigned or removed
     */
    public function getAction(): ?string {
        return $this->action;
    }

    /**
     * Set whether the variant is to be assigned to or removed from the group.
     *
     * @param string $action 'Add' to add the variant to the
     *      group, 'Remove' to remove it.
     * @return self
     */
    public function setAction(string $action): self {
        $this->action = $action;
        return $this;
    }

    /**
     * Get whether enrollments in the subscription require credits.
     *
     * @return bool True if and only if the subscription requires credits.
     */
    public function getRequiresCredits(): bool {
        return $this->requiresCredits;
    }

    /**
     * Set whether enrollments in the subscription require credits.
     *
     * @param bool $requiresCredits True if and only if the subscription
     *      requires credits.
     * @return self
     */
    public function setRequiresCredits(bool $requiresCredits): self {
        $this->requiresCredits = $requiresCredits;
        return $this;
    }
}
