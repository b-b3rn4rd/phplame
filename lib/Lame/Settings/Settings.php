<?php

namespace Lame\Settings;

/**
 * Lame Settings
 * 
 * @package Lame
 * @author Bernard Baltrusaitis <bernard@runawaylover.info>
 * @license http://unlicense.org/UNLICENSE Unlicense 
 */
class Settings
{
    /**
     * Selected encoding type
     * 
     * @var null|\Lame\Settings\Encoding\EncodingInterface 
     */
    protected $encoding = null;
    
    /**
     * Lame options
     * 
     * @var array 
     */
    protected $options = array();
    
    /**
     * Create lame settings
     * 
     * @param \Lame\Settings\Encoding\EncodingInterface $encoding encoding related settings
     * @param array $options other lame settings
     * @return \Lame\Settings\Settings 
     */
    public function __construct(Encoding\EncodingInterface $encoding, 
        array $options = array())
    {
        $this->setAvailableOptions();
        
        $this->encoding = $encoding;
        $this->mergeOptions($options);
    }
    
    /**
     * Set mode selection
     * 
     * <i>Possible modes: (j)oint, (s)imple, (f)orce, 
     * (d)ual-mono, (m)ono (l)eft (r)ight</i>
     * 
     * @param string $mode mode selection
     * @return \Lame\Settings\Settings 
     */
    public function setChannelMode($mode)
    {
        $this->setOption('-m', $mode);
        
        return $this;
    }    
    
    /**
     * Set internal algorithm quality setting  0..9
     * 
     * @param int $quality algorithm quality setting
     * @return \Lame\Settings\Settings  
     */
    public function setAlgorithmQuality($quality)
    {
        $this->setOption('-q', $quality);
        
        return $this;
    }    
    
    
    /**
     * Set given LAME option's value
     * 
     * @param string $key option name
     * @param mixed $value option value
     * @return \Lame\Settings\Settings
     * @throws \InvalidArgumentException 
     */
    public function setOption($key, $value)
    {
        if (!array_key_exists($key, $this->options)) {
            throw new \InvalidArgumentException(
                sprintf('Unknown LAME option: `%s`', $key));
        }
        
        $this->options[$key] = $value;
        
        return $this;
    }
    
    /**
     * Build LAME options string using prev. specified values
     * 
     * @return string LAME options string 
     */
    public function buildOptions()
    {
        $return = '';
        
        $this->mergeOptions(
            $this->encoding->getOptions());
        
        foreach ($this->options as $key => $value) {
            if (is_null($value)) {
                continue;
            }
            
            if (true === $value) {
                $return .= sprintf(' %s', $key);
            } else {
                $return .= sprintf(' %s %s', $key, $value);
            }
        }
        
        return $return;
    }
    
    /**
     * Merge given $options with already specified
     * Lame options
     * 
     * @param array $options lame options
     * @return null 
     */
    protected function mergeOptions(array $options)
    {
        foreach ($options as $key => $value) {
            $this->setOption($key, $value);
        }
    }
    
    /**
     * Set available lame options
     * 
     * @return null 
     */
    protected function setAvailableOptions()
    {
        $this->options = array(
            '--scale'                => null, 
            '--scale-l'              => null, 
            '--scale-r'              => null, 
            '--gain'                 => null, 
            '--swap-channel'         => null, 
            '--mp1input'             => null, 
            '--mp2input'             => null, 
            '--mp3input'             => null, 
            '--nogap'                => null, 
            '--nogapout'             => null, 
            '--nogaptags'            => null, 
            '--out-dir'              => null, 
            '-r'                     => null, 
            '-s'                     => null, 
            '--signed'               => null, 
            '--unsigned'             => null, 
            '--bitwidth'             => null, 
            '-x'                     => null, 
            '--little-endian'        => null, 
            '--big-endian'           => null, 
            '-a'                     => null, 
            '--lowpass'              => null, 
            '--lowpass-width'        => null, 
            '--highpass'             => null, 
            '--highpass-width'       => null, 
            '--resample'             => null, 
            '--preset'               => null, 
            '--decode'               => null, 
            '--decode-mp3delay'      => null, 
            '-t'                     => null, 
            '-m'                     => null, 
            '-q'                     => null, 
            '-h'                     => null, 
            '-f'                     => null, 
            '--priority'             => null, 
            '-b'                     => null, 
            '--freeformat'           => null, 
            '-v'                     => null, 
            '--vbr-old'              => null, 
            '--vbr-new'              => null, 
            '-V'                     => null, 
            '-b'                     => null, 
            '-B'                     => null, 
            '-F'                     => null, 
            '-t'                     => null, 
            '--nohist'               => null, 
            '--abr'                  => null, 
            '--cbr'                  => null, 
            '-e'                     => null, 
            '-p'                     => null, 
            '-c'                     => null, 
            '-o'                     => null, 
            '-S'                     => null, 
            '--strictly-enforce-ISO' => null, 
            '--replaygain-fast'      => null, 
            '--replaygain-accurate'  => null, 
            '--noreplaygain'         => null, 
            '--clipdetect'           => null, 
            '--tt'                   => null, 
            '--ta'                   => null, 
            '--tl'                   => null, 
            '--ty'                   => null, 
            '--tc'                   => null, 
            '--tn'                   => null, 
            '--tg'                   => null, 
            '--ti'                   => null, 
            '--tv'                   => null, 
            '--add-id3v2'            => null, 
            '--id3v1-only'           => null, 
            '--id3v2-only'           => null, 
            '--id3v2-utf16'          => null, 
            '--id3v2-latin1'         => null, 
            '--space-id3v1'          => null, 
            '--pad-id3v2'            => null, 
            '--pad-id3v2-size'       => null, 
            '--genre-list'           => null, 
            '--ignore-tag-errors'    => null
        );
    }    
}