<?php

namespace Drupal\webform_submission_search_api;

use Drupal\flag\FlaggingInterface;
use Drupal\search_api\Plugin\search_api\datasource\ContentEntity;

/**
 * Class WebformSubmissionSearchApiReindexService.
 */
class WebformSubmissionSearchApiReindexService implements WebformSubmissionSearchApiReindexServiceInterface {

  /**
   * Reindex Item.
   *
   * @param \Drupal\flag\FlaggingInterface $entity
   *   Flagging.
   */
  public function reindexItem(FlaggingInterface $entity) {
    $datasource_id = 'entity:' . $entity->getFlaggableType();
    /** @var \Drupal\Core\Entity\ContentEntityInterface $content_flagged */
    $content_flagged = $entity->getFlaggable();
    $indexes = ContentEntity::getIndexesForEntity($content_flagged);

    $entity_id = $entity->getFlaggableId();

    $updated_item_ids = $content_flagged->getTranslationLanguages();
    foreach ($updated_item_ids as $langcode => $language) {
      $inserted_item_ids[] = $langcode;
    }
    $combine_id = function ($langcode) use ($entity_id) {
      return $entity_id . ':' . $langcode;
    };
    $updated_item_ids = array_map($combine_id, array_keys($updated_item_ids));
    foreach ($indexes as $index) {
      if ($updated_item_ids) {
        $index->trackItemsUpdated($datasource_id, $updated_item_ids);
      }
    }
  }

}
