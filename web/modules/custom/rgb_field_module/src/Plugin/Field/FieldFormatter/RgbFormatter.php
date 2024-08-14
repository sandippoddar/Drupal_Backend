<?php

declare(strict_types=1);

namespace Drupal\rgb_field_module\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'RGB Formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "rgb_formatter",
 *   label = @Translation("RGB Formatter"),
 *   field_types = {"rgb_field"},
 * )
 */
final class RgbFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode): array {
    $element = [];

    foreach ($items as $delta => $item) {
      // Get the RGB values
      $r = $item->r ?? 0;
      $g = $item->g ?? 0;
      $b = $item->b ?? 0;

      // Create the RGB string
      $rgb_value = "rgb($r, $g, $b)";

      // Render the color box
      $element[$delta] = [
        '#markup' => t('<div style="padding:50px; background-color:@rgb_value;">Hello From RGB</div>', ['@rgb_value'=>$rgb_value]),
      ];
    }

    return $element;
  }

}
