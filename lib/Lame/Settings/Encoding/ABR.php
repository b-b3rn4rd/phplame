<?php

namespace Lame\Settings\Encoding;

/**
 * Use Average Bitrate Encoding (ABR) and specify ABR related options
 * 
 * @package Lame
 * @author Bernard Baltrusaitis <bernard@runawaylover.info>
 * @license http://unlicense.org/UNLICENSE Unlicense
 */
class ABR implements EncodingInterface
{
    /**
     * Average bitrate
     * 
     * @var int 
     */
    protected $bitrate = 192;
    
    /**
     * Get average bitrate desired
     * 
     * @return int 
     */
    public function getBitrate()
    {
        return $this->bitrate;
    }
    
    /**
     * Specify average bitrate desired
     * 
     * <i>turns on encoding with a targeted average bitrate of n kbps, allowing
     * to use frames of different sizes.  The allowed range of n is 8...320 
     * kbps, you can use any integer value within that range.</i>
     * 
     * @param int $bitrate average bitrate desired
     * @return \Lame\Settings\Bitrate\ABR 
     */
    public function setBitrate($bitrate)
    {
        $this->bitrate = $bitrate;
        return $this;
    }
    
    /**
     * {@inheritdoc }
     */
    public function getOptions()
    {
        return array(
            '--abr' => $this->getBitrate()
        );
    }    
}