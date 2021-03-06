<?php
 /**
 * This file is part of the Certificationy web platform.
 * (c) Johann Saunier (johann_27@hotmail.fr)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 **/

namespace Certificationy\Component\Certy\Provider;

use Certificationy\Component\Certy\Provider\Configuration\FileProviderConfiguration;
use Symfony\Component\Finder\Finder;

abstract class AbstractFileProvider extends AbstractProvider implements FileProviderInterface
{
    /**
     * @var FileProviderConfiguration
     */
    private $config;

    /**
     * @param \SplFileInfo $file
     * @param string       $certificationName
     *
     * @return mixed
     */
    abstract protected function loadFile(\SplFileInfo $file, $certificationName);

    /**
     * @return FileProviderConfiguration
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return FileProviderConfiguration
     */
    protected function createProviderConfiguration(array $options = [])
    {
        return new FileProviderConfiguration($options);
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options = [])
    {
        $this->config = $this->createProviderConfiguration($options);
    }

    /**
     * @param string $certificationName
     *
     * @return \Resource[]|void
     */
    public function load($certificationName)
    {
        $options = $this->getConfig()->getOptions();

        foreach ($options['path'] as $id => $path) {
            $options['path'][$id] = $path.'/'.$certificationName.'/'.$this->getName();
        }

        $finder = new Finder();
        $finder->files()->in($options['path'])->sortByName();

        foreach ($finder as $file) {
            $this->loadFile($file, $certificationName);
        }
    }
}
