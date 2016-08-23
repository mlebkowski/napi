<?php

namespace Nassau\Napi\Formatter;

class TxtToSrtFormatter implements Formatter
{
    const RE = '/^[{\[](?<from>\d+)[}\]][{\[](?<to>\d+)[}\]](?:[{\[]C:\$(?<color>[0-9a-f]{6})[}\]])?(?<text>.+)$/m';

    /**
     * @param string $source
     * @param float $fps
     * @return string
     */
    public function reformat($source, $fps)
    {
        if (!$source || '1' === substr($source, 0, 1)) {
            return $source;
        }

        if ($source{0} === '[') {
            $fps = 10;
        }

        preg_match_all(self::RE, $source, $source, PREG_SET_ORDER);

        return implode("\n", array_map(function ($num, $data) use ($fps) {
            return $this->formatItem($data + ['num' => $num], $fps);
        }, range(1, sizeof($source)), $source));
    }


    private function formatItem($data, $fps)
    {
        return vsprintf("%d\n%s --> %s\n%s\n", [
            $data['num'],
            $this->formatDate($data['from'], $fps),
            $this->formatDate($data['to'], $fps),
            $this->formatText($data['text'], $data['color']),
        ]);
    }

    private function formatDate($value, $fps)
    {
        $value /= $fps;

        return sprintf('%s,%03d', gmdate('H:i:s', $value), ($value - ($value >> 0)) * 1000);
    }

    private function formatText($text, $color)
    {
        $emphasize = function ($s) {
            return '/' === $s{0} ? sprintf('<i>%s</i>', substr($s, 1)) : $s;
        };

        $text = implode("\n", array_map($emphasize, explode('|', trim($text))));

        if ($color) {
            $text = sprintf('<'. 'font color="#%s">%s</font>', $color, $text);
        }

        return $text;
    }
}