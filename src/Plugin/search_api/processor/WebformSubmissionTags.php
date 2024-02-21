<?php

namespace Drupal\webform_submission_search_api\Plugin\search_api\processor;

use Drupal\search_api\Datasource\DatasourceInterface;
use Drupal\search_api\Item\ItemInterface;
use Drupal\search_api\Processor\ProcessorPluginBase;
use Drupal\search_api\Processor\ProcessorProperty;

/**
 * Search API Processor for indexing Webform Submission Taxonomy Entity Reference field.
 *
 * @SearchApiProcessor(
 *   id = "webform_submission_tags",
 *   label = @Translation("Tags field of Webform Submission"),
 *   description = @Translation("Switching on will enable indexing the tags field of a Webform Submission"),
 *   stages = {
 *     "add_properties" = 0,
 *   },
 *   locked = true,
 *   hidden = true,
 * )
 */
class WebformSubmissionTags extends ProcessorPluginBase {

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions(DatasourceInterface $datasource = NULL) {
    $properties = [];

    if (!$datasource) {
      $definition = [
        'label' => $this->t('Tags'),
        'description' => $this->t('Tags field for Webform Submission.'),
        'type' => 'string',
        'is_list' => TRUE,
        'processor_id' => $this->getPluginId(),
      ];
      $properties['search_api_webform_submission_tags'] = new ProcessorProperty($definition);

    }
    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function addFieldValues(ItemInterface $item) {
    $webform_submission = $item->getOriginalObject()->getValue();
    $tags = $webform_submission->getElementData('tags');

    if ($tags) {
      $fields = $this->getFieldsHelper()
        ->filterForPropertyPath($item->getFields(), NULL, 'search_api_webform_submission_tags');
      foreach ($fields as $field) {
        foreach ($tags as $tag) {
          // Get the tag name from taxonomy term id.
          $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($tag);
          $field->addValue($term->getName());
        }
      }
    }
  }

}
