<?php
namespace Bss\HyvaCustomize\Plugin;

class Currency
{
    public function afterFormatPrecision(
        $subject,
        $result,
        $price,
        $precision,
        $options = [],
        $includeContainer = true,
        $addBrackets = false
    ) {
        if (!isset($options['precision'])) {
            $options['precision'] = $precision;
        }
        if ($includeContainer) {
            return '<span class="price font-normal">' . ($addBrackets ? '[' : '') . $subject->formatTxt(
                    $price,
                    $options
                ) . ($addBrackets ? ']' : '') . '</span>';
        }
        return $result;
    }
}