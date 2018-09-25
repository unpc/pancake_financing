<?php

namespace Gini\Model;

use \Gini\Model\Widget;

class Help
{
    public static function pagination(& $objects, $start, $per_page, $url=null, $params = [])
    {
        unset($params['st']);

        $totalCount = $objects->totalCount();

        $start = $start - ($start % $per_page);

        if ($start > 0) {
            $last = floor($totalCount / $per_page) * $per_page;
            if ($last == $totalCount) {
                $last = max(0, $last - $per_page);
            }
            if ($start > $last) {
                $start = $last;
            }
        }

        $objects = $objects->limit($start, $per_page);

        $pagination = Widget::factory('pagination', [
                'start' => $start,
                'per_page' => $per_page,
                'total' => $totalCount,
                'url' => $url,
                'params' => $params
            ]);

        return $pagination;
    }

    public static function links($links)
    {
        return Widget::factory('links', ['items' => $links]);
    }

    public static function jsQuote($str, $quote='"')
    {
        if (is_scalar($str)) {
            if (is_numeric($str)) {
                return $str;
            } elseif (is_bool($str)) {
                return $str ? true : false;
            } elseif (is_null($str)) {
                return 'null';
            } else {
                return $quote.self::escape($str).$quote;
            }
        } else {
            return @json_encode($str);
        }
    }

    public static function escape($str)
    {
        return addcslashes($str, "\\\'\"&\n\r<>");
    }

    public static function mergeArrayText($arr1=[], $arr2=[])
    {
        $new = [];
        foreach ($arr1 as $key => $value) {
            if ($arr2[$value]) {
                $new[] = $arr2[$value];
            }
        }
        return join(', ', $new);
    }

    public static function cutHtml($html='')
    {
        $b = [
            "/<script.*>(.*)<\/script>/siU",
            '/on(click|dblclick|mousedown|mouseup|mouseover|mousemove|mouseout|keypress|keydown|keyup)="[^"]*"/i',
            '/on(abort|beforeunload|error|load|move|resize|scroll|stop|unload)="[^"]*"/i', 
            '/on(blur|change|focus|reset|submit)="[^"]*"/i', 
            '/on(bounce|finish|start)="[^"]*"/i',
            '/on(beforecopy|beforecut|beforeeditfocus|beforepaste|beforeupdate|contextmenu|cut)="[^"]*"/i',
            '/on(drag|dragdrop|dragend|dragenter|dragleave|dragover|dragstart|drop|losecapture|paste|select|selectstart)="[^"]*"/i',
            '/on(afterupdate|cellchange|dataavailable|datasetchanged|datasetcomplete|errorupdate|rowenter|rowexit|rowsdelete|rowsinserted)="[^"]*"/i',
            '/on(afterprint|beforeprint|filterchange|help|propertychange|readystatechange)="[^"]*"/i',
            '/javascript\:.*(\;|")/'
        ];
        $c = ['','','','','','','','','','','',''];
        return preg_replace($b, $c, $html);
    }
}
