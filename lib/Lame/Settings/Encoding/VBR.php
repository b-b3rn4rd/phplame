<?php

namespace Lame\Settings\Encoding;

/**
 * Use Variable Bit Rate Encoding (VBR) and specify VBR related options
 * 
 * @package Lame
 * @author Bernard Baltrusaitis <bernard@runawaylover.info>
 * @license http://unlicense.org/UNLICENSE Unlicense
 */
class VBR implements EncodingInterface
{
    /**
     * VBR quality setting
     * 
     * @var float 
     */
    protected $quality = null;
    
    /**
     * Minimum allowed bitrate
     * 
     * @var int|null 
     */
    protected $minBitrate = null;
    
    /**
     * Maximum allowed bitrate
     * 
     * @var int|null 
     */
    protected $maxBitrate = null;
    
    /**
     * Use old variable bitrate (VBR) routine
     * 
     * @var boolean|null 
     */
    protected $vbrOld = null;
    
    /**
     * Use new variable bitrate (VBR) routine (default)
     * 
     * @var boolean|null 
     */
    protected $vbrNew = null;
    
    /**
     * VBR quality setting
     * 
     * @return float|null 
     */
    public function getQuality()
    {
        return $this->quality;
    }
    
    /**
     * Get a minimum allowed bitrate
     * 
     * @return int|null 
     */
    public function getMinBitrate()
    {
        return $this->minBitrate;
    }
    
    /**
     * Get a maximum allowed bitrate
     * 
     * @return int|null 
     */
    public function getMaxBitrate()
    {
        return $this->maxBitrate;
    }
    
    /**
     * Determine if use new variable bitrate (VBR) routine (default)
     * 
     * @return boolean|null 
     */
    public function isVBRNew()
    {
        return $this->vbrNew;
    }
    
    /**
     * Determine if use old variable bitrate (VBR) routine
     * 
     * @return boolean|null 
     */
    public function isVBROld()
    {
        return $this->vbrOld;
    }
    
    /**
     * Specify a minimum allowed bitrate (8,16,24,...,320)
     * 
     * @param int $minBitrate min bitrate
     * @return \Lame\Settings\Encoding\VBR 
     */
    public function setMinBitrate($minBitrate)
    {
        $this->minBitrate = $minBitrate;
        
        return $this;
    }
    
    /**
     * Specify a maximum allowed bitrate (8,16,24,...,320)
     * 
     * @param int $maxBitrate maxbitrate
     * @return \Lame\Settings\Encoding\VBR 
     */
    public function setMaxBitrate($maxBitrate)
    {
        $this->maxBitrate = $maxBitrate;
        
        return $this;
    }
    
    /**
     * VBR quality setting  (0=highest quality, 9.999=lowest)
     * default is 4
     * 
     * @param float $quality quality setting
     * @return \Lame\Settings\Encoding\VBR
     */
    public function setQuality($quality)
    {
        $this->quality = $quality;
        
        return $this;
    }
    
    
    /**
     * Use new variable bitrate (VBR) routine (default)
     * 
     * @param boolean $flag use vbr new
     * @return \Lame\Settings\Encoding\VBR
     */
    public function setVBRNew($flag)
    {
        $this->vbrNew = (bool)$flag;
        
        return $this;
    }
    
    /**
     * Use old variable bitrate (VBR) routine
     * 
     * @param boolean $flag use vbr old
     * @return \Lame\Settings\Encoding\VBR
     */
    public function setVBROld($flag)
    {
        $this->vbrOld = (bool)$flag;
        
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        $options = array();
        
        if (!is_null($this->getQuality())) {
            $options['-V'] = $this->getQuality();
        }
        
        if (!is_null($this->getMinBitrate())) {
            $options['-b'] = $this->getMinBitrate();
        }
        
        if (!is_null($this->getMaxBitrate())) {
            $options['-B'] = $this->getMaxBitrate();
        }
        
        if (true === $this->isVBRNew()) {
            $options['--vbr-new'] = true;
        }
        
        if (true === $this->isVBROld()) {
            $options['--vbr-old'] = true;
        }
        
        return $options;
    }    
}