<?php

namespace Nassau\Napi\Formatter;

class TxtToSrtFormatter implements Formatter
{
    const RE = '/^[{\[](?<from>\d+)[}\]][{\[](?<to>\d+)[}\]](?:[{\[]C:\$(?<color>[0-9a-f]{6})[}\]])?(?<text>.+)$/m';

    public function reformat($source, FormatterOptions $options)
    {
        if (!$source || '1' === substr($source, 0, 1)) {
            return $source;
        }

        if ($source{0} === '[') {
            $options = $options->withFps(10);
        }

        preg_match_all(self::RE, $source, $source, PREG_SET_ORDER);

        return implode("\n", array_map(function ($num, $data) use ($options) {
            return $this->formatItem($data + ['num' => $num], $options);
        }, range(1, sizeof($source)), $source));
    }


    private function formatItem($data, FormatterOptions $options)
    {
        return vsprintf("%d\n%s --> %s\n%s\n", [
            $data['num'],
            $this->formatDate($data['from'], $options),
            $this->formatDate($data['to'], $options),
            $this->formatText($data['text'], $data['color']),
        ]);
    }

    private function formatDate($value, FormatterOptions $options)
    {
        $value /= $options->getFps();
        $value += $options->getDelay();

        return sprintf('%s,%03d', gmdate('H:i:s', $value), ($value - ($value >> 0)) * 1000);
    }

    private function formatText($text, $color)
    {
        $emphasize = function ($s) {
            return 0 === strpos($s, '/') ? sprintf('<i>%s</i>', substr($s, 1)) : $s;
        };

        $text = implode("\n", array_map($emphasize, explode('|', trim($text))));

        if ($color) {
            $text = sprintf('<'. 'font color="#%s">%s</font>', $color, $text);
        }

        return $text;
    }
}