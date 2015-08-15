<?php
/*************************************************************************************/
/* Copyright Benjamin Perche                                                          */
/* email : bperche9@gmail.com                                                        */
/* web : http://benjaminperche.fr                                                    */
/*                                                                                   */
/* For the full copyright and license information, please view the LICENSE.txt       */
/* file that was distributed with this source code.                                  */
/*************************************************************************************/

namespace FullCalendar\Hook;

use FullCalendar\Smarty\Plugins\Calendar;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

/**
 * Class CalendarHook
 * @package FullCalendar\Hook
 * @author Benjamin Perche <bperche9@gmail.com>
 */
class CalendarHook extends BaseHook
{
    const FULLCALENDAR_CSS_PATH = "css/vendors/fullcalendar.min.css";
    const FULLCALENDAR_PRINT_CSS_PATH = "css/vendors/fullcalendar.print.css";

    const FULLCALENDAR_JS_PATH = "js/vendors/fullcalendar/fullcalendar.min.js";
    const FULLCALENDAR_I18N_PATH = "js/vendors/fullcalendar/lang/%d.js";

    const MOMENT_JS_PATH = "js/vendors/moment/moment.min.js";
    const MOMENT_I18N_PATH = "js/vendors/moment/locale/%d.js";

    private $smartyPlugin;

    public function __construct(Calendar $smartyPlugin)
    {
        $this->smartyPlugin = $smartyPlugin;
    }

    public function onMainStylesheet(HookRenderEvent $event)
    {
        $event->add($this->addCSS($this->getAssetPath(static::FULLCALENDAR_CSS_PATH)));

        $event->add($this->addCSS(
            $this->getAssetPath(static::FULLCALENDAR_PRINT_CSS_PATH),
            ["media" => "print"]
        ));
    }

    public function onMainJavascriptInitialization(HookRenderEvent $event)
    {
        // First add moment and fullCalendar assets
        $langCode = strtolower($this->getLang()->getCode());
        $momentI18n = sprintf($this->getAssetPath(static::MOMENT_I18N_PATH), $langCode);
        $fullcalendarI18n = sprintf($this->getAssetPath(static::FULLCALENDAR_I18N_PATH), $langCode);

        $event->add($this->addJs($this->getAssetPath(static::MOMENT_JS_PATH)));
        if (is_file($momentI18n)) {
            $event->add($this->addJS($momentI18n));
        }

        $event->add($this->addJs($this->getAssetPath(static::FULLCALENDAR_JS_PATH)));
        if (is_file($fullcalendarI18n)) {
            $event->add($this->addJS($momentI18n));
        }

        // Then declare all the calendars to the script
        $calendarScripts = "<script>(function($) {\n";

        foreach ($this->smartyPlugin->getCalendars() as $calendarId => $options) {
            $calendarScripts .= sprintf("    $(\"#%s\").fullCalendar(%s);\n", $calendarId, json_encode($options));
        }

        $calendarScripts .= "})(jQuery);</script>";
        $event->add($calendarScripts);
    }

    protected function getAssetPath($path)
    {
        return str_replace("/", DS, "assets/".$path);
    }
}
