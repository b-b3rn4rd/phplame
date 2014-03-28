<?php
namespace Lame\Settings\Encoding;

class VBRTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Lame\Settings\Encoding\VBR
     */
    protected $encoding;
    
    protected function setUp()
    {
        parent::setUp();

        $this->encoding = new VBR();
    }
    
    public function testQualityDefailtIsNull()
    {
        $this->assertNull($this->encoding->getQuality());
    }
    
    public function testMinBitrateDefailtIsNull()
    {
        $this->assertNull($this->encoding->getMinBitrate());
    }
    
    public function testIsVBRNewNull()
    {
        $this->assertNull($this->encoding->isVBRNew());
    }
    
    public function testIsVBROldNull()
    {
        $this->assertNull($this->encoding->isVBROld());
    }
    
    public function testMaxBitrateDefailtIsNull()
    {
        $this->assertNull($this->encoding->getMaxBitrate());
    }
    
    public function testSetMinBitrateReturnSelf()
    {
        $this->assertInstanceOf('\Lame\Settings\Encoding\VBR', $this->encoding->setMinBitrate(1));
    }
    
    public function testSetVBRNewReturnSelf()
    {
        $this->assertInstanceOf('\Lame\Settings\Encoding\VBR', $this->encoding->setVBRNew(true));
    }
    
    public function testSetVBROldReturnSelf()
    {
        $this->assertInstanceOf('\Lame\Settings\Encoding\VBR', $this->encoding->setVBROld(true));
    }
    
    public function testGetMinBitrateReturnSetMinBitrateReturn()
    {
        $value = 100;
        $this->encoding->setMinBitrate($value);
        $this->assertEquals($value, $this->encoding->getMinBitrate());
    }
    
    public function testSetVBRNewConvertFlagToBoolean()
    {
        $this->encoding->setVBRNew('hello world');
        $this->assertTrue($this->encoding->isVBRNew());
        
        $this->encoding->setVBRNew(null);
        $this->assertFalse($this->encoding->isVBRNew());
    }
    
    public function testSetVBROldConvertFlagToBoolean()
    {
        $this->encoding->setVBROld('hello world');
        $this->assertTrue($this->encoding->isVBROld());
        
        $this->encoding->setVBROld(null);
        $this->assertFalse($this->encoding->isVBROld());
    }
    
    public function testSetMaxBitrateReturnSelf()
    {
        $this->assertInstanceOf('\Lame\Settings\Encoding\VBR', $this->encoding->setMaxBitrate(1));
    }
    
    public function testGetMaxBitrateReturnSetMaxBitrateReturn()
    {
        $value = 100;
        $this->encoding->setMaxBitrate($value);
        $this->assertEquals($value, $this->encoding->getMaxBitrate());
    }
    
    public function testSetQualityReturnSelf()
    {
        $this->assertInstanceOf('\Lame\Settings\Encoding\VBR', $this->encoding->setQuality(1));
    }
    
    public function testGetOptionsIsArray()
    {
        
        $options = $this->encoding->getOptions();
        
        $this->assertInternalType('array', $options);
        
        return $options;
    }
    
    /**
     * @depends testGetOptionsIsArray
     */
    public function testOptionsDoensHavebOption($options)
    {
        $this->assertArrayNotHasKey('-b', $options);
    }
    
    /**
     * @depends testGetOptionsIsArray
     */
    public function testOptionsDoensHave_VOption($options)
    {
        $this->assertArrayNotHasKey('-V', $options);
    }
    
    /**
     * @depends testGetOptionsIsArray
     */
    public function testOptionsDoensHaveVbrOldOption($options)
    {
        $this->assertArrayNotHasKey('--vbr-old', $options);
    }
    
    /**
     * @depends testGetOptionsIsArray
     */
    public function testOptionsDoensHaveVbrNewOption($options)
    {
        $this->assertArrayNotHasKey('--vbr-new', $options);
    }
    
    /**
     * @depends testGetOptionsIsArray
     */
    public function testOptionsDoensHave_BOption($options)
    {
        $this->assertArrayNotHasKey('-B', $options);
    }
    
    public function testMinBitrateOptionExistsIfNotNulllValueIsSpecified()
    {
        $this->encoding->setMinBitrate(1);
        $this->assertArrayHasKey('-b', $this->encoding->getOptions());
    }
    
    public function testMaxnBitrateOptionExistsIfNotNulllValueIsSpecified()
    {
        $this->encoding->setMaxBitrate(1);
        $this->assertArrayHasKey('-B', $this->encoding->getOptions());
    }
    
    public function testOptionsHaveVOptionIfQualityIsNotNull()
    {
        $this->encoding->setQuality(4);
        $this->assertArrayHasKey('-V', $this->encoding->getOptions());
    }
    
    public function testOptionsHaveVBRNewIfNotNull()
    {
        $this->encoding->setVBRNew(true);
        $this->assertArrayHasKey('--vbr-new', $this->encoding->getOptions());
    }
    
    public function testOptionsHaveVBROldIfNotNull()
    {
        $this->encoding->setVBROld(true);
        $this->assertArrayHasKey('--vbr-old', $this->encoding->getOptions());
    }   
}