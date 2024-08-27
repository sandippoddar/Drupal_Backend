<?php

declare(strict_types=1);

namespace Drupal\movie_module\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Cache\Cache;

/**
 * Configure Movie Module settings for this site.
 */
final class BudgetForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'movie_module_budget';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['movie_module.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['budget'] = [
      '#type' => 'number',
      '#title' => $this->t('Movie Budget'),
      '#default_value' => $this->config('movie_module.settings')->get('budget'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->config('movie_module.settings')
      ->set('budget', $form_state->getValue('budget'))
      ->save();
    parent::submitForm($form, $form_state);
    Cache::invalidateTags(['message_data']);
  }

}
