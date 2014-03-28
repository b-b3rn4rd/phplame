<?php
namespace Lame\Settings;

class SettingsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Lame\Settings\Settings
     */
    protected $settings;
    
    public function setUp()
    {
        parent::setUp();
        
        $encoding = $this->getMockBuilder('\Lame\Settings\Encoding\NullEncoding')
            ->getMock();
        
        $this->settings = new \Lame\Settings\Settings($encoding);
    }
    
    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testSettingsConstructorExpectEncodingToBeInstance()
    {
        new \Lame\Settings\Settings(null);
    }
    
    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testSettingsConstructorExpectOptionsToBeArray()
    {
        new \Lame\Settings\Settings(new \Lame\Settings\Encoding\NullEncoding(), null);
    }
    
    public function testSettingsConstructorCallSetAvailableOptions()
    {
        $encoding = $this->getMockBuilder('\Lame\Settings\Encoding\NullEncoding')
            ->getMock();
        
        $settings = $this->getMockBuilder('\Lame\Settings\Settings')
            ->disableOriginalConstructor()
            ->setMethods(array('setAvailableOptions'))
            ->getMock();
        
        $settings->expects($this->once())
            ->method('setAvailableOptions');
        
        $settings->__construct($encoding);
    }
    
    public function testSettingsConstructorCallMergeOptionsWithGivenOptions()
    {
        $options = array(
            '-m' => 1
        );
        
        $encoding = $this->getMockBuilder('\Lame\Settings\Encoding\NullEncoding')
            ->getMock();
        
        $settings = $this->getMockBuilder('\Lame\Settings\Settings')
            ->disableOriginalConstructor()
            ->setMethods(array('mergeOptions'))
            ->getMock();
        
        $settings->expects($this->once())
            ->method('mergeOptions')
            ->with($this->identicalTo($options));
        
        $settings->__construct($encoding, $options);
    }
    
    public function testSettingsConstructorSetEncoding()
    {
        $reflector = new \ReflectionObject($this->settings);
        
        $encoding = $reflector->getProperty('encoding');
        $encoding->setAccessible(true);
        
        $this->assertInstanceOf('\Lame\Settings\Encoding\NullEncoding', 
            $encoding->getValue($this->settings));
    }
    
    public function testSetChannelModeReturnSelf()
    {
        $this->assertInstanceOf('\Lame\Settings\Settings', 
            $this->settings->setChannelMode('abc'));
    }
    
    public function testSetChannelModeCallSetOption()
    {
        $encoding = $this->getMockBuilder('\Lame\Settings\Encoding\NullEncoding')
            ->getMock();
        
        $settings = $this->getMockBuilder('\Lame\Settings\Settings')
            ->setConstructorArgs(array($encoding))
            ->setMethods(array('setOption'))
            ->getMock();
        
        $settings->expects($this->once())
            ->method('setOption')
            ->with($this->identicalTo('-m'), $this->identicalTo('test'));
        
        $settings->setChannelMode('test');
    }
    
    public function testSetAlgorithmQualityReturnSelf()
    {
        $this->assertInstanceOf('\Lame\Settings\Settings', 
            $this->settings->setAlgorithmQuality(1));
    }
    
    public function testSetAlgorithmQualityCallSetOption()
    {
        $encoding = $this->getMockBuilder('\Lame\Settings\Encoding\NullEncoding')
            ->getMock();
        
        $settings = $this->getMockBuilder('\Lame\Settings\Settings')
            ->setConstructorArgs(array($encoding))
            ->setMethods(array('setOption'))
            ->getMock();
        
        $settings->expects($this->once())
            ->method('setOption')
            ->with($this->identicalTo('-q'), $this->identicalTo(1));
        
        $settings->setAlgorithmQuality(1);
    }
    
    public function testSetOptionReturnSelf()
    {
        $this->assertInstanceOf('\Lame\Settings\Settings', 
            $this->settings->setOption('-q', 'test'));
    }
    
    /**
     * @expectedException \InvalidArgumentException 
     */
    public function testSetOptionThrowsExceptionOnInvalidOption()
    {
        $this->assertInstanceOf('\Lame\Settings\Settings', 
            $this->settings->setOption('invlid option', 'test'));
    }
    
    public function testSetOptionSetsTheOption()
    {
        $quality = 5;
        $this->settings->setOption('-q', $quality);
        
        $options = new \ReflectionProperty($this->settings, 'options');
        $options->setAccessible(true);
        $values = $options->getValue($this->settings);
        
        $this->assertEquals($quality, $values['-q']);
    }
    
    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testMergeOptionExpectsArray()
    {
        $method = new \ReflectionMethod($this->settings, 'mergeOptions');
        $method->setAccessible(true);
        
        $method->invoke($this->settings, null);
    }
    
    
    public function testMergeOptionReturnNull()
    {
        $method = new \ReflectionMethod($this->settings, 'mergeOptions');
        $method->setAccessible(true);
        
        $this->assertNull($method->invoke($this->settings, array()));
    }
    
    public function optionProvider()
    {
        return array(
          array(array('-a' => 1)),
          array(array('-b' => null)),
          array(array('-s' => 1))
        );
    }
    
    /**
     * @dataProvider optionProvider
     */
    public function testMergeOptionInvokeSetOptionForEachOption($options)
    {
        $encoding = $this->getMockBuilder('\Lame\Settings\Encoding\NullEncoding')
            ->getMock();
        
        $settings = $this->getMockBuilder('\Lame\Settings\Settings')
            ->setConstructorArgs(array($encoding))
            ->setMethods(array('setOption'))
            ->getMock();
        
        $settings->expects($this->once())
            ->method('setOption')
            ->with($this->identicalTo(key($options)), $this->identicalTo(reset($options)));
        
        $method = new \ReflectionMethod($settings, 'mergeOptions');
        $method->setAccessible(true);
        
        $method->invoke($settings, $options);
    }
    
    public function testBuildOptionsGetsEncodingOptionsAndInvokeMergeOptions()
    {
        $options = array('-a' => 1);
        
        $encoding = $this->getMockBuilder('\Lame\Settings\Encoding\NullEncoding')
            ->setMethods(array('getOptions'))
            ->getMock();
        
        $encoding->expects($this->once())
            ->method('getOptions')
            ->will($this->returnValue($options));
        
        $settings = $this->getMockBuilder('\Lame\Settings\Settings')
            ->setConstructorArgs(array($encoding))
            ->setMethods(array('mergeOptions'))
            ->getMock();
        
        $settings->expects($this->once())
            ->method('mergeOptions')
            ->with($this->arrayHasKey('-a'));
        
        $settings->buildOptions();
    }
    
    
    public function testbuildOptionsIgnoreNullOptions()
    {
        $options = array(
            '-s' => 2,
            '-a' => true,
            '-b' => null,
        );
        
        $encoding = $this->getMockBuilder('\Lame\Settings\Encoding\NullEncoding')
            ->setMethods(array('getOptions'))
            ->getMock();
        
        $encoding->expects($this->once())
            ->method('getOptions')
            ->will($this->returnValue($options));
        
        $settings = new Settings($encoding);
        
        $this->assertRegExp('/^\s\-s\s2\s-a/', $settings->buildOptions());
    }
    
    public function testSetAvailableOptionsReturnNull()
    {
        $method = new \ReflectionMethod($this->settings, 'setAvailableOptions');
        $method->setAccessible(true);
        
        $this->assertNull($method->invoke($this->settings));
    }
    
    public function testSetAvailableOptionsSetOptionsInConstructor()
    {
        $encoding = $this->getMockBuilder('\Lame\Settings\Encoding\NullEncoding')
            ->getMock();
        
        $settings = $this->getMockBuilder('\Lame\Settings\Settings')
            ->disableOriginalConstructor()
            ->getMock();
        
        $optionsProperty = new \ReflectionProperty($settings, 'options');
        $optionsProperty->setAccessible(true);
        
        $this->assertCount(0, $optionsProperty->getValue($settings));
        $settings->__construct($encoding);
        
        $this->assertCount(75 ,$optionsProperty->getValue($settings));
    }    
}