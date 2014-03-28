<?php

namespace Lame\Settings\Encoding;

/**
 * Null Encoding, use this encoding when lame 
 * options will be specified manually
 * 
 * @package Lame
 * @author Bernard Baltrusaitis <bernard@runawaylover.info>
 * @license http://unlicense.org/UNLICENSE Unlicense 
 */
class NullEncoding implements EncodingInterface
{
    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return array();
    }    
}