<?php
/**
 * Created by PhpStorm.
 * User: camph
 * Date: 14/08/2018
 * Time: 17:15
 */
namespace Ecommage\BackendTranslate\Plugins;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Serialize;
use Magento\Framework\App\Area;
use Magento\Framework\Module;
class TestTranslate
{
    const CONFIG_AREA_KEY = 'area';
    const CONFIG_LOCALE_KEY = 'locale';
    const CONFIG_SCOPE_KEY = 'scope';
    const CONFIG_THEME_KEY = 'theme';
    const CONFIG_MODULE_KEY = 'module';

    /**
     * Locale code
     *
     * @var string
     */
    protected $_localeCode;

    /**
     * Translator configuration array
     *
     * @var array
     */
    protected $_config;

    /**
     * Cache identifier
     *
     * @var string
     */
    protected $_cacheId;

    /**
     * Translation data
     *
     * @var []
     */
    protected $_data = [];

    /**
     * @var \Magento\Framework\View\DesignInterface
     */
    protected $_viewDesign;

    /**
     * @var \Magento\Framework\Cache\FrontendInterface $cache
     */
    protected $_cache;

    /**
     * @var \Magento\Framework\View\FileSystem
     */
    protected $_viewFileSystem;

    /**
     * @var \Magento\Framework\Module\ModuleList
     */
    protected $_moduleList;

    /**
     * @var \Magento\Framework\Module\Dir\Reader
     */
    protected $_modulesReader;

    /**
     * @var \Magento\Framework\App\ScopeResolverInterface
     */
    protected $_scopeResolver;

    /**
     * @var \Magento\Framework\Translate\ResourceInterface
     */
    protected $_translateResource;

    /**
     * @var \Magento\Framework\Locale\ResolverInterface
     */
    protected $_locale;

    /**
     * @var \Magento\Framework\App\State
     */
    protected $_appState;

    /**
     * @var \Magento\Framework\Filesystem\Directory\Read
     */
    protected $directory;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\Framework\File\Csv
     */
    protected $_csvParser;

    /**
     * @var \Magento\Framework\App\Language\Dictionary
     */
    protected $packDictionary;

    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    private $serializer;

