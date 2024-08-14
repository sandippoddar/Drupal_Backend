<?php

declare(strict_types=1);

namespace Drupal\rgb_field_module\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Color;

/**
 * Defines the 'hex_widget' field widget.
 *
 * @FieldWidget(
 *   id = "hex_widget",
 *   label = @Translation("Hex Field Widget"),
 *   field_types = {"rgb_field"},
 * )
 */
final class HexWidget extends WidgetBase {

 /**
 * This function takes the red, green, and blue components of a color,
 * each ranging from 0 to 255, and converts them into a single hexadecimal
 * color code string in the format "#RRGGBB".
 *
 * @param int $r
 *   The red component of the color, ranging from 0 to 255.
 * @param int $g
 *   The green component of the color, ranging from 0 to 255.
 * @param int $b
 *   The blue component of the color, ranging from 0 to 255.
 *
 * @return string
 *   The hexadecimal color code in the format "#RRGGBB".
 */
  private function rgbToHex($r, $g, $b): string {
    return sprintf("#%02x%02x%02x", $r, $g, $b);
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state): array {
    // Retrieve RGB values
    $r = $items[$delta]->get('r')->getValue();
    $g = $items[$delta]->get('g')->getValue();
    $b = $items[$delta]->get('b')->getValue();

    // Convert RGB values to HEX
    $hex = $this->rgbToHex($r, $g, $b);

    // Define the form element
    $element['hex'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Hex color code'),
        '#default_value' => $hex,
        '#description' => $this->t('Enter the color in hex format (#RRGGBB).'),
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state): array {
    foreach ($values as &$value) {
      if (isset($value['hex']) && !empty($value['hex']) && $this->validateHexCode($value['hex'])) {
        // Convert hex to RGB.
        $rgb = Color::hexToRgb($value['hex']);
        
        // Map the RGB values to the field type properties.
        $value['r'] = $rgb['red'] ?? 0;
        $value['g'] = $rgb['green'] ?? 0;
        $value['b'] = $rgb['blue'] ?? 0;
  
        // Remove the hex key if not needed.
        unset($value['hex']);
      }
      else {
        $form_state->setErrorByName('hex', $this->t('Invalid hex color code.'));
      }
    }
  
    return $values;
  }

/**
 * This function checks if a given string is a valid hexadecimal color code 
 * in the format "#RRGGBB", where R, G, and B are hexadecimal digits.
 *
 * @param string $hex
 *   The hexadecimal color code to validate, expected in the format "#RRGGBB".
 *
 * @return bool
 *   TRUE if the hex code is valid, FALSE otherwise.
 */
  private function validateHexCode($hex): bool {
    return preg_match('/^#[0-9A-Fa-f]{6}$/', $hex) === 1;
  }
}
