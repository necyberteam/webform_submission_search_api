<?php

namespace Drupal\webform_submission_search_api\Plugin\search_api\processor;

use Drupal\search_api\Datasource\DatasourceInterface;
use Drupal\search_api\Item\ItemInterface;
use Drupal\search_api\Processor\ProcessorPluginBase;
use Drupal\search_api\Processor\ProcessorProperty;

/**
 * Search API Processor for Event Instance Domain Access field.
 *
 * @SearchApiProcessor(
 *   id = "eventinstance_domain",
 *   label = @Translation("Domain field of Event Instance"),
 *   description = @Translation("Switching on will enable indexing the Domain Access field of an Event Instance"),
 *   stages = {
 *     "add_properties" = 0,
 *   },
 *   locked = true,
 *   hidden = true,
 * )
 */
class EventInstanceDomain extends ProcessorPluginBase {

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions(?DatasourceInterface $datasource = NULL) {
    $properties = [];

    if (!$datasource) {
      $definition = [
        'label' => $this->t('Domain Access'),
        'description' => $this->t('Domain Access field for Event Instance.'),
        'type' => 'string',
        'is_list' => TRUE,
        'processor_id' => $this->getPluginId(),
      ];
      $properties['search_api_eventinstance_domain'] = new ProcessorProperty($definition);

    }
    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function addFieldValues(ItemInterface $item) {
    $entity = $item->getOriginalObject()->getValue();
    
    // Only process eventinstance entities
    if ($entity->getEntityTypeId() !== 'eventinstance') {
      return;
    }
    
    $eventinstance = $entity;
    $domain_access = $eventinstance->get('domain_access')->getValue();

    if ($domain_access) {
      $fields = $this->getFieldsHelper()
        ->filterForPropertyPath($item->getFields(), NULL, 'search_api_eventinstance_domain');
      foreach ($fields as $field) {
        foreach ($domain_access as $domain) {
          if (isset($domain['target_id'])) {
            $field->addValue($domain['target_id']);
          }
        }
      }
    }
  }

}