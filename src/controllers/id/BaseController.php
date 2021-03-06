<?php

namespace craftcom\controllers\id;

use craft\helpers\ArrayHelper;
use craft\helpers\Json;
use craft\web\Controller;
use craftcom\plugins\Plugin;
use GuzzleHttp\Client;
use yii\helpers\Markdown;

/**
 * Class BaseController
 *
 * @property array $apps
 */
abstract class BaseController extends Controller
{
    // Properties
    // =========================================================================

    /**
     * @inheritdoc
     */
    public $enableCsrfValidation = false;

    // Protected Methods
    // =========================================================================

    /**
     * @param Plugin $plugin
     *
     * @return array
     */
    protected function pluginTransformer(Plugin $plugin): array
    {
        $icon = $plugin->getIcon();
        $developer = $plugin->getDeveloper();

        // Screenshots
        $screenshotUrls = [];
        $screenshotIds = [];

        foreach ($plugin->getScreenshots() as $screenshot) {
            $screenshotUrls[] = $screenshot->getUrl().'?'.$screenshot->dateModified->getTimestamp();
            $screenshotIds[] = $screenshot->getId();
        }

        // Last history note
        $lastHistoryNote = null;
        $history = $plugin->getHistory();

        if(count($history) > 0) {
            $lastHistoryNote = $history[0];

            if($lastHistoryNote['devComments']) {
                $lastHistoryNote['devComments'] = Markdown::process($lastHistoryNote['devComments']);
            }
        }

        return [
            'id' => $plugin->id,
            'enabled' => $plugin->enabled,
            'pendingApproval' => $plugin->pendingApproval,
            'status' => $plugin->status,
            'iconId' => $plugin->iconId,
            'iconUrl' => $icon ? $icon->getUrl().'?'.$icon->dateModified->getTimestamp() : null,
            'packageName' => $plugin->packageName,
            'handle' => $plugin->handle,
            'name' => $plugin->name,
            'shortDescription' => $plugin->shortDescription,
            'longDescription' => $plugin->longDescription,
            'documentationUrl' => $plugin->documentationUrl,
            'changelogPath' => $plugin->changelogPath,
            'repository' => $plugin->repository,
            'license' => $plugin->license,
            'price' => $plugin->price,
            'renewalPrice' => $plugin->renewalPrice,
            'keywords' => $plugin->keywords,
            'latestVersion' => $plugin->latestVersion,

            // 'iconUrl' => $iconUrl,
            'developerId' => $developer->id,
            'developerName' => $developer->getDeveloperName(),
            'developerUrl' => $developer->developerUrl,

            'screenshotUrls' => $screenshotUrls,
            'screenshotIds' => $screenshotIds,
            'categoryIds' => ArrayHelper::getColumn($plugin->getCategories(), 'id'),

            'lastHistoryNote' => $lastHistoryNote
        ];
    }
}
