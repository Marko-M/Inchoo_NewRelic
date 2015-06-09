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

class Inchoo_NewRelic_Model_Observer
{

    /**
     * Name transaction with new relic when not loading from FPC.
     *
     * @param Varien_Event_Observer $observer
     */
    public function nameTransaction(Varien_Event_Observer $observer)
    {

        /*
         * controller_action_predispatch is also dispatched for pagecache/request/process whose transaction
         * naming is covered by Inchoo_NewRelic_Model_Enterprise_Pagecache_Processor::extractContent().
         */
        if(Mage::registry('cached_page_content')) {
            return;
        }

        // Name the transaction when not loading from FPC
        if (extension_loaded ('newrelic')) {
            $request = Mage::app()->getRequest();

            $route = $request->getModuleName();
            $controller = $request->getControllerName();
            $action = $request->getActionName();

            $transactionName = "$route/$controller/$action";

            newrelic_name_transaction($transactionName);

            //Mage::log(__FUNCTION__.': '.$transactionName);
        }

    }

}