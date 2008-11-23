<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Abstract representation of file and directory pathnames.
 *
 * LICENSE: This source file is subject to version 3.0 of the GNU Lesser General
 * Public License that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/lgpl.html. If you did not receive a copy of
 * the GNU Lesser General Public License and are unable to obtain it through the web,
 * please send a note to gnu@gnu.org, or send an email to any of the file's authors
 * so we can email you a copy.
 *
 * @package		Commons
 * @author		NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html LGPL 3.0
 * @id			$Id: ClassFile.php 170 2008-05-19 08:10:39Z btnguyen2k@gmail.com $
 * @since      	File available since v0.1
 */

if ( !function_exists('__autoload') ) {
    /**
     * Automatically loads class source file when used.
     *
     * @param string
     * @ignore
     */
    function __autoload($className) {
        require_once 'Ddth/Commons/ClassDefaultClassNameTranslator.php';
        require_once 'Ddth/Commons/ClassLoader.php';
        $translator = Ddth_Commons_DefaultClassNameTranslator::getInstance();
        Ddth_Commons_Loader::loadClass($className, $translator);
    }
}

/**
 * An abstract representation of file and directory pathnames.
 *
 * @package    	Commons
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html LGPL 3.0
 * @since      	Class available since v0.1
 */
class Ddth_Commons_File {

    /**
     * Abstract directory separator.
     * @var string
     */
    const SEPARATOR = '/';

    /**
     * @var string
     */
    private $pathname = NULL;

    /**
     * Constructs a new Ddth_Commons_File object.
     *
     * @param string
     * @param Ddth_Commons_File
     */
    public function __construct($pathname, $parent=NULL) {
        $temp = $pathname;
        if ( $parent !== NULL ) {
            if ( $parent instanceof Ddth_Commons_File ) {
                $temp = $parent->getPathname() . '/' . $temp;
            } else {
                $temp = $parent . '/' . $temp;
            }
        }
        $this->pathname = $this->normalize($temp);
    }

    /**
     * Alias of {@link isReadable()}.
     *
     * @return bool
     */
    public function canRead() {
        return $this->isReadable();
    }

    /**
     * Alias of {@link isWritable()}.
     *
     * @return bool
     */
    public function canWrite() {
        return $this->isWrite();
    }

    /**
     * Tests whether the file or directory denoted by this abstract pathname
     * exists.
     *
     * @return bool
     */
    public function exists() {
        return file_exists($this->getPathname());
    }

    //    /**
    //     * Gets absolute pathname of the file.
    //     *
    //     * @return string
    //     */
    //    public function getAbsolutePath() {
    //        return realpath($this->getPathname());
    //    }

    /**
     * Gets base filename of the file (i.e. /etc/test.txt --> test.txt).
     *
     * @see getPathname()
     * @return string
     */
    public function getBasename() {
        return basename($this->getPathname());
    }

    /**
     * Gets extension part of the file (i.e. /etc/test.txt --> txt).
     *
     * @return string
     */
    public function getExtension() {
        return pathinfo($this->getPathname(), PATHINFO_EXTENSION);
    }

    /**
     * Gets filename part of the file (i.e. /etc/test.txt --> test).
     *
     * @return string
     */
    public function getFilename() {
        $basename = $this->getBasename();
        $ext = $this->getExtension();
        $temp = substr($basename, 0, strlen($basename)-strlen($ext));
        return preg_replace('/\.+/', '', $temp);
    }

    /**
     * Gets the abstract pathname of the file.
     *
     * @return string
     */
    public function getPathname() {
        return $this->pathname;
    }

    /**
     * Tests whether the file denoted by this abstract file is a directory.
     *
     * @return bool true if the abstract file exists and is a directory, false otherwise
     */
    public function isDirectory() {
        return is_dir($this->getPathname());
    }

    /**
     * Tests whether the file denoted by this abstract file is a normal
     * file (non-directory.
     *
     * @return bool true if the abstract file exists and is a regular file, false otherwise
     */
    public function isFile() {
        return is_file($this->getPathname());
    }

    /**
     * Tests whether the file denoted by this abstract file is readable.
     *
     * @return bool
     */
    public function isReadable() {
        return is_readable($this->getPathname());
    }

    /**
     * Tests whether the file denoted by this abstract file is writable.
     *
     * @return bool
     */
    public function isWritable() {
        return is_writable($this->getPathname());
    }

    /**
     * Alias of {@link isWritable()}.
     *
     * @return bool
     */
    public function isWriteable() {
        return $this->isWritable();
    }

    /**
     * Normalizes a path name.
     *
     * @param string
     * @return string
     */
    protected function normalize($pathname) {
        if ( $pathname === NULL ) {
            return "";
        }
        //normalize directory separator
        $pathname = preg_replace('/[\\\\\\/]+/', self::SEPARATOR, trim($pathname));
        if ( $pathname !== self::SEPARATOR ) {
            //remove trailing 'directory separators'
            $pathname = preg_replace('/[\\\\\\/]+$/', '', trim($pathname));
        }
        return $pathname;
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function __toString() {
        return $this->getPathname();
    }
}
?>