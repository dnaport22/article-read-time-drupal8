<?php
namespace Drupal\art\Plugin\Field\FieldWidget;

use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Field\WidgetInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FieldItemListInterface;
/**
 * Plugin implementation of the 'field_example_text' widget.
 *
 * @FieldWidget(
 *   id = "art",
 *   label = @Translation("Display article read time"),
 *   field_types = {
 *     "art"
 *   }
 * )
 */
class artWidget extends WidgetBase {

    /**
     * {@inheritdoc}
     */
    public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

        $element['wpm'] = array(
            '#type' => 'number',
            '#title' => t('WPM'),
            '#description' => t('Average number of words read per minute.'),
            '#default_value' => isset($items[$delta]->wpm) ? $items[$delta]->wpm : 225,
        );
        $element['image_time'] = array(
            '#type' => 'number',
            '#title' => t('IPM'),
            '#description' => t('Average time spent on an image (in seconds).'),
            '#default_value' => isset($items[$delta]->image_time) ? $items[$delta]->image_time : 12,
        );
        $element['time_format'] = array(
            '#type' => 'select',
            '#title' => t('Format'),
            '#description' => t('How the calculation will be formatted.'),
            '#options' => array(
                'hour_short' => t('Hours & minutes, short (1 hr, 5 mins)'),
                'hour_long' => t('Hours & minutes, long (1 hour, 5 minutes)'),
                'min_short' => t('Minutes, short (65 mins)'),
                'min_long' => t('Minutes, long (65 minutes)'),
            ),
            '#default_value' => isset($items[$delta]->time_format) ? $items[$delta]->time_format : 'hour_short',
        );
        if($this->fieldDefinition->getFieldStorageDefinition()->getCardinality() == 1){
            $element += array(
                '#type' => 'fieldset',
                '#attributes' => array('class' => array('container-inline')),
            );
        }
        return $element;
    }



}