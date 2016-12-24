<?php
namespace Sm\ShopBy\Controller\Category\View;

/**
 * Interceptor class for @see \Sm\ShopBy\Controller\Category\View
 */
class Interceptor extends \Sm\ShopBy\Controller\Category\View implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Catalog\Model\Design $catalogDesign, \Magento\Catalog\Model\Session $catalogSession, \Magento\Framework\Registry $coreRegistry, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator $categoryUrlPathGenerator, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory, \Magento\Catalog\Model\Layer\Resolver $layerResolver, \Magento\Framework\View\LayoutInterface $layout, \Magento\Framework\Json\EncoderInterface $jsonEncoder, \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository)
    {
        $this->___init();
        parent::__construct($context, $catalogDesign, $catalogSession, $coreRegistry, $storeManager, $categoryUrlPathGenerator, $resultPageFactory, $resultForwardFactory, $layerResolver, $layout, $jsonEncoder, $categoryRepository);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        if (!$pluginInfo) {
            return parent::execute();
        } else {
            return $this->___callPlugins('execute', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        if (!$pluginInfo) {
            return parent::dispatch($request);
        } else {
            return $this->___callPlugins('dispatch', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getActionFlag()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getActionFlag');
        if (!$pluginInfo) {
            return parent::getActionFlag();
        } else {
            return $this->___callPlugins('getActionFlag', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRequest');
        if (!$pluginInfo) {
            return parent::getRequest();
        } else {
            return $this->___callPlugins('getRequest', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getResponse');
        if (!$pluginInfo) {
            return parent::getResponse();
        } else {
            return $this->___callPlugins('getResponse', func_get_args(), $pluginInfo);
        }
    }
}
