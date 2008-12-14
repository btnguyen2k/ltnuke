<?php
class LtNuke_Dzit_ReturnHomeHandler extends Ddth_Dzit_ActionHandler_AbstractActionHandler {
    /**
     * {@see Ddth_Dzit_ActionHandler_AbstractActionHandler::performAction()}
     */
    protected function performAction() {
        /**
         * @var Ddth_Dzit_IUrlCreator
         */
        $urlCreator = $this->getApplication()->getUrlCreator();
        $url = $urlCreator->createUrl(Ddth_Dzit_DzitConstants::ACTION_DEFAULT);
        return new Ddth_Dzit_ControlForward_UrlRedirectControlForward($url);
    }
}
?>
