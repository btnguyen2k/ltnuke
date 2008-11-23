<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Handle 'index' action.
 *
 * @package    	LtNuke
 * @subpackage  Dzit
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
class LtNuke_Dzit_IndexHandler extends Ddth_Dzit_ActionHandler_AbstractActionHandler {

    /**
     * {@see Ddth_Dzit_ActionHandler_AbstractActionHandler::performAction()}
     */
    protected function performAction() {
        return new Ddth_Dzit_ControlForward_ViewControlForward($this->getAction());
    }
}
?>
