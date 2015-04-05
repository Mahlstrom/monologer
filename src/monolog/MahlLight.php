<?php namespace mahlstrom\monolog;
use Bramus\Ansi\ControlSequences\EscapeSequences\Enums\SGR;
use Bramus\Monolog\Formatter\ColorSchemes\ColorSchemeInterface;
use Bramus\Monolog\Formatter\ColorSchemes\ColorSchemeTrait;
use Monolog\Logger;

/**
 * Created by PhpStorm.
 * User: mahlstrom
 * Date: 05/04/15
 * Time: 11:31
 */

class MahlLight implements ColorSchemeInterface
{
    /**
     * Use the ColorScheme and alias its constructor
     */
    use ColorSchemeTrait {
        ColorSchemeTrait::__construct as private __constructTrait;
    }

    /**
     * [__construct description]
     */
    public function __construct()
    {
        // Call Trait Constructor, so that we have $this->ansi available
        $this->__constructTrait();

        // Our Color Scheme
        $this->setColorizeArray(array(
            Logger::DEBUG => $this->ansi->sgr(array(SGR::COLOR_FG_GREEN, SGR::STYLE_INTENSITY_FAINT))->get(),
            Logger::INFO => $this->ansi->sgr(array(SGR::COLOR_FG_GREEN_BRIGHT, SGR::STYLE_INTENSITY_NORMAL))->get(),
            Logger::NOTICE => $this->ansi->sgr(array(SGR::COLOR_FG_CYAN, SGR::STYLE_INTENSITY_BRIGHT))->get(),
            Logger::WARNING => $this->ansi->sgr(array(SGR::COLOR_FG_YELLOW, SGR::STYLE_INTENSITY_FAINT))->get(),
            Logger::ERROR => $this->ansi->sgr(array(SGR::COLOR_FG_YELLOW_BRIGHT, SGR::STYLE_INTENSITY_NORMAL))->get(),
            Logger::CRITICAL => $this->ansi->sgr(array(SGR::COLOR_FG_PURPLE, SGR::STYLE_INTENSITY_NORMAL))->get(),
            Logger::ALERT => $this->ansi->sgr(array(SGR::COLOR_FG_RED, SGR::STYLE_INTENSITY_BRIGHT))->get(),
            Logger::EMERGENCY => $this->ansi->sgr(array(SGR::COLOR_FG_RED_BRIGHT, SGR::STYLE_INTENSITY_BRIGHT, SGR::STYLE_BLINK))->get(),
        ));
    }
}
