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

        $registryContent = Mage::registry('cached_page_content');

        /*
         * We want to catch situation where body content doesn't originate
         * in it's own route. This is when whole page is served from cache,
         * or when cached content is to be processed for hole punching
         * on pagecache/request/process route.
         */
        if($content || $registryContent) {
            /*
             * It's too early into request to have Mage::helper() available
             */
            $helper = new Inchoo_NewRelic_Helper_Data();

            $transactionName = $this->getMetadata(Inchoo_NewRelic_Helper_Data::INCHOO_NEWRELIC_METADATA_KEY);

            $helper->nameTransaction($transactionName);
        }

        return $content;
    }

    /**
     *
     * Save metadata for cache in cache storage
     *
     * Inchoo: Additionally save route/controller/action info as request metadata
     *
     */
    protected function _saveMetadata()
    {

        /** @var Inchoo_NewRelic_Helper_Data $helper */
        $helper = Mage::helper('inchoo_newrelic');

        $transactionName = $helper->getTransactionNameFromRequest();

        $this->setMetadata(Inchoo_NewRelic_Helper_Data::INCHOO_NEWRELIC_METADATA_KEY, $transactionName);

        return parent::_saveMetadata();
    }

}