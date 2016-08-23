<?php

namespace Nassau\Napi\Fps;

use Symfony\Component\Process\ProcessBuilder;

class FFmpegFpsReader implements FpsReader
{

    /**
     * @var ProcessBuilder
     */
    private $builder;

    /**
     * FFmpegFpsReader constructor.
     * @param ProcessBuilder $builder
     */
    public function __construct(ProcessBuilder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param \SplFileInfo $file
     * @return float|null
     */
    public function getFps(\SplFileInfo $file)
    {

        $arguments = ['-v', '0', '-select_streams', 'v', '-print_format', 'json', '-show_entries', 'stream=r_frame_rate'];
        $process = $this->builder->create($arguments)->add($file->getRealPath())->setPrefix('ffprobe')->getProcess();

        $exitCode = $process->run();

        if (0 !== $exitCode) {
            return null;
        }

        $output = json_decode($process->getOutput(), true);

        if (false === isset($output['streams'][0]['r_frame_rate'])) {
            return null;
        }

        list ($alpha, $bravo) = array_pad(explode('/', $output['streams'][0]['r_frame_rate']), 2, null);

        if (0 === (int)$bravo) {
            throw new \RuntimeException('Invalid FPS received from ffprobe: ' . $output['streams'][0]['r_frame_rate']);
        }

        return $alpha / $bravo ;
    }
}