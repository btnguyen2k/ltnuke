<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Definitions of Dzit Constants.
 *
 * LICENSE: This source file is subject to version 3.0 of the GNU Lesser General
 * Public License that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/lgpl.html. If you did not receive a copy of
 * the GNU Lesser General Public License and are unable to obtain it through the web,
 * please send a note to gnu@gnu.org, or send an email to any of the file's authors
 * so we can email you a copy.
 *
 * @package		Dzit
 * @author		NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html LGPL 3.0
 * @id			$Id: ClassDzitConstants.php 27 2008-07-14 03:43:23Z btnguyen2k@gmail.com $
 * @since      	File available since v0.1
 */

/**
 * Definitions of Dzit Constants.
 *
 * @package    	Dzit
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
class Ddth_Dzit_DzitConstants {
    const APP_ATTR_ROOT_DATA_MODELS     = 'ROOT_DATA_MODELS';
    const APP_ATTR_CURRENT_ACTION       = 'CURRENT_ACTION';

    const SCRIPT_DEFAULT                = 'index.php';

    const ACTION_DEFAULT                = 'index';
    
    const LANGUAGE_DEFAULT              = 'default';
    
    const TEMPLATE_DEFAULT              = 'default';
    
    const SESSION_TRANSMISSION          = 'TRANSMISSIONS';
    
    const URL_PARAM_TRANSMISSION        = 'trans';

    //roots data models
    const DATAMODEL_LANGUAGE            = 'language';
    const DATAMODEL_PAGE                = 'page';

    //page's data models
    const DATAMODEL_PAGE_HEADER                = 'header';
    const DATAMODEL_PAGE_HEADER_TITLE          = 'title';
    const DATAMODEL_PAGE_HEADER_CHARSET        = 'charset';
    const DATAMODEL_PAGE_HEADER_DESCRIPTION    = 'description';
    const DATAMODEL_PAGE_HEADER_KEYWORDS       = 'keywords';
    const DATAMODEL_PAGE_HEADER_REDIRECT_URL   = 'redirectUrl';

    const DATAMODEL_PAGE_FORM                  = 'form';
    
    const DATAMODEL_PAGE_CONTENT               = 'content';
}
?>