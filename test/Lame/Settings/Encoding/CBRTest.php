<?php
namespace Lame\Settings\Encoding;

class CBRTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Lame\Settings\Encoding\CBR
     */
    protected $encoding;
    
    protected function setUp()
    {
        parent::setUp();

        $this->encoding = new CBR();
    }
    
    public function testSetBitrateReturnSelf()
    {
        $this->assertInstanceOf('\Lame\Settings\Encoding\CBR', $this->encoding->setBitrate(100));
    }
    
    public function testBitrateSettorAndGettorSelf()
    {
        $value = 100;
        
        $this->assertEquals($value, $this->encoding->setBitrate($value)->getBitrate());
    }
    
    public function testDefaultBitrateIs128()
    {
        $this->assertEquals(128, $this->encoding->getBitrate());
    }
    
    public function testGetOptionsReturnArray()
    {
        $options = $this->encoding->getOptions();
        $this->assertInternalType('array', $options);
        
        return $options;
    }
    
    /**
     * @depends testGetOptionsReturnArray
     */
    public function testGetOptionsHasCBROption($options)
    {
        $this->assertArrayHasKey('--cbr', $options);
        return $options['--cbr'];
    }
    
    /**
     * @depends testGetOptionsHasCBROption
     */
    public function testGetOptionsCBRIsEnabled($cbr)
    {
        $this->assertTrue($cbr);
    }
    
    /**
     * @depends testGetOptionsReturnArray
     */
    public function testGetOptionsHasbOption($options)
    {
        $this->assertArrayHasKey('-b', $options);
    }    
}