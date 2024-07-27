<?php
/**
 * Created by PhpStorm.
 * User: dentiman
 * Date: 2019-11-23
 * Time: 20:46
 */

namespace App\Model\Feed;


interface TickerTaskInterface
{

    public function getId(): string;


    public function setId(?string $id): void;

    /**
     * @return bool
     */
    public function isDone(): bool;

    /**
     * @param bool $done
     */
    public function markDone(): void;

    /**
     * @return bool
     */
    public function isSuccess(): bool;

    /**
     * @param bool $success
     */
    public function markSuccess(): void;

    /**
     * @return string
     */
    public function getMessage(): ?string;

    /**
     * @param string $message
     */
    public function setMessage(?string $message): void;

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime;

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt): void;

    public function addAttempt(): void;

    public function getAttempt(): int;

}
