<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * A Generic Application.
 *
 * LICENSE: This source file is subject to version 3.0 of the GNU Lesser General
 * Public License that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/lgpl.html. If you did not receive a copy of
 * the GNU Lesser General Public License and are unable to obtain it through the web,
 * please send a note to gnu@gnu.org, or send an email to any of the file's authors
 * so we can email you a copy.
 *
 * @package		Dzit
 * @subpackage  App
 * @author		NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html LGPL 3.0
 * @id			$Id: ClassGenericApplication.php 21 2008-05-07 08:05:18Z btnguyen2k@gmail.com $
 * @since      	File available since v0.1
 */

/**
 * A Generic Application.
 *
 * @package    	Dzit
 * @subpackage  App
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
class Ddth_Dzit_App_GenericApplication extends Ddth_Dzit_App_AbstractApplication {

    /**
     * @var Ddth_Commons_Logging_ILog
     */
    private $LOGGER;

    /**
     * Constructs a new Ddth_Dzit_App_GenericApplication object.
     */
    public function __construct() {
        $clazz = __CLASS__;
        $this->LOGGER = Ddth_Commons_Logging_LogFactory::getLog($clazz);
        parent::__construct();
    }

    /**
     * {@see Ddth_Dzit_IApplication::execute()}
     */
    public function execute() {
        $httpRequest = $this->getHttpRequest();
        $action = $httpRequest->getAction();
        $actionHandler = $this->getActionHandler($action);
        $this->executeActionHandler($action, $actionHandler);
    }

    /**
     * Executes an action handler.
     *
     * @param Ddth_Dzit_IActionHandler
     * @throws Ddth_Dzit_DzitException
     */
    protected function executeActionHandler($action, $actionHandler) {
        if ( $actionHandler === NULL ) {
            $actionHandler = $this->getDefaultActionHandler();
        }
        if ( $actionHandler === NULL ) {
            $msg = "Null action handler!";
            throw new Ddth_Dzit_DzitException($msg);
        }
        $this->setCurrentActionHandler($actionHandler);
        $controlForward = $actionHandler->execute($action);
        if ( $controlForward instanceof Ddth_Dzit_ControlForward_ActionControlForward ) {
            $action = $controlForward->getAction();
            $myActionHandler = $this->getActionHandler($action);
            $this->executeActionHandler($action, $myActionHandler);
        } elseif ( $controlForward instanceof Ddth_Dzit_ControlForward_UrlRedirectControlForward ) {
            $url = $controlForward->getUrl();
            header("Location: $url");
        } elseif ( $controlForward instanceof Ddth_Dzit_ControlForward_ViewControlForward ) {
            $action = $controlForward->getAction();
            $myViewHandler = $this->getViewRenderer($action);
            if ( $myViewHandler===NULL || !($myViewHandler instanceof Ddth_Dzit_IViewRenderer) ) {
                if ( $myViewHandler!==NULL && !($myViewHandler instanceof Ddth_Dzit_IViewRenderer) ) {
                    $msg = "Invalid view handler for action [$action]!";
                    $this->LOGGER->warn($msg);    
                }
                $myViewHandler = $this->getDefaultViewRenderer();
            }
            if ( $myViewHandler===NULL || !($myViewHandler instanceof Ddth_Dzit_IViewRenderer) ) {
                $msg = "Can not find view renderer for action [$action]!";
                throw new Ddth_Dzit_DzitException($msg);
            }
            $myViewHandler->render();
        } else {
            //do nothing
        }
    }
}
?>