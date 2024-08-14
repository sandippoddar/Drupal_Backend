<?php

declare(strict_types=1);

namespace Drupal\rgb_field_module\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Color;

/**
 * Defines the 'pick_widget' field widget.
 *
 * @FieldWidget(
 *   id = "pick_widget",
 *   label = @Translation("Pick Color Widget"),
 *   field_types = {"rgb_field"},
 * )
 */
final class PickWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state): array {
    $element['color_picker'] = [
      '#type' => 'color',
      '#title' => $this->t('Pick a color'),
      '#default_value' => $this->rgbToHex(
        $items[$delta]->get('r')->getValue(),
        $items[$delta]->get('g')->getValue(),
        $items[$delta]->get('b')->getValue()
      ),
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state): array {
    foreach ($values as &$value) {
      if (isset($value['color_picker']) && !empty($value['color_picker'])) {
        // Convert hex to RGB.
        $rgb = Color::hexToRgb($value['color_picker']);
        
        // Map the RGB values to the field type properties.
        $value['r'] = $rgb['red'] ?? 0;
        $value['g'] = $rgb['green'] ?? 0;
        $value['b'] = $rgb['blue'] ?? 0;
  
        // Remove the hex key if not needed.
        unset($value['color_picker']);
      }
      else {
        $form_state->setErrorByName('color_picker', $this->t('Invalid hex color code.'));
      }
    }
  
    return $values;
  }

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
  private function rgbToHex($r, $g, $b) {
    return sprintf('#%02x%02x%02x', $r, $g, $b);
  }

}
