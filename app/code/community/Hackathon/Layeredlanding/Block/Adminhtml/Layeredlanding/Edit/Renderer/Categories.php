<?php
//Mage_Adminhtml_Block_Catalog_Category_Checkboxes_Tree
class Hackathon_Layeredlanding_Block_Adminhtml_Layeredlanding_Edit_Renderer_Categories
 extends Mage_Adminhtml_Block_Catalog_Category_Tree
 implements Varien_Data_Form_Element_Renderer_Interface
{
    /** @var array  */
    protected $_selectedIds = array();

    public function __construct() {
        parent::__construct();
        $this->_withProductCount = false;
        $this->setJsFormObject('categories_selector');
    }

    /**
     * Render HTML
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        return $this->toHtml();
    }


    /**
     * @return Mage_Core_Block_Abstract
     */
    protected function _prepareLayout()
    {
        $this->setTemplate('catalog/category/checkboxes/tree.phtml');
        return $this;
    }

    /**
     * @return array
     */
    public function getCategoryIds()
    {
        return $this->_selectedIds;
    }


    /**
     * @param $ids
     * @return $this
     */
    public function setCategoryIds($ids)
    {
        if (empty($ids)) {
            $ids = array();
        }
        elseif (!is_array($ids)) {
            $ids = array((int)$ids);
        }
        $this->_selectedIds = $ids;
        return $this;
    }

    public function getLoadTreeUrl($expanded=null)
    {
        $params = array('_current'=>true, 'id'=>null,'store'=>null);
        if (
            (is_null($expanded) && Mage::getSingleton('admin/session')->getIsTreeWasExpanded())
            || $expanded == true) {
            $params['expand_all'] = true;
        }
        $params['layeredlanding_id'] = $this->getLayeredlandingId();
        return $this->getUrl('*/*/categoriesJson', $params);
    }


    /**
     * @param array|Varien_Data_Tree_Node $node
     * @param int                         $level
     * @return string
     */
    protected function _getNodeJson($node, $level = 1)
    {
        $item = parent::_getNodeJson($node, $level);

        if (in_array($node->getId(), $this->getCategoryIds())) {
            $item['checked'] = true;
        }

        return $item;
    }


    /**
     * Returns JSON-encoded array of category children
     *
     * @param int $categoryId
     * @return string
     */
    public function getCategoryChildrenJson($categoryId)
    {
        $node = $this->getRoot()->getTree()->getNodeById($categoryId);

        if (!$node || !$node->hasChildren()) {
            return '[]';
        }

        $children = array();
        foreach ($node->getChildren() as $child) {
            $children[] = $this->_getNodeJson($child);
        }

        return Mage::helper('core')->jsonEncode($children);
    }
}
