<?php

declare(strict_types=1);

namespace Drupal\rgb_field_module\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'Text Formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "text_formatter",
 *   label = @Translation("Text Formatter"),
 *   field_types = {"rgb_field"},
 * )
 */
final class TextFormatter extends FormatterBase {

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
        '#markup' => t('<p style="color: @rgb_value">Hello From RGB</p>', ['@rgb_value'=>$rgb_value]),
      ];
    }

    return $element;
  }

}
