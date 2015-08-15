<?php
/*************************************************************************************/
/* Copyright Benjamin Perche                                                          */
/* email : bperche9@gmail.com                                                        */
/* web : http://benjaminperche.fr                                                    */
/*                                                                                   */
/* For the full copyright and license information, please view the LICENSE.txt       */
/* file that was distributed with this source code.                                  */
/*************************************************************************************/

namespace FullCalendar\Smarty\Plugins;

use FullCalendar\FullCalendar;
use FullCalendar\Model\Config\FullCalendarConfigValue;
use TheliaSmarty\Template\AbstractSmartyPlugin;
use TheliaSmarty\Template\Exception\SmartyPluginException;
use TheliaSmarty\Template\SmartyPluginDescriptor;

/**
 * Class Calendar
 * @package FullCalendar\Smarty\Plugins
 * @author Benjamin Perche <bperche9@gmail.com>
 */
class Calendar extends AbstractSmartyPlugin
{
    const DEFAULT_CLASS = "thelia-fullcalendar";
    const DEFAULT_ID = "fullcalendar%d";
    const CALENDAR_TAG_TEMPLATE = "<div id=\"%s\" class=\"%s\" %s></div>";

    private $currentCallWithoutId = 0;
    private $calendars = [];

    public function renderCalendar($params)
    {
        $id = $this->getParam($params, "id");
        $classes = $this->getParam($params, "class");
        $calendarOptions = $this->getParam($params, "options", []);
        $attributes = $this->getParam($params, "attr", []);

        if (!is_array($calendarOptions)) {
            throw new SmartyPluginException(
                sprintf("The 'calendar' smarty function 'options' argument must be an array")
            );
        }

        if (!is_array($attributes)) {
            throw new SmartyPluginException(
                sprintf("The 'calendar' smarty function 'attr' argument must be an array")
            );
        }

        if (null === $id) {
            $id = sprintf(static::DEFAULT_ID, $this->currentCallWithoutId++);
        }

        if (!is_array($classes)) {
            $classes = explode(" ", $classes);
        }

        if (!in_array(static::DEFAULT_CLASS, $classes)) {
            $classes[] = static::DEFAULT_CLASS;
        }

        if (FullCalendar::getConfigValue(FullCalendarConfigValue::ENABLE_PREVENT_SCROLL)) {
            $calendarOptions["handleWindowResize"] = false;
        }

        $this->calendars[$id] = $calendarOptions;
        $tag = sprintf(static::CALENDAR_TAG_TEMPLATE, $id, implode($classes), $this->formatAttributes($attributes));

        return $tag;
    }

    /**
     * @return array
     */
    public function getCalendars()
    {
        return $this->calendars;
    }

    public function getDefaultClass()
    {
        return static::DEFAULT_CLASS;
    }

    public function formatAttributes(array $attributes)
    {
        $formattedAttributes = "";

        foreach ($attributes as $name => $value) {
            if (! is_scalar($value)) {
                throw new SmartyPluginException(sprintf(
                    "The 'calendar' smarty function 'attr' argument must only contain scalar values"
                ));
            }

            $formattedAttributes .= sprintf(" %s=\"%s\"", $name, $value);
        }

        return $formattedAttributes;
    }

    /**
     * @return SmartyPluginDescriptor[] an array of SmartyPluginDescriptor
     */
    public function getPluginDescriptors()
    {
        return [
            new SmartyPluginDescriptor("function", "calendar", $this, "renderCalendar"),
        ];
    }
}
