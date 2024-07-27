<?php

namespace App\Entity\Feed\Traits;

use Doctrine\ORM\Mapping as ORM;


trait MainWeekTrait
{

    /**
     * @var float
     *
     * @ORM\Column(name="cl1", type="float", nullable=true)
     */
    private $cl1;

    /**
     * @var float
     *
     * @ORM\Column(name="op1", type="float", nullable=true)
     */
    private $op1;

    /**
     * @var float
     *
     * @ORM\Column(name="hi1", type="float", nullable=true)
     */
    private $hi1;

    /**
     * @var float
     *
     * @ORM\Column(name="lo1", type="float", nullable=true)
     */
    private $lo1;

    /**
     * @var int
     *
     * @ORM\Column(name="vol1", type="integer", nullable=true)
     */
    private $vol1;

    /**
     * @var float
     *
     * @ORM\Column(name="cl2", type="float", nullable=true)
     */
    private $cl2;

    /**
     * @var float
     *
     * @ORM\Column(name="op2", type="float", nullable=true)
     */
    private $op2;

    /**
     * @var float
     *
     * @ORM\Column(name="hi2", type="float", nullable=true)
     */
    private $hi2;

    /**
     * @var float
     *
     * @ORM\Column(name="lo2", type="float", nullable=true)
     */
    private $lo2;

    /**
     * @var int
     *
     * @ORM\Column(name="vol2", type="integer", nullable=true)
     */
    private $vol2;

    /**
     * @var float
     *
     * @ORM\Column(name="cl3", type="float", nullable=true)
     */
    private $cl3;

    /**
     * @var float
     *
     * @ORM\Column(name="op3", type="float", nullable=true)
     */
    private $op3;

    /**
     * @var float
     *
     * @ORM\Column(name="hi3", type="float", nullable=true)
     */
    private $hi3;

    /**
     * @var float
     *
     * @ORM\Column(name="lo3", type="float", nullable=true)
     */
    private $lo3;

    /**
     * @var int
     *
     * @ORM\Column(name="vol3", type="integer", nullable=true)
     */
    private $vol3;

    /**
     * @var float
     *
     * @ORM\Column(name="cl4", type="float", nullable=true)
     */
    private $cl4;

    /**
     * @var float
     *
     * @ORM\Column(name="op4", type="float", nullable=true)
     */
    private $op4;

    /**
     * @var float
     *
     * @ORM\Column(name="hi4", type="float", nullable=true)
     */
    private $hi4;

    /**
     * @var float
     *
     * @ORM\Column(name="lo4", type="float", nullable=true)
     */
    private $lo4;

    /**
     * @var int
     *
     * @ORM\Column(name="vol4", type="integer", nullable=true)
     */
    private $vol4;




    public function setCl1($cl1)
    {
        $this->cl1 = $cl1;

        return $this;
    }

    /**
     * Get cl1
     *
     * @return float
     */
    public function getCl1()
    {
        return $this->cl1;
    }


    public function setOp1($op1)
    {
        $this->op1 = $op1;

        return $this;
    }

    /**
     * Get op1
     *
     * @return float
     */
    public function getOp1()
    {
        return $this->op1;
    }


    public function setHi1($hi1)
    {
        $this->hi1 = $hi1;

        return $this;
    }

    /**
     * Get hi1
     *
     * @return float
     */
    public function getHi1()
    {
        return $this->hi1;
    }


    public function setLo1($lo1)
    {
        $this->lo1 = $lo1;

        return $this;
    }

    /**
     * Get lo1
     *
     * @return float
     */
    public function getLo1()
    {
        return $this->lo1;
    }


    public function setVol1($vol1)
    {
        $this->vol1 = $vol1;

        return $this;
    }

    /**
     * Get vol1
     *
     * @return int
     */
    public function getVol1()
    {
        return $this->vol1;
    }


    public function setCl2($cl2)
    {
        $this->cl2 = $cl2;

        return $this;
    }

    /**
     * Get cl2
     *
     * @return float
     */
    public function getCl2()
    {
        return $this->cl2;
    }

    public function setOp2($op2)
    {
        $this->op2 = $op2;

        return $this;
    }

    /**
     * Get op2
     *
     * @return float
     */
    public function getOp2()
    {
        return $this->op2;
    }


    public function setHi2($hi2)
    {
        $this->hi2 = $hi2;

        return $this;
    }

    /**
     * Get hi2
     *
     * @return float
     */
    public function getHi2()
    {
        return $this->hi2;
    }

    public function setLo2($lo2)
    {
        $this->lo2 = $lo2;

        return $this;
    }

    /**
     * Get lo2
     *
     * @return float
     */
    public function getLo2()
    {
        return $this->lo2;
    }


    public function setVol2($vol2)
    {
        $this->vol2 = $vol2;

        return $this;
    }


    public function getVol2()
    {
        return $this->vol2;
    }


    public function setCl3($cl3)
    {
        $this->cl3 = $cl3;

        return $this;
    }

    /**
     * Get cl3
     *
     * @return float
     */
    public function getCl3()
    {
        return $this->cl3;
    }


    public function setOp3($op3)
    {
        $this->op3 = $op3;

        return $this;
    }

    /**
     * Get op3
     *
     * @return float
     */
    public function getOp3()
    {
        return $this->op3;
    }


    public function setHi3($hi3)
    {
        $this->hi3 = $hi3;

        return $this;
    }

    /**
     * Get hi3
     *
     * @return float
     */
    public function getHi3()
    {
        return $this->hi3;
    }

    public function setLo3($lo3)
    {
        $this->lo3 = $lo3;

        return $this;
    }

    /**
     * Get lo3
     *
     * @return float
     */
    public function getLo3()
    {
        return $this->lo3;
    }


    public function setVol3($vol3)
    {
        $this->vol3 = $vol3;

        return $this;
    }

    /**
     * Get vol3
     *
     * @return int
     */
    public function getVol3()
    {
        return $this->vol3;
    }

    public function setCl4($cl4)
    {
        $this->cl4 = $cl4;

        return $this;
    }

    /**
     * Get cl4
     *
     * @return float
     */
    public function getCl4()
    {
        return $this->cl4;
    }


    public function setOp4($op4)
    {
        $this->op4 = $op4;

        return $this;
    }

    /**
     * Get op4
     *
     * @return float
     */
    public function getOp4()
    {
        return $this->op4;
    }


    public function setHi4($hi4)
    {
        $this->hi4 = $hi4;

        return $this;
    }

    /**
     * Get hi4
     *
     * @return float
     */
    public function getHi4()
    {
        return $this->hi4;
    }


    public function setLo4($lo4)
    {
        $this->lo4 = $lo4;

        return $this;
    }

    /**
     * Get lo4
     *
     * @return float
     */
    public function getLo4()
    {
        return $this->lo4;
    }


    public function setVol4($vol4)
    {
        $this->vol4 = $vol4;

        return $this;
    }

    /**
     * Get vol4
     *
     * @return int
     */
    public function getVol4()
    {
        return $this->vol4;
    }
}

