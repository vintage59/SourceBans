<?php

namespace SourceBans\CoreBundle\Twig\Extension;

class FormatExtension extends \Twig_Extension
{
    /**
     * @inheritdoc
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('formatLength', [$this, 'formatLength']),
            new \Twig_SimpleFilter('formatSize', [$this, 'formatSize']),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'format';
    }

    /**
     * Format seconds as "1mo 2wk 3d 4hr 5min 6sec" or "1132:05:06"
     *
     * @param integer $secs
     * @param boolean $textual Whether to output as "1mo 2wk 3d 4hr 5min 6sec" or "1132:05:06"
     * @return string
     */
    public function formatLength($secs, $textual = true)
    {
        if (! $textual) {
            $hours = (int)($secs / 60 / 60);
            $secs -= $hours * 60 * 60;
            $mins = (int)($secs / 60);
            $secs %= 60;

            return $hours . ':' . $mins . ':' . $secs;
        }

        $ret = '';
        $units = [
            'mo'  => 60 * 60 * 24 * 30,
            'wk'  => 60 * 60 * 24 * 7,
            'd'   => 60 * 60 * 24,
            'hr'  => 60 * 60,
            'min' => 60,
            'sec' => 1,
        ];

        foreach ($units as $name => $div) {
            if (!($value = (int)($secs / $div))) {
                continue;
            }

            $ret .= ', ' . $value . ' ' . $name;
            $secs %= $div;
        }

        return substr($ret, 2);
    }

    /**
     * Format size in English units
     *
     * @param integer $size The size in bytes
     * @param integer $precision The optional number of decimal digits to round to
     * @return string
     */
    public function formatSize($size, $precision = 2)
    {
        $sizes = ['bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        for ($i = 0; $size > 1024 && $i < count($sizes) - 1; $i++) {
            $size /= 1024;
        }
        return round($size, $precision) . ' ' . $sizes[$i];
    }
}
