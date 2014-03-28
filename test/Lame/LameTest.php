<?php
namespace Lame;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamFile;
use \PHPUnit_Framework_Error;

/**
 * Override glob function, because it does not work with custom stream
 * 
 * @link https://github.com/mikey179/vfsStream/issues/2
 * @param string $pattern pattern
 * @return string 
 */
function glob($pattern)
{
    $found = array();
    
    
    if (strpos($pattern, '|') !== false) {
        list($directory, $pattern) = explode('|', $pattern);
    } else {
        list($directory, $pattern) = array($pattern, '');
    }
    
    if (empty($directory)) {
        return false;
    }
    
    if (is_dir($directory)) {
        $files = scandir($directory);
    }
    
    if (is_file($directory)) {
        return array($directory);
    }
    

    foreach ($files as $filename) {
        if (fnmatch($pattern, $filename)) {
            $found[] = $directory . '/' . $filename;
        }
    }

    return $found;
}

class LameTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Lame\Lame
     */
    protected $lame;
    
    /**
     *
     * @var \org\bovigo\vfs\vfsStream 
     */
    protected $filesystem = null;
    
    public function setUp()
    {
        parent::setUp();
        
        $structure = array(
            'usr' => array(
                'bin' => array()
            ),
            'tmp' => array(
                'music' => array(
                    'wawfiles' => array(
                            'mp3'                      => array(),
                            'hello world.waw'          => 'nice song',
                            'abc.waw'                  => 'bad song',
                            'put that cookie down.waw' => 'best song ever',
                            "zed's dead baby.waw"      => 'another cool song'
                    )
                )
            )
        );
        
        $vfs = vfsStream::setup('root');
        
        vfsStream::create($structure, $vfs);
        vfsStream::newFile('usr/bin/lame', 0777)
            ->at($vfs)
            ->setContent('binary file');
        
        $this->filesystem = $vfs;
        
        $encoding = $this->getMockBuilder('\Lame\Settings\Encoding\NullEncoding')
            ->getMock();
        
        $settings = $this->getMockBuilder('\Lame\Settings\Settings')
            ->setConstructorArgs(array($encoding))
            ->getMock();
        
        $this->lame = new Lame(vfsStream::url('root/usr/bin/lame'), $settings);
    }
    
    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testLameConstructorRequiresInstanceOfSettings()
    {
        new Lame('', null);
    }
    
    public function testLameConstructorSetsBinaryPath()
    {
        $this->assertEquals(vfsStream::url('root/usr/bin/lame'), $this->lame->getBinary());
    } 
    
    public function testLameConstructorSetsSettings()
    {
        $this->assertInstanceOf('\Lame\Settings\Settings', $this->lame->getSettings());
    }
    /*
    public function testEncodeReturnNull()
    {
        $inputfile = vfsStream::url('root/tmp/music/wawfiles/hello world.waw');
        $outputfile = vfsStream::url('root/tmp/music/wawfiles/abc.waw');
        
        $this->assertNull($this->lame->encode($inputfile, $outputfile));
    }
    */
    public function testEncodeInvokeGetFilenamesMethod()
    {
        $inputfile = vfsStream::url('root/tmp/music/wawfiles/hello world.waw');
        $outputfile = vfsStream::url('root/tmp/music/wawfiles/abc.waw');
            
        $encoding = $this->getMockBuilder('\Lame\Settings\Encoding\NullEncoding')
            ->getMock();
        
        $settings = $this->getMockBuilder('\Lame\Settings\Settings')
            ->setConstructorArgs(array($encoding))
            ->getMock();
        
        $lame = $this->getMockBuilder('\Lame\Lame')
            ->setConstructorArgs(array('', $settings))
            ->setMethods(array('getFilenames', 'prepareCommand', 'executeCommand'))
            ->getMock();
        
        $lame->expects($this->once())
            ->method('getFilenames')
            ->will($this->returnValue(array($inputfile => $outputfile)))
            ->with($this->identicalTo($inputfile), $this->identicalTo($outputfile));
        
        $lame->encode($inputfile, $outputfile);
    }
    
    
    public function testEncodeInvokePrepareCommandUsingGetFilenamesReturn()
    {
        $inputfile = vfsStream::url('root/tmp/music/wawfiles/hello world.waw');
        $outputfile = vfsStream::url('root/tmp/music/wawfiles/abc.waw');
        
        $encoding = $this->getMockBuilder('\Lame\Settings\Encoding\NullEncoding')
            ->getMock();
        
        $settings = $this->getMockBuilder('\Lame\Settings\Settings')
            ->setConstructorArgs(array($encoding))
            ->getMock();
        
        $lame = $this->getMockBuilder('\Lame\Lame')
            ->setConstructorArgs(array('', $settings))
            ->setMethods(array('getFilenames', 'prepareCommand', 'executeCommand'))
            ->getMock();
        
        $lame->expects($this->once())
            ->method('getFilenames')
            ->will($this->returnValue(array($inputfile => $outputfile)));
        
        $lame->expects($this->once())
            ->method('prepareCommand')
            ->with($this->identicalTo($inputfile), $this->identicalTo($outputfile));
        
        $lame->encode($inputfile, $outputfile);
    }
    
    public function testEncodeInvokeExecuteCommandUsingPrepareCommandReturn()
    {
        $inputfile = vfsStream::url('root/tmp/music/wawfiles/hello world.waw');
        $outputfile = vfsStream::url('root/tmp/music/wawfiles/abc.waw');
        
        $preparedCommand = sprintf('%s %s', $inputfile, $outputfile);
        
        $encoding = $this->getMockBuilder('\Lame\Settings\Encoding\NullEncoding')
            ->getMock();
        
        $settings = $this->getMockBuilder('\Lame\Settings\Settings')
            ->setConstructorArgs(array($encoding))
            ->getMock();
        
        $lame = $this->getMockBuilder('\Lame\Lame')
            ->setConstructorArgs(array('', $settings))
            ->setMethods(array('getFilenames', 'prepareCommand', 'executeCommand'))
            ->getMock();
        
        $lame->expects($this->once())
            ->method('getFilenames')
            ->will($this->returnValue(array($inputfile => $outputfile)));
        
        $lame->expects($this->once())
            ->method('prepareCommand')
            ->will($this->returnValue($preparedCommand));
        
        $lame->expects($this->once())
            ->method('executeCommand')
            ->with($this->identicalTo($preparedCommand));
        
        $lame->encode($inputfile, $outputfile);
    }
    
    public function testEncodeInvokeCallbackIfGiven()
    {
        $inputfile = vfsStream::url('root/tmp/music/wawfiles/hello world.waw');
        $outputfile = vfsStream::url('root/tmp/music/wawfiles/abc.waw');
        
        $preparedCommand = sprintf('%s %s', $inputfile, $outputfile);
        
        $encoding = $this->getMockBuilder('\Lame\Settings\Encoding\NullEncoding')
            ->getMock();
        
        $settings = $this->getMockBuilder('\Lame\Settings\Settings')
            ->setConstructorArgs(array($encoding))
            ->getMock();
        
        $lame = $this->getMockBuilder('\Lame\Lame')
            ->setConstructorArgs(array('', $settings))
            ->setMethods(array('getFilenames', 'prepareCommand', 'executeCommand'))
            ->getMock();
        
        $lame->expects($this->once())
            ->method('getFilenames')
            ->will($this->returnValue(array($inputfile => $outputfile)));
        
        $lame->expects($this->once())
            ->method('prepareCommand')
            ->will($this->returnValue($preparedCommand));
  
        $that = $this;
        
        $lame->encode($inputfile, $outputfile, function($i, $o) use ($that, $inputfile, $outputfile) {
            $that->assertEquals($i, $inputfile);
            $that->assertEquals($o, $outputfile);
        });
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testPrepareCommandExpectsBinaryToBeExecutable()
    {
        $inputfile = vfsStream::url('root/tmp/music/wawfiles/hello world.waw');
        $outputfile = vfsStream::url('root/tmp/music/wawfiles/abc.waw');
        
        vfsStream::newFile('usr/bin/badlame', 400)
            ->at($this->filesystem)
            ->setContent('unexecutable binary file');
        $binary = new \ReflectionProperty($this->lame, 'binary');
        $binary->setAccessible(true);
        $binary->setValue($this->lame, vfsStream::url('root/usr/bin/badlame'));
        
        $prepareCommand = new \ReflectionMethod($this->lame, 'prepareCommand');
        $prepareCommand->setAccessible(true);
        $prepareCommand->invokeArgs($this->lame, array($inputfile, $outputfile));
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testPrepareCommandExpectsInputfileToBeReable()
    {
        $inputfile = vfsStream::url('root/tmp/music/wawfiles/badfilename.waw');
        $outputfile = vfsStream::url('root/tmp/music/wawfiles/abc.waw');
        
        $prepareCommand = new \ReflectionMethod($this->lame, 'prepareCommand');
        $prepareCommand->setAccessible(true);
        $prepareCommand->invokeArgs($this->lame, array($inputfile, $outputfile));
    }
    
    public function testPrepareCommandInvokeBuildOptionsMethod()
    {
        $inputfile = vfsStream::url('root/tmp/music/wawfiles/hello world.waw');
        $outputfile = vfsStream::url('root/tmp/music/wawfiles/abc.waw');
        
        $encoding = $this->getMockBuilder('\Lame\Settings\Encoding\NullEncoding')
            ->getMock();
        
        $settings = $this->getMockBuilder('\Lame\Settings\Settings')
            ->setConstructorArgs(array($encoding))
            ->setMethods(array('buildOptions'))
            ->getMock();
        
        
        $settings->expects($this->once())
            ->method('buildOptions')
            ->will($this->returnValue('args'));
        
        $lame = $this->getMockBuilder('\Lame\Lame')
            ->setConstructorArgs(array(vfsStream::url('root/usr/bin/lame'), $settings))
            ->setMethods(array('getBinary'))
            ->getMock();
        
        $lame->expects($this->once())
            ->method('getBinary')
            ->will($this->returnValue(vfsStream::url('root/usr/bin/lame')));
        
        $prepareCommand = new \ReflectionMethod($lame, 'prepareCommand');
        $prepareCommand->setAccessible(true);
        $command = $prepareCommand->invokeArgs($lame, array($inputfile, $outputfile));
        
        $this->assertEquals("".vfsStream::url('root/usr/bin/lame')." args '{$inputfile}' '{$outputfile}'", $command);
    }
    
    /**
     * @expectedException InvalidArgumentException 
     */
    public function testGetFilenamesExpectsValidPatternForInputfile()
    {
        $inputfile = false;
        $outputfile = null;
        
        $getFilenames = new \ReflectionMethod($this->lame, 'getFilenames');
        $getFilenames->setAccessible(true);
        $getFilenames->invokeArgs($this->lame, array($inputfile, $outputfile));
    }
    
    /**
     * @expectedException InvalidArgumentException 
     */
    public function testGetFilenamesExpectsOutputfileToBeDirectoryIfPatternIsGiven()
    {
        $inputfile = sprintf('%s|%s', vfsStream::url('root/tmp/music/wawfiles'), '*.waw');
        $outputfile = false;
       
        $getFilenames = new \ReflectionMethod($this->lame, 'getFilenames');
        $getFilenames->setAccessible(true);
        $getFilenames->invokeArgs($this->lame, array($inputfile, $outputfile));
    }
    
    public function testGetFilenamesMakesNamesForOutputfilesIfDirectoryIsGiven()
    {
        $inputfile = sprintf('%s|%s', vfsStream::url('root/tmp/music/wawfiles'), '*.waw');
        $outputfile = vfsStream::url('root/tmp/music/wawfiles/mp3');
        
        $getFilenames = new \ReflectionMethod($this->lame, 'getFilenames');
        $getFilenames->setAccessible(true);
        $files = $getFilenames->invokeArgs($this->lame, array($inputfile, $outputfile));
        
        foreach ($files as $inputfile => $outfile) {
            $this->assertSame(basename($inputfile, '.waw'), 
                basename($outfile, '.mp3'));
        }
    }
    
    public function testGetFilenamesDoesNotMakeNamesIfOutputfileIsFile()
    {
        $inputfile = vfsStream::url('root/tmp/music/wawfiles/abc.waw');
        $outputfile = vfsStream::url('root/tmp/music/wawfiles/mp3/zzzz.mp3');
        
        $getFilenames = new \ReflectionMethod($this->lame, 'getFilenames');
        $getFilenames->setAccessible(true);
        $files = $getFilenames->invokeArgs($this->lame, array($inputfile, $outputfile));
        
        foreach ($files as $inputfile => $outfile) {
            $this->assertSame('zzzz', basename($outfile, '.mp3'));
        }
    }    
}