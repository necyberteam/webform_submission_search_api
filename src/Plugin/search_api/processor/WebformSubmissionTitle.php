<?php

namespace Drupal\webform_submission_search_api\Plugin\search_api\processor;

use Drupal\search_api\Datasource\DatasourceInterface;
use Drupal\search_api\Item\ItemInterface;
use Drupal\search_api\Processor\ProcessorPluginBase;
use Drupal\search_api\Processor\ProcessorProperty;

/**
 * Search API Processor for indexing Webform Submission Title field.
 *
 * @SearchApiProcessor(
 *   id = "webform_submission_title",
 *   label = @Translation("Title field of Webform Submission"),
 *   description = @Translation("Switching on will enable indexing the title field of a Webform Submission"),
 *   stages = {
 *     "add_properties" = 0,
 *   },
 *   locked = true,
 *   hidden = true,
 * )
 */
class WebformSubmissionTitle extends ProcessorPluginBase {

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions(DatasourceInterface $datasource = NULL) {
    $properties = [];

    if (!$datasource) {
      $definition = [
        'label' => $this->t('Submission Title'),
        'description' => $this->t('Title field for Webform Submission.'),
        'type' => 'string',
        'processor_id' => $this->getPluginId(),
      ];
      $properties['search_api_webform_submission_title'] = new ProcessorProperty($definition);

    }
    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function addFieldValues(ItemInterface $item) {
    $webform_submission = $item->getOriginalObject()->getValue();
    $title = $webform_submission->getElementData('title');

    if ($title) {
      $fields = $this->getFieldsHelper()
        ->filterForPropertyPath($item->getFields(), NULL, 'search_api_webform_submission_title');
      foreach ($fields as $field) {
        $field->addValue($title);
      }
    }
  }

}
