<?php

namespace modules\test;

use Craft;
use craft\elements\Entry;
use yii\base\Module as BaseModule;

/**
 * test module
 *
 * @method static Module getInstance()
 */
class Module extends BaseModule
{
    public function init(): void
    {
        Craft::setAlias('@modules/test', __DIR__);

        // Set the controllerNamespace based on whether this is a console or web request
        if (Craft::$app->request->isConsoleRequest) {
            $this->controllerNamespace = 'modules\\test\\console\\controllers';
        } else {
            $this->controllerNamespace = 'modules\\test\\controllers';
        }

        parent::init();

        if (Craft::$app->getRequest()->getIsSiteRequest()) {
            $view = Craft::$app->getView();

            $view->registerTwigExtension(new TestExtension());
            $view->hook('page-translations', $this->pageTranslations(...));
        }

        // Defer most setup tasks until Craft is fully initialized
        Craft::$app->onInit(function() {
            $this->attachEventHandlers();
            // ...
        });
    }

    private function attachEventHandlers(): void
    {
        // Register event handlers here ...
        // (see https://craftcms.com/docs/4.x/extend/events.html to get started)
    }

    private function pageTranslations(array &$content)
    {
        $entry = $content['entry'];
        $siteGroup = $entry->getSite()->groupId;
        $allSiblings = Entry::find()
                            ->id($entry->id)
                            ->siteId(['not', $entry->siteId])
                            ->all();
        $siteGroupSiblings = array_filter($allSiblings, function($item) use ($siteGroup) {
            return $item->getSite()->groupId === $siteGroup;
        });


        return Craft::$app->getView()->renderTemplate('language-switcher', ['entries' => $siteGroupSiblings]);
    }
}
