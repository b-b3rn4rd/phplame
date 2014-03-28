<?php
namespace Lame\Settings\Encoding;

class ABRTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Lame\Settings\Encoding\ABR
     */
    protected $encoding;
    
    protected function setUp()
    {
        parent::setUp();

        $this->encoding = new ABR();
    }
    
    public function testSetBitrateReturnSelf()
    {
        $this->assertInstanceOf('\Lame\Settings\Encoding\ABR', $this->encoding->setBitrate(100));
    }
    
    public function testBitrateSettorAndGettorSelf()
    {
        $value = 100;
        
        $this->assertEquals($value, $this->encoding->setBitrate($value)->getBitrate());
    }
    
    public function testDefaultBitrateIs192()
    {
        $this->assertEquals(192, $this->encoding->getBitrate());
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
    public function testGetOptionsHasABROption($options)
    {
        $this->assertArrayHasKey('--abr', $options);
    }    
}