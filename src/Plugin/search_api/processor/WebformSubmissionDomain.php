<?php

namespace Drupal\webform_submission_search_api\Plugin\search_api\processor;

use Drupal\search_api\Datasource\DatasourceInterface;
use Drupal\search_api\Item\ItemInterface;
use Drupal\search_api\Processor\ProcessorPluginBase;
use Drupal\search_api\Processor\ProcessorProperty;

/**
 * Search API Processor for Webform Submission Taxonomy Entity Reference field.
 *
 * @SearchApiProcessor(
 *   id = "webform_submission_domain",
 *   label = @Translation("Domain field of Webform Submission"),
 *   description = @Translation("Switching on will enable indexing the Domain field of a Webform Submission"),
 *   stages = {
 *     "add_properties" = 0,
 *   },
 *   locked = true,
 *   hidden = true,
 * )
 */
class WebformSubmissionDomain extends ProcessorPluginBase {

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions(?DatasourceInterface $datasource = NULL) {
    $properties = [];

    if (!$datasource) {
      $definition = [
        'label' => $this->t('Domain'),
        'description' => $this->t('Domain field for Webform Submission.'),
        'type' => 'string',
        'is_list' => TRUE,
        'processor_id' => $this->getPluginId(),
      ];
      $properties['search_api_webform_submission_domain'] = new ProcessorProperty($definition);

    }
    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function addFieldValues(ItemInterface $item) {
    $entity = $item->getOriginalObject()->getValue();
    
    // Only process webform submission entities
    if (!$entity instanceof \Drupal\webform\WebformSubmissionInterface) {
      return;
    }
    
    $webform_submission = $entity;
    $tags = $webform_submission->getElementData('domain');

    if ($tags) {
      $fields = $this->getFieldsHelper()
        ->filterForPropertyPath($item->getFields(), NULL, 'search_api_webform_submission_domain');
      foreach ($fields as $field) {
        foreach ($tags as $tag) {
          // Get the tag name from taxonomy term id.
          $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($tag);
          if ($term) {
            $field->addValue($term->get('field_region_connected_domain')->value);
          }
        }
      }
    }
  }

}
