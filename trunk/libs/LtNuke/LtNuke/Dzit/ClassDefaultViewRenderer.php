<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Default view renderer.
 *
 * @package    	LtNuke
 * @subpackage  Dzit
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
class LtNuke_Dzit_DefaultViewRenderer extends Ddth_Dzit_ViewRenderer_GenericViewRenderer {
    /**
     * @var Ddth_Commons_Logging_ILog
     */
    private $LOGGER;

    /**
     * Constructs a new Ddth_Dzit_Demo_DefaultViewRenderer object.
     */
    public function __construct() {
        $clazz = __CLASS__;
        $this->LOGGER = Ddth_Commons_Logging_LogFactory::getLog($clazz);
        parent::__construct();
    }
}
?>