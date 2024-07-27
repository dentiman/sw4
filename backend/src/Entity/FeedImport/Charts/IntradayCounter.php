<?php

namespace App\Entity\FeedImport\Charts;

use Doctrine\ORM\Mapping as ORM;

/**
 * IntradayCounter
 *
 * @ORM\Table(name="feed_charts_intraday_counter")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 */
class IntradayCounter
{

    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=10)
     * @ORM\Id
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="exchange", type="integer")
     */
    private $exchange;

    /**
     * @var bool
     *
     * @ORM\Column(name="done", type="boolean")
     */
    private $done;

    /**
     * @var bool
     *
     * @ORM\Column(name="writen", type="boolean")
     */
    private $writen;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=255, nullable=true)
     */
    private $source;

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id
     *
     * @param string $id
     *
     * @return IntradayCounter
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set exchange
     *
     * @param integer $exchange
     *
     * @return IntradayCounter
     */
    public function setExchange($exchange)
    {
        $this->exchange = $exchange;

        return $this;
    }

    /**
     * Get exchange
     *
     * @return int
     */
    public function getExchange()
    {
        return $this->exchange;
    }

    /**
     * Set done
     *
     * @param boolean $done
     *
     * @return IntradayCounter
     */
    public function setDone($done)
    {
        $this->done = $done;

        return $this;
    }

    /**
     * Get done
     *
     * @return bool
     */
    public function getDone()
    {
        return $this->done;
    }

    /**
     * Set writen
     *
     * @param boolean $writen
     *
     * @return IntradayCounter
     */
    public function setWriten($writen)
    {
        $this->writen = $writen;

        return $this;
    }

    /**
     * Get writen
     *
     * @return bool
     */
    public function getWriten()
    {
        return $this->writen;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return IntradayCounter
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set source
     *
     * @param string $source
     *
     * @return IntradayCounter
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setUpdatedAt(new \DateTime('now'));

    }
}

