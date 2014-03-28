<?php
namespace Lame\Settings\Encoding;

class PresetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Lame\Settings\Encoding\Preset
     */
    protected $encoding;
    
    protected function setUp()
    {
        parent::setUp();

        $this->encoding = new Preset();
    }
    
    public function testSetTypeReturnSelf()
    {
        $this->assertInstanceOf('\Lame\Settings\Encoding\Preset', 
            $this->encoding->setType(Preset::TYPE_STANDARD));
    }
    
    public function testDefaultTypeIsStandard()
    {
        $this->assertEquals(Preset::TYPE_STANDARD, $this->encoding->getType());
    }
    
    public function testGetTypeReturnSetType()
    {
        $this->encoding->setType(Preset::TYPE_MEDIUM);
        $this->assertEquals(Preset::TYPE_MEDIUM, $this->encoding->getType());
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
    public function testGetOptionsHasPresetOption($options)
    {
        $this->assertArrayHasKey('--preset', $options);
        return $options['--preset'];
    }
    
    /**
     *
     * @depends testGetOptionsHasPresetOption 
     */
    public function testPresetValueInGetOptionsEqualsGetType($preset)
    {
        $this->assertEquals($this->encoding->getType(), $preset);
    }    
}