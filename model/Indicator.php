<?php

/**
 * Created by PhpStorm.
 * User: ніна
 * Date: 02.02.2017
 * Time: 10:46
 */
class Indicator
{
    /** показник пацієнта */
    protected $parameter;
    /** вказує, чи задовільняє показник норму */
    public $index;
    /** норма увигляді рядка "н в" */
    protected $norm;
    /** нижня межа норми */
    protected $lowerLimit;

    protected $upperLimit;


    /**
     * Indicator constructor.
     * @param $parameter
     * @param $norm
     */
    public function __construct($parameter,$norm)
    {
        $this->parameter = $parameter;
        $this->norm = $norm;
        $this->parseNorm();
        $this->checkParameter();
    }


    /**
     * @return mixed
     */
    public function getNorm()
    {
        return $this->norm;
    }

    /**
     * @param mixed $norm
     */
    public function setNorm($norm)
    {
        $this->norm = $norm;
    }

    /**
     * @return mixed
     */
    public function getParameter()
    {
        return $this->parameter;
    }

    /**
     * @param mixed $parameter
     */
    public function setParameter($parameter)
    {
        $this->parameter = $parameter;
    }

    /**
     * @return mixed
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param mixed $index
     */
    public function setIndex($index)
    {
        $this->index = $index;
    }

    /**
     * @return mixed
     */
    public function getUpperLimit()
    {
        return $this->upperLimit;
    }

    /**
     * @param mixed $upperLimit
     */
    public function setUpperLimit($upperLimit)
    {
        $this->upperLimit = $upperLimit;
    }

    /**
     * @return mixed
     */
    public function getLowerLimit()
    {
        return $this->lowerLimit;
    }

    /**
     * @param mixed $lowerLimit
     */
    public function setLowerLimit($lowerLimit)
    {
        $this->lowerLimit = $lowerLimit;
    }


    public function parseNorm()
    {
        list($this->lowerLimit,$this->upperLimit) = explode(" ",$this->norm);
    }

    public function checkParameter()
    {
        if($this->parameter > $this->lowerLimit && $this->parameter < $this->upperLimit)
        {
            $this->index = true;
        }
        else
        {
            $this->index = false;
        }
    }

}

$indicator = new Indicator(14,"1 10");
echo "lower: ".$indicator->getLowerLimit()."<br>";
echo "upper: ".$indicator->getUpperLimit()."<br>";
$indicator->checkParameter();
echo "parameters index is ".$indicator->getIndex()."<br>";
var_dump($indicator->index);
