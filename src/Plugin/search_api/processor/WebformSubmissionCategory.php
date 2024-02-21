<?php

namespace Drupal\webform_submission_search_api\Plugin\search_api\processor;

use Drupal\search_api\Datasource\DatasourceInterface;
use Drupal\search_api\Item\ItemInterface;
use Drupal\search_api\Processor\ProcessorPluginBase;
use Drupal\search_api\Processor\ProcessorProperty;

/**
 * Search API Processor for indexing Webform Submission Category field.
 *
 * @SearchApiProcessor(
 *   id = "webform_submission_category",
 *   label = @Translation("Category field of Webform Submission"),
 *   description = @Translation("Switching on will enable indexing the category field of a Webform Submission"),
 *   stages = {
 *     "add_properties" = 0,
 *   },
 *   locked = true,
 *   hidden = true,
 * )
 */
class WebformSubmissionCategory extends ProcessorPluginBase {

  const FIELD_NAME = 'search_api_webform_submission_category';

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions(DatasourceInterface $datasource = NULL) {
    $properties = [];

    if (!$datasource) {
      $definition = [
        'label' => $this->t('Category'),
        'description' => $this->t('Category field for Webform Submission.'),
        'type' => 'string',
        'processor_id' => $this->getPluginId(),
      ];
      $properties[self::FIELD_NAME] = new ProcessorProperty($definition);

    }
    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function addFieldValues(ItemInterface $item) {
    $webform_submission = $item->getOriginalObject()->getValue();
    $value = $webform_submission->getElementData('category');

    if ($value) {
      $fields = $this->getFieldsHelper()
        ->filterForPropertyPath($item->getFields(), NULL, self::FIELD_NAME);
      foreach ($fields as $field) {
        $field->addValue($value);
      }
    }
  }

}
