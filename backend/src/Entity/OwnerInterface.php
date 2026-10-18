<?php
/**
 * Created by PhpStorm.
 * User: dentiman
 * Date: 2020-01-05
 * Time: 19:26
 */

namespace App\Entity;


interface OwnerInterface
{
    public function getOwner(): ?User;
    public function setOwner(?User $owner): void;

}
