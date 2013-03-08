Contributing to the ICU component
=================================

A very good way of contributing to the ICU component is by updating the
included data for the ICU version you have installed on your system.

Preparation
-----------

To prepare, you need to install the development dependencies of the component.

    $ cd /path/to/Symfony/Component/Icu
    $ composer.phar install --dev

Determining your ICU version
---------------------------

The ICU version installed in your PHP environment can be found by running
icu-version.php:

    $ php Resources/bin/icu-version.php

Updating the ICU data
---------------------

To update the data files, run the build-data.php script:

    $ php Resources/bin/build-data.php

The script needs the binaries "svn" and "make" to be available on your system.
It will download the latest version of the ICU sources for the ICU version
installed in your PHP environment. The script will then compile the "genrb"
binary and use it to compile the ICU data files to binaries. The binaries are
copied to the component's resource files.

Once the script is complete, you can commit and push the update and create a
pull request.

Creating a pull request
-----------------------

When you create a pull request, make sure to submit the pull request to the
correct master branch. If you updated the ICU data for version 4.8, your
pull request goes to branch `48-master`, for version 49 to `49-master` and so
on.

Updating the ICU data for a different version
---------------------------------------------

You can also update the ICU data for a different ICU version than the one
installed in your PHP environment. You do so by passing the version as first
parameter to the build-data.php script. This is not recommended though and could
cause problems.

   $ php build-data.php 49

Combining .res files to a .dat-package
--------------------------------------

The individual *.res files can be combined into a single .dat-file.
Unfortunately, PHP's `ResourceBundle` class is currently not able to handle
.dat-files.

Once it is, the following steps have to be followed to build the .dat-file:

1. Package the resource bundles into a single file

   $ find . -name *.res | sed -e "s/\.\///g" > packagelist.txt
   $ pkgdata -p region -T build -d . packagelist.txt

2. Clean up

   $ rm -rf build packagelist.txt

3. You can now move region.dat to replace the version bundled with Symfony2.
