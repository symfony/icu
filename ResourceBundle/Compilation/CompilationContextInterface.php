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

/**
 * Stores contextual information for resource bundle compilation.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
interface CompilationContextInterface
{
    /**
     * Returns the directory where the source versions of the resource bundles
     * are stored.
     *
     * @return string An absolute path to a directory.
     */
    public function getSourceDir();

    /**
     * Returns the directory where the binary resource bundles are stored.
     *
     * @return string An absolute path to a directory.
     */
    public function getBinaryDir();

    /**
     * Returns the directory where the stub resource bundles are stored.
     *
     * @return string An absolute path to a directory.
     */
    public function getStubDir();

    /**
     * Returns a tool for manipulating the filesystem.
     *
     * @return \Symfony\Component\Filesystem\Filesystem The filesystem manipulator.
     */
    public function getFilesystem();

    /**
     * Returns a resource bundle compiler.
     *
     * @return ResourceBundleCompilerInterface The loaded resource bundle compiler.
     */
    public function getCompiler();

    /**
     * Returns the ICU version of the bundles being converted.
     *
     * @return string The ICU version string.
     */
    public function getIcuVersion();
}
