<?php

declare(strict_types=1);

namespace Drupal\rgb_field_module\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines the 'rgb_widget' field widget.
 *
 * @FieldWidget(
 *   id = "rgb_widget",
 *   label = @Translation("RGB field widget"),
 *   field_types = {"rgb_field"},
 * )
 */
final class RgbWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state): array {
    $element['r'] = [
      '#type' => 'number',
      '#title' => t('Red'),
      '#default_value' => $items[$delta]->r ?? NULL,
      '#min' => 0,
      '#max' => 255,
    ];
    $element['g'] = [
      '#type' => 'number',
      '#title' => t('Green'),
      '#default_value' => $items[$delta]->g ?? NULL,
      '#min' => 0,
      '#max' => 255,
    ];
    $element['b'] = [
      '#type' => 'number',
      '#title' => t('Blue'),
      '#default_value' => $items[$delta]->b ?? NULL,
      '#min' => 0,
      '#max' => 255,
    ];
    return $element;
  }

}
