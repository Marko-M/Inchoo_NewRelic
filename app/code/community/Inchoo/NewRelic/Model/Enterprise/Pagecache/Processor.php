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

class Inchoo_NewRelic_Model_Enterprise_Pagecache_Processor extends Enterprise_PageCache_Model_Processor
{

    /**
     * Get page content from cache storage
     *
     * Inchoo: Name transaction for New Relic when loading from FPC
     *
     * @param string $content
     * @return string|false
     */
    public function extractContent($content)
    {
        $content =  parent::extractContent($content);

        // If compiler disabled, do not proceed
        if(!defined('COMPILER_INCLUDE_PATH')) {
            return $content;
        }

        /*
         * Name the transaction when loading from FPC:
         *
         * 1. When whole page is served from cache
         * 2. When cached content is to be processed for hole punching on pagecache/request/process route.
         *
         * Else, controller_action_predispatch observer will name the transaction.
         */
        if($content || Mage::registry('cached_page_content')) {
            if (extension_loaded ('newrelic')) {
                $route = $this->getMetadata('routing_requested_route');
                $controller = $this->getMetadata('routing_requested_controller');
                $action = $this->getMetadata('routing_requested_action');

                $transactionName = "$route/$controller/$action";

                newrelic_name_transaction($transactionName);

                //Mage::log(__FUNCTION__.': '.$transactionName);
            }
        }

        return $content;
    }

}