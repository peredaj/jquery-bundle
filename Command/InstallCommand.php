<?php

namespace Peredaj\JQueryBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Exception\IOException;

class InstallCommand extends ContainerAwareCommand
{
    private $kernelRoot;
    
    private $targetRoot;
    
    private $vendorRoot;
    
    private $successPattern = 'Library <comment>%s</comment> was installed in <info>%s</info>';


    public function configure()
    {
        $this
            ->setName('peredaj:install')
            ->setDescription('Install libs in bundle')
            ;
    }
    
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->kernelRoot = $this->getContainer()->getParameter('kernel.root_dir');
        $this->targetRoot = dirname(__DIR__).'/Resources/public';
        $this->vendorRoot = dirname($this->kernelRoot).'/vendor';

        $this->installJQuery($input, $output);
        $this->installSelect2($input, $output);
    }
    
    protected function installSelect2(InputInterface $input, OutputInterface $output)
    {
        $fs = new Filesystem();
        $select2Root = $this->vendorRoot.'/ivaynberg/select2';
        $targetRoot = $this->targetRoot.'/select2';
        
        if(!$fs->exists($select2Root))
        {
            $output->writeln(sprintf('Can not found <comment>select2</comment> vendor root "%s"', $select2Root));
            
            return;
        }
        
        $finder = new Finder();
        $files = array();
        foreach($finder->in($select2Root) as $file)
        {
            if($file->isFile())
            {
                $files[] = array(
                    $file->getRealPath(),
                    $targetRoot . substr($file->getRealPath(), strlen($select2Root)),
                );
            }
        }
        
        try
        {
            foreach($files as $file)
            {
                $fs->touch($file[1]);
                $fs->copy($file[0], $file[1], true);
            }

            $output->writeln(sprintf($this->successPattern, 'Select2', 'PeredajJQueryBundle/Resources/public/select2/'));
        }
        catch(IOException $e)
        {
            $output->writeln($e->getMessage());

            return;
        }
    }
    
    protected function installJQuery(InputInterface $input, OutputInterface $output)
    {
        $fs = new Filesystem();
        
        $jquery = file_get_contents('http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js');
        
        if(false === $jquery)
            throw new \Exception("Library jQuery can not be installed");
        
        $filename = $this->targetRoot.'/jquery/jquery.min.js';
        $path = dirname($filename);
        
        try
        {
            $fs->mkdir($path);
            $fs->touch($filename);
            $fs->dumpFile($filename, $jquery);
            
            $output->writeln(sprintf($this->successPattern, 'jQuery(1.11.0)', 'PeredajJQueryBundle/Resources/public/jquery/jquery.min.js'));
        }
        catch (IOException $e)
        {
            $output->writeln($e->getMessage());
            
            return;
        }
    }
}