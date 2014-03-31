<?php
namespace Lame;

/**
 * Lame wrapper
 * 
 * @package Lame
 * @author Bernard Baltrusaitis <bernard@runawaylover.info>
 * @license http://unlicense.org/UNLICENSE Unlicense 
 */
class Lame
{
    /**
     * LAME binary file path
     * 
     * @var null|string 
     */
    protected $binary = null;
    
    /**
     * LAME settings
     * 
     * @var \Lame\Settings 
     */
    protected $settings = null;
    
    /**
     * Create new instance of LAME wrapper
     * 
     * @param string $binary lame binary file location
     * @param Settings\Settings $settings instance of lame settings
     * @return \Lame\Lame 
     */
    public function __construct($binary, Settings\Settings $settings)
    {
        $this->settings = $settings;
        $this->binary = $binary;
    }
    
    /**
     * LAME binary file path
     * 
     * @return string|null 
     */
    public function getBinary()
    {
        return $this->binary;
    }
    
    /**
     * Get Lame settings
     * 
     * @return \Lame\Settings\Settings 
     */
    public function getSettings()
    {
        return $this->settings;
    }
    
    /**
     * Encode given wav file(s) into mp3 file(s).
     * $inputfile can be a location to a single file or a pattern
     * $outputfile can be a file name or a directory name
     * <b>if $inputfile is a pattern then $outputfile should be an existing
     * directory.</b>
     * Callback function should have the following structure:
     * <code>
     * function ($inputfile, $outputfile) {
     *  /\* do someting with $inputfile or $outputfile file *\/
     * }
     * </code>
     * 
     * @param string $inputfile input file location or pattern
     * @param string $outputfile output file location or directory name
     * @param \Closure $callback callback function to be called after encoding
     * @return null
     */
    public function encode($inputfile, $outputfile, \Closure $callback = null)
    { 
        $files = $this->getFilenames($inputfile, $outputfile);
        
        foreach ($files as $inputfile => $outputfile) {
            $this->executeCommand($this->prepareCommand($inputfile, $outputfile));
            
            if (is_null($callback)) {
                continue;
            }
            
            call_user_func_array($callback, array($inputfile, $outputfile));
        } 
    }
    
    /**
     * Prepare LAME command to be executed
     * 
     * @param string $inputfile input file name
     * @param string $outputfile output file name
     * @return string LAME command
     * @throws \InvalidArgumentException
     */
    protected function prepareCommand($inputfile, $outputfile)
    {
        $binary = $this->getBinary();
        
        if (!is_executable($binary)) {
            throw new \InvalidArgumentException(
                sprintf('LAME binary path: `%s` is invalid or not executable', $binary));
        }
        
        if (!is_readable($inputfile)) {
            throw new \InvalidArgumentException(
                sprintf('Input file `%s` is not readable', $inputfile));
        }
        
        $command = sprintf('%s ', $binary);
        $command .= $this->getSettings()->buildOptions();
        $command .= sprintf(' %s %s', escapeshellarg($inputfile), 
            escapeshellarg($outputfile));
        
        return $command;
    }
    
    /**
     * Execute given command
     * 
     * @param string $command command to be executed
     * @return boolean
     * @throws \RuntimeException 
     */
    protected function executeCommand($command)
    {
        $output = '';
        $handle = popen("{$command} 2>&1", 'r');
        
        while (!feof($handle)) {
            $output .= fgets($handle);
        }

        $returnCode = pclose($handle);
        
        if (0 !== $returnCode) {
            throw new \RuntimeException(
                sprintf('LAME execution error! command: `%s`, error: `%s`', 
                    $command, $output));
        }
        
        return true;
    }
    
    /**
     * Get source/destination file names
     * 
     * @param string $inputfile input file name or pattern
     * @param string $outputfile output file name or directory
     * @return array assoc. array of input and output files to be processed
     * @throws \InvalidArgumentException 
     */
    protected function getFilenames($inputfile, $outputfile)
    {
        $filenames  = array();
        $inputfiles = glob($inputfile);
        
        if (!is_array($inputfiles)) {
            throw new \InvalidArgumentException(
                sprintf('`%s` is invalid input file location or pattern', 
                    $inputfile));
        }
        
        if ((1 < sizeof($inputfiles)) && !is_dir($outputfile)) {
            throw new \InvalidArgumentException(
                sprintf('If input file is a pattern, output file should be 
                    an existing directory, `%s` given', $outputfile));
        }
        
        if (is_dir($outputfile)) {
            $outputfile = rtrim($outputfile, DIRECTORY_SEPARATOR);
        }
        
        foreach ($inputfiles as $inputfile) {
            
            if (is_dir($outputfile)) {
                $filename = pathinfo($inputfile, PATHINFO_FILENAME);
                $filenames[$inputfile] = sprintf('%s/%s.mp3', $outputfile, $filename);
            } else {
                $filenames[$inputfile] = $outputfile;
            }
        }
        
        return $filenames;
    }    
}
