<?php

declare(strict_types=1);

namespace Drupal\rgb_field_module\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Defines the 'rgb_field' field type.
 *
 * @FieldType(
 *   id = "rgb_field",
 *   label = @Translation("RGB Color Code"),
 *   description = @Translation("Stores RGB color values."),
 *   default_widget = "rgb_widget",
 *   default_formatter = "rgb_formatter",
 * )
 */
final class RgbFieldItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public function isEmpty(): bool {
    return $this->get('r')->getValue() === NULL || $this->get('g')->getValue() === NULL || $this->get('b')->getValue() === NULL;
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition): array {
    $properties['r'] = DataDefinition::create('integer')
      ->setLabel(t('Red'))
      ->setRequired(TRUE);

    $properties['g'] = DataDefinition::create('integer')
      ->setLabel(t('Green'))
      ->setRequired(TRUE);

    $properties['b'] = DataDefinition::create('integer')
      ->setLabel(t('Blue'))
      ->setRequired(TRUE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition): array {
    $columns = [
      'r' => [
        'type' => 'int',
        'not null' => FALSE,
        'description' => 'Red value (0-255)',
        'size' => 'small',
      ],
      'g' => [
        'type' => 'int',
        'not null' => FALSE,
        'description' => 'Green value (0-255)',
        'size' => 'small',
      ],
      'b' => [
        'type' => 'int',
        'not null' => FALSE,
        'description' => 'Blue value (0-255)',
        'size' => 'small',
      ],
    ];

    return [
      'columns' => $columns,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function generateSampleValue(FieldDefinitionInterface $field_definition): array {
    return [
      'r' => mt_rand(0, 255),
      'g' => mt_rand(0, 255),
      'b' => mt_rand(0, 255),
    ];
  }
}
