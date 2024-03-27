<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\components;

use common\managers\SectionManager;
use Yii;

use yii\base\InvalidConfigException;
use yii\web\CompositeUrlRule;
use yii\web\UrlRuleInterface;

/**
 * SectionUrlRule
 */
class SectionUrlRule extends CompositeUrlRule
{
    /**
     * @var array the rules contained within this composite rule. Please refer to [[UrlManager::rules]]
     * for the format of this property.
     * @see prefix
     * @see routePrefix
     */
    public $rules = [];

    /**
     * @var string the prefix for the pattern part of every rule declared in [[rules]].
     * The prefix and the pattern will be separated with a slash.
     */
    public $title = 'Раздел';

    public $section_id = null;

    public $prefix = null;

    public $folder = null;

    public $template = null;

    // Модель секции
    public $object = null;

    // Параметры по умолчанию для вставки в rules defaults
    // задают значения для аргументов метода экшена ( function actionName($selected = true) )
    public $params = [];

    /**
     * @var array the default configuration of URL rules. Individual rule configurations
     * specified via [[rules]] will take precedence when the same property of the rule is configured.
     */
    public $ruleConfig = ['class' => '\yii\web\UrlRule'];

    /**
     * @inheritdoc
     */
    public function init()
    {
        //$Site = Yii::$app->factory->site;
        //$this->folder = str_replace('@upload', $Site->getUploadUrl(), $this->folder);

        parent::init();
    }

    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * Парсим запрос
     * если нашли секцию, соответсвующую запросу - выставяем текущей
     */
    public function parseRequest($manager, $request)
    {
        $result = parent::parseRequest($manager, $request);

        if ($result) {
            $SectionManager = Yii::$container->get(SectionManager::class);
            $SectionManager->setCurrentSection($this);
        }

        return $result;
    }

    public function getUrl()
    {
        return '/' . trim($this->prefix, '/');
    }

    public function getParam($name, $default = null)
    {
        return $this->params[$name] ?? $default;
    }

    /**
     * При генерации url мы задаем id текущей секции
     * а значит генерация url в рамках текущего раздела пройдет корректно
     * но при необходимости сгенерировать url к соседнему разделу метод уже не прокатит
     */
    public function createUrl($manager, $route, $params)
    {
        if (!isset($params['__section_id'])) {
            //$SectionManager = Yii::$container->get('PersonaSectionManager');
            //$Rule = $SectionManager->getCurrentSection();
            //$params['__section_id'] = $Rule->object->id ?? 0;
        }

        return parent::createUrl($manager, $route, $params);
    }

    /**
     * @inheritdoc
     */
    protected function createRules()
    {
        $rules = [];

        foreach ($this->rules as $key => $ruleData) {
            if (is_array($ruleData)) {
                throw new InvalidConfigException('Rules array element could be string only.');
            }

            $verb = null;
            $parts = explode(' ', $key);
            $pattern = rtrim('/' . ltrim($key, '/'), '/');

            // Если указан verb
            if (sizeof($parts) > 1) {
                $verb = $parts[0];
                $pattern = '/' . ltrim('/' . rtrim('/' . ltrim($parts[1], '/'), '/'), '/');
            }

            $rule = [
                'verb' => $verb,
                'pattern' => str_replace('@', $this->prefix, $pattern),
                'route' => $ruleData,
            ];

            if ($this->params) {
                foreach ($this->params as $defName => $defValue) {
                    $rule['defaults'][$defName] = $defValue;
                }
            }

            // Добавляем section_id в defaults
            // необходимо для корректной работы Url::current() метода
            if ($this->object) {
                //$rule['defaults']['__section_id'] = $this->object->id;
            }

            $rule = Yii::createObject(array_merge($this->ruleConfig, $rule));
            //$rule->init();

            if (!$rule instanceof UrlRuleInterface) {
                throw new InvalidConfigException('URL rule class must implement UrlRuleInterface.');
            }

            $rules[] = $rule;
        }

        return $rules;
    }
}
