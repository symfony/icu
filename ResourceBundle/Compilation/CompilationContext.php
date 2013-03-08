<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Icu\ResourceBundle\Compilation;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Default implementation of {@link CompilationContextInterface}.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class CompilationContext implements CompilationContextInterface
{
    /**
     * @var string
     */
    private $sourceDir;

    /**
     * @var string
     */
    private $binaryDir;

    /**
     * @var string
     */
    private $stubDir;

    /**
     * @var FileSystem
     */
    private $filesystem;

    /**
     * @var ResourceBundleCompilerInterface
     */
    private $compiler;

    /**
     * @var string
     */
    private $icuVersion;

    public function __construct($sourceDir, $binaryDir, $stubDir, Filesystem $filesystem, ResourceBundleCompilerInterface $compiler, $icuVersion)
    {
        $this->sourceDir = $sourceDir;
        $this->binaryDir = $binaryDir;
        $this->stubDir = $stubDir;
        $this->filesystem = $filesystem;
        $this->compiler = $compiler;
        $this->icuVersion = $icuVersion;
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceDir()
    {
        return $this->sourceDir;
    }

    /**
     * {@inheritdoc}
     */
    public function getBinaryDir()
    {
        return $this->binaryDir;
    }

    /**
     * {@inheritdoc}
     */
    public function getStubDir()
    {
        return $this->stubDir;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function getCompiler()
    {
        return $this->compiler;
    }

    /**
     * {@inheritdoc}
     */
    public function getIcuVersion()
    {
        return $this->icuVersion;
    }
}
