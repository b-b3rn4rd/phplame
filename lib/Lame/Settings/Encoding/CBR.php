<?php

namespace Lame\Settings\Encoding;

/**
 *  Use Constant Bitrate Encoding (CBR) and specify CBR related options
 *
 * @package Lame
 * @author Bernard Baltrusaitis <bernard@runawaylover.info>
 * @license http://unlicense.org/UNLICENSE Unlicense 
 */
class CBR implements EncodingInterface
{
    /**
     * Bitrate in kbps
     * 
     * @var int 
     */
    protected $bitrate = 128;
    
    /**
     * Get bitrate (8, 16, 24, ..., 320)
     * 
     * @return int 
     */
    public function getBitrate()
    {
        return $this->bitrate;
    }
    
    /**
     * Set bitrate in kbps (default 128 kbps)
     * possible values (8, 16, 24, ..., 320)
     * 
     * @param int $bitrate bitrate
     * @return \Lame\Settings\Bitrate\CBR 
     */
    public function setBitrate($bitrate)
    {
        $this->bitrate = $bitrate;
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOptions()
    {
        return array(
            '--cbr' => true,
            '-b'    => $this->getBitrate()
        );
    }    
}