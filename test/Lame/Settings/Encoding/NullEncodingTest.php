<?php
namespace Lame\Settings\Encoding;

class NullEncodingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Lame\Settings\Encoding\NullEncoding
     */
    protected $encoding;
    
    protected function setUp()
    {
        parent::setUp();

        $this->encoding = new NullEncoding();
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
    public function testNullEncodingOptionsIsEmptyArray($options)
    {
        $this->assertCount(0, $options);
    }    
}