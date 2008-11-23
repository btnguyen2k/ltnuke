<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * A generic ViewRenderer.
 *
 * LICENSE: This source file is subject to version 3.0 of the GNU Lesser General
 * Public License that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/lgpl.html. If you did not receive a copy of
 * the GNU Lesser General Public License and are unable to obtain it through the web,
 * please send a note to gnu@gnu.org, or send an email to any of the file's authors
 * so we can email you a copy.
 *
 * @package		Dzit
 * @subpackage  ViewRenderer
 * @author		NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html LGPL 3.0
 * @id			$Id: ClassGenericViewRenderer.php 22 2008-05-19 04:22:44Z btnguyen2k@gmail.com $
 * @since      	File available since v0.1
 */

/**
 * A generic ViewRenderer.
 *
 * @package    	Dzit
 * @subpackage  ViewRenderer
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
class Ddth_Dzit_ViewRenderer_GenericViewRenderer extends Ddth_Dzit_ViewRenderer_AbstractViewRenderer {
    /**
     * @var Ddth_Commons_Logging_ILog
     */
    private $LOGGER;

    /**
     * Constructs a new Ddth_Dzit_ViewRenderer_GenericViewRenderer object.
     */
    public function __construct() {
        $clazz = __CLASS__;
        $this->LOGGER = Ddth_Commons_Logging_LogFactory::getLog($clazz);
        parent::__construct();
    }

    /**
     * {@see Ddth_Dzit_IViewRenderer::render()}
     */
    public function render() {
        $action = $this->getAction();
        $page = $this->getTemplate()->getPage($action);
        if ( $page !== NULL ) {
            $rootDataModels = $this->getRootDataModels();
            $root = new Ddth_Template_DataModel_Map('');
            foreach ( array_values($rootDataModels) as $node ) {
                $root->addChild($node->getName(), $node);
            }
            $page->render($root);
        } else {
            $msg = "Null presentation page for action [$action]!";
            //$this->LOGGER->error($msg);
            throw new Ddth_Dzit_DzitException($msg);
        }
    }
}
?>