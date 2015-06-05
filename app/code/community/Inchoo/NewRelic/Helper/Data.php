<?php

/**
 * Inchoo
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Please do not edit or add to this file if you wish to upgrade
 * Magento or this extension to newer versions in the future.
 ** Inchoo *give their best to conform to
 * "non-obtrusive, best Magento practices" style of coding.
 * However,* Inchoo *guarantee functional accuracy of
 * specific extension behavior. Additionally we take no responsibility
 * for any possible issue(s) resulting from extension usage.
 * We reserve the full right not to provide any kind of support for our free extensions.
 * Thank you for your understanding.
 *
 * @category Inchoo
 * @package NewRelic
 * @author Marko Martinović <marko.martinovic@inchoo.net>
 * @copyright Copyright (c) Inchoo (http://inchoo.net/)
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

class Inchoo_NewRelic_Helper_Data extends Mage_Core_Helper_Abstract
{
    const INCHOO_NEWRELIC_METADATA_KEY = 'inchoo_newrelic_transaction_name';

    /**
     * Compose transaction name from request object
     *
     * @return string
     */
    public function getTransactionNameFromRequest()
    {
        /** @var Mage_Core_Controller_Request_Http $request */
        $request = Mage::app()->getRequest();

        $route = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();

        return $route.'/'.$controller.'/'.$action;
    }

    /**
     * Name the transaction using New Relic PHP agent API
     *
     * @param $transactionName
     */
    public function nameTransaction($transactionName)
    {
        if(!$transactionName) {
            $transactionName = 'Unknown';
        }

        if (extension_loaded ('newrelic')) {
            newrelic_name_transaction($transactionName);
        }

        //Mage::log(__FUNCTION__.': '.$transactionName);
    }

}