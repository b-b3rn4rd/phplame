<?php

namespace Lame\Settings\Encoding;

/**
 * Enables some preconfigured settings, switches are aliases over LAME settings
 * 
 * @package Lame
 * @author Bernard Baltrusaitis <bernard@runawaylover.info>
 * @license http://unlicense.org/UNLICENSE Unlicense 
 */
class Preset implements EncodingInterface
{
    /**
     * Preset type
     * 
     * @var string 
     */
    protected $type = self::TYPE_STANDARD;
    
    /**
     * This preset should provide near transparency
     * to most people on most music.
     */
    CONST TYPE_MEDIUM = 'medium';
    
    /**
     * This preset should generally be transparent
     * to most people on most music and is already
     * quite high in quality.
     */
    CONST TYPE_STANDARD = 'standard';
    
    /**
     * If you have extremely good hearing and similar
     * equipment, this preset will generally provide
     * slightly higher quality than the "standard"
     * mode.
     */
    CONST TYPE_EXTREME = 'extreme';
    
    /**
     * This preset will usually be overkill for most
     * people and most situations, but if you must
     * have the absolute highest quality with no
     * regard to filesize, this is the way to go
     */
    CONST TYPE_INSANE = 'insane';
    
    /**
     * Enable one of the presets
     * <i>Possible values: 'medium', 'standard', 'extreme', 'insane'</i>
     * 
     * @param string $type preset type
     * @return \Lame\Settings\Encoding\Preset 
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
    
    /**
     * Get preset type
     * 
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return array(
            '--preset' => $this->getType()
        );
    }    
}