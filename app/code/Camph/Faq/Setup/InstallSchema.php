<?php 
namespace Camph\Faq\Setup;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        
        $table_camph_faqgroup = $setup->getConnection()->newTable($setup->getTable('camph_faqgroup'));
        
        $table_camph_faqgroup->addColumn(
            'faqgroup_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [
                'identity' => true,
                'nullable' => false,
                'primary' => true,
                'unsigned' => true,
            ],
            'Entity ID'
            );
        
        $table_camph_faqgroup->addColumn(
            'groupname',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'groupname'
            );
        
        
        $table_camph_faqgroup->addColumn(
            'sortorder',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'sortorder'
            );
        
        $table_camph_faq = $setup->getConnection()->newTable($setup->getTable('camph_faq'));
        
        $table_camph_faq->addColumn(
            'faq_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [
                'identity' => true,
                'nullable' => false,
                'primary' => true,
                'unsigned' => true,
            ],
            'Entity ID'
            );
        
        $table_camph_faq->addColumn(
            'question',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'question'
            );
        
        $table_camph_faq->addColumn(
            'answer',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'answer'
            );
        
        $table_camph_faq->addColumn(
            'group',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'group'
            );
        
        $table_camph_faq->addColumn(
            'sortorder',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'sortorder'
            );
        
        $setup->getConnection()->createTable($table_camph_faq);
        $setup->getConnection()->createTable($table_camph_faqgroup);
        $setup->endSetup();
    }   
}
?>