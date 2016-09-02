<?php

namespace Nassau\Napi\Formatter;

class FormatterOptions
{
    /**
     * @var float
     */
    private $fps;

    /**
     * @var float
     */
    private $delay;

    /**
     * FormatterOptions constructor.
     * @param float $fps
     * @param float $delay
     */
    public function __construct($fps, $delay = 0.0)
    {
        $this->fps = $fps;
        $this->delay = $delay;
    }

    /**
     * @param float $fps
     * @return FormatterOptions
     */
    public function withFps($fps)
    {
        $instance = clone $this;
        $instance->fps = $fps;

        return $instance;
    }

    /**
     * @return float
     */
    public function getFps()
    {
        return $this->fps;
    }

    /**
     * @return float
     */
    public function getDelay()
    {
        return $this->delay;
    }

}