    /**
     * @param \Magento\Framework\View\DesignInterface $viewDesign
     * @param \Magento\Framework\Cache\FrontendInterface $cache
     * @param \Magento\Framework\View\FileSystem $viewFileSystem
     * @param \Magento\Framework\Module\ModuleList $moduleList
     * @param \Magento\Framework\Module\Dir\Reader $modulesReader
     * @param \Magento\Framework\App\ScopeResolverInterface $scopeResolver
     * @param \Magento\Framework\Translate\ResourceInterface $translate
     * @param \Magento\Framework\Locale\ResolverInterface $locale
     * @param \Magento\Framework\App\State $appState
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\File\Csv $csvParser
     * @param \Magento\Framework\App\Language\Dictionary $packDictionary
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    const BACKEND_LANGUAGE_CACHE = 'backend_';
    public function __construct(
        \Magento\Framework\View\DesignInterface $viewDesign,
        \Magento\Framework\App\Cache\Type\Translate $cache,
        \Magento\Framework\View\FileSystem $viewFileSystem,
        \Magento\Framework\Module\ModuleList $moduleList,
        \Magento\Framework\Module\Dir\Reader $modulesReader,
        \Magento\Framework\App\ScopeResolverInterface $scopeResolver,
        \Magento\Framework\Translate\ResourceInterface $translate,
        \Magento\Framework\Locale\ResolverInterface $locale,
        \Magento\Framework\App\State $appState,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\File\Csv $csvParser,
        \Magento\Framework\App\Language\Dictionary $packDictionary
    ) {
        $this->_viewDesign = $viewDesign;
        $this->_cache = $cache;
        $this->_viewFileSystem = $viewFileSystem;
        $this->_moduleList = $moduleList;
        $this->_modulesReader = $modulesReader;
        $this->_scopeResolver = $scopeResolver;
        $this->_translateResource = $translate;
        $this->_locale = $locale;
        $this->_appState = $appState;
        $this->request = $request;
        $this->directory = $filesystem->getDirectoryRead(DirectoryList::ROOT);
        $this->_csvParser = $csvParser;
        $this->packDictionary = $packDictionary;

        $this->_config = [
            self::CONFIG_AREA_KEY => null,
            self::CONFIG_LOCALE_KEY => null,
            self::CONFIG_SCOPE_KEY => null,
            self::CONFIG_THEME_KEY => null,
            self::CONFIG_MODULE_KEY => null
        ];
    }

    public function afterloadData(\Magento\Framework\Translate $translate, $result)
	{
        $area = $this->_appState->getAreaCode();
        if ($area == Area::AREA_ADMINHTML){
            $this->setConfig(
                [
                    self::CONFIG_AREA_KEY => $area,
                ]
            );
            $this->_cacheId = $this->getCacheId();
            $backendTranslate = (int)$this->_cache->load(self::BACKEND_LANGUAGE_CACHE . $this->_cacheId);
            if (!$backendTranslate){
                $data = $this->_cache->load($this->getCacheId());

                if ($data){
                    $locale = $translate->getLocale();
                    $this->_data = $this->getSerializer()->unserialize($data);
                    $translateData = $this->getBackendTranslateData($locale);
                    $this->_addData($translateData);
                    $this->_saveCache();
                    $this->_cache->save('1', self::BACKEND_LANGUAGE_CACHE.$this->getCacheId(),[],false);
                    $translate->loadData();
                }
            }
        }

        /*$result = (array)$result;
        foreach ($result as $key => $value){
            if (is_array($value)){
                foreach ($value as $k => $v){
                    if ($k === 'area'){
                        $this->area = $v;
                    }
                    if ($k === 'locale'){
                        $this->locale = $v;
                    }


                    if ($k === 'scope'){
                        $this->scope = $v;
                    }
                    if ($k === 'theme'){
                        $this->theme = $v;
                    }
                    if ($k === 'module'){
                        $this->module = $v;
                    }
                }
            }

        }

        if ($this->serializer === null) {
            $this->serializer = \Magento\Framework\App\ObjectManager::getInstance()
                ->get(Serialize\SerializerInterface::class);
        }
        $_cacheId = \Magento\Framework\App\Cache\Type\Translate::TYPE_IDENTIFIER;
        $_cacheId .= '_' . $this->locale;
        $_cacheId .= '_' . $this->area;
        $_cacheId .= '_' . $this->scope;
        $_cacheId .= '_' . $this->theme;
        $_cacheId .= '_' . $this->module;
        $this->_cacheId = $_cacheId;
        $data = $this->_cache->load($this->_cacheId);

        if ($data) {
            $this->_data = $this->serializer->unserialize($data);
        }


        if (file_exists($this::file_path)){
            $this->csv->setDelimiter(',');
            $data = $this->csv->getDataPairs($this::file_path);
            $this->_data = $this->_addData($data);
            return $this;
        }









        //$result = (object)$result;*/
        return $result;


	}
    protected function getControllerModuleName()
    {
        return $this->request->getControllerModule();
    }
	public function getBackendTranslateData($locale){
        $result = [];
        $file = $this->_modulesReader->getModuleDir(Module\Dir::MODULE_I18N_DIR, 'Ecommage_BackendTranslate');
        $file .= '/' . $locale . '.csv';
        if (file_exists($file)){
            $this->_csvParser->setDelimiter(',');
            $result = $this->_csvParser->getDataPairs($file);

        }
        return $result;
    }
    private function getSerializer()
    {
        if ($this->serializer === null) {
            $this->serializer = \Magento\Framework\App\ObjectManager::getInstance()
                ->get(Serialize\SerializerInterface::class);
        }
        return $this->serializer;
    }
    protected function _saveCache()
    {
        $this->_cache->save($this->getSerializer()->serialize($this->getData()), $this->getCacheId(), [], false);
        return $this;
    }
    public function setLocale($locale)
    {
        $this->_localeCode = $locale;
        $this->_config[self::CONFIG_LOCALE_KEY] = $locale;
        return $this;
    }
    public function getLocale()
    {
        if (null === $this->_localeCode) {
            $this->_localeCode = $this->_locale->getLocale();
        }
        return $this->_localeCode;
    }
    public function getData()
    {
        if ($this->_data === null) {
            return [];
        }
        return $this->_data;
    }

    protected function getConfig($key)
    {
        if (isset($this->_config[$key])) {
            return $this->_config[$key];
        }
        return null;
    }

    protected function getScope()
    {
        $scope = ($this->getConfig(self::CONFIG_AREA_KEY) === 'adminhtml') ? 'admin' : null;
        return $this->_scopeResolver->getScope($scope)->getCode();
    }
    protected function setConfig($config)
    {
        $this->_config = $config;
        if (!isset($this->_config[self::CONFIG_LOCALE_KEY])) {
            $this->_config[self::CONFIG_LOCALE_KEY] = $this->getLocale();
        }
        if (!isset($this->_config[self::CONFIG_SCOPE_KEY])) {
            $this->_config[self::CONFIG_SCOPE_KEY] = $this->getScope();
        }
        if (!isset($this->_config[self::CONFIG_THEME_KEY])) {
            $this->_config[self::CONFIG_THEME_KEY] = $this->_viewDesign->getDesignTheme()->getThemePath();
        }
        if (!isset($this->_config[self::CONFIG_MODULE_KEY])) {
            $this->_config[self::CONFIG_MODULE_KEY] = $this->getControllerModuleName();
        }
        return $this;
    }
	public function getCacheId(){
        $_cacheId = \Magento\Framework\App\Cache\Type\Translate::TYPE_IDENTIFIER;
        $_cacheId .= '_' . $this->_config[self::CONFIG_LOCALE_KEY];
        $_cacheId .= '_' . $this->_config[self::CONFIG_AREA_KEY];
        $_cacheId .= '_' . $this->_config[self::CONFIG_SCOPE_KEY];
        $_cacheId .= '_' . $this->_config[self::CONFIG_THEME_KEY];
        $_cacheId .= '_' . $this->_config[self::CONFIG_MODULE_KEY];

        $this->_cacheId = $_cacheId;
        return $_cacheId;
    }
    public function _addData($data)
    {
        foreach ($data as $key => $value) {
            if ($key === $value) {
                if (isset($this->_data[$key])) {
                    unset($this->_data[$key]);
                }
                continue;
            }

            $key = str_replace('""', '"', $key);
            $value = str_replace('""', '"', $value);

            $this->_data[$key] = $value;
        }
        return $this;
    }

}