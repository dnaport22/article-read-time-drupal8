<?php
/**
 * @file
 * Contains \Drupal\module_name\Plugin\field\field_type\Person.
 */

namespace Drupal\art\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'field_example_rgb' field type.
 *
 * @FieldType(
 *   id = "art",
 *   label = @Translation("Article read time"),
 *   module = "art",
 *   description = @Translation("Display the time it will take to read an article on your website."),
 *   default_widget = "art",
 *   default_formatter = "art"
 * )
 */
class artField extends FieldItemBase {

    public static function schema(FieldStorageDefinitionInterface $field_definition)
    {
        return array(
            'columns' => array(
                'wpm' => array(
                    'type' => 'int',
                    'unsigned' => TRUE,
                    'not null' => FALSE,
                ),
                'image_time' => array(
                    'type' => 'int',
                    'unsigned' => TRUE,
                    'not null' => FALSE,
                ),
                'time_format' => array(
                    'type' => 'text',
                    'length' => 20,
                    'not null' => FALSE,
                ),
            ),
        );
    }
    public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition)
    {
       $properties['wpm'] = DataDefinition::create('integer')
           ->setLabel(t('wpm'))
           ->setDescription(t('Words per minute'));
        $properties['image_time'] = DataDefinition::create('integer')
            ->setLabel(t('image_time'))
            ->setDescription(t('Time per image'));
        $properties['time_format'] = DataDefinition::create('string')
            ->setLabel(t('time_format'))
            ->setDescription(t('Time format for displaying read time'));
        return $properties;
    }

}