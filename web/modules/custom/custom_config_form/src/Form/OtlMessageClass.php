<?php

declare(strict_types=1);

namespace Drupal\custom_config_form\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * Configure Custom config form settings for this site.
 */
final class OtlMessageClass extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'custom_config_form_otl_message_class';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['custom_config_form.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['message'] = [
      '#type' => 'markup',
      '#markup' => '<div id="message"></div>',
    ];
    $form['user_id'] = [
      '#type' => 'number',
      '#title' => $this->t('Enter User Id'),
      '#suffix' => '<div class="error" id="id_err"></div>',
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::idCheck',
        'event' => 'keyup'
      ]
    ];
    $form['getOtl'] = [
      '#type' => 'button',
      '#value' => $this->t('Get OTL'),
      '#ajax' => [
        'callback' => '::showOtl',
        'event' => 'click',
      ],
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * This Function returns One Time Login Link of User.
   * 
   * @param array $form
   *  An array contains render output of Form.
   * @param FormStateInterface $form_state
   *  An object which stores current state of Form.
   * 
   * @return string
   *  This string contains the User Reset Password Link.
   */
  public function generateOtl(array &$form, FormStateInterface $form_state) {
    $userId = $form_state->getValue('user_id');
    $user = User::load($userId);
    $message = '';

    if ($userId && $user) {
      $otll = user_pass_reset_url($user);
      $message = $this->t('One-time login link: <a href=":link">:link</a>', [':link' => $otll]);
    } 
    else {
      $message = $this->t('Invalid user ID.');
    }
    return $message;
  }

  /**
   * This Function is Use to Check if the User Id is only Number or not.
   * 
   * @param array $form
   *  An array contains render output of Form.
   * @param FormStateInterface $form_state
   *  An object which stores current state of Form.
   * 
   * @return object
   *  An object which stores the AjaxResponse object.
   */
  public function idCheck(array &$form, FormStateInterface $form_state) {
    $id = $form_state->getValue('user_id');
    $error = '';
    if (!preg_match('/^[0-9]+$/', $id)) {
      $error = 'It is not a Valid Format.';
    }
    $ajaxResponse = new AjaxResponse();
    $ajaxResponse->addCommand(new HtmlCommand('#id_err', $error));
    return $ajaxResponse;
  }

  /**
   * This Function implements to show One time Login Link of User.
   * 
   * @param array $form
   *  An array contains render output of Form.
   * @param FormStateInterface $form_state
   *  An object which stores current state of Form.
   * 
   * @return object
   *  An object which stores the AjaxResponse object.
   */
  public function showOtl(array &$form, FormStateInterface $form_state) {
    $ajaxResponse = new AjaxResponse();
    $message = $this->generateOtl($form, $form_state);
    $ajaxResponse->addCommand(new HtmlCommand('#message', $message));
    return $ajaxResponse;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->config('custom_config_form.settings')
      ->set('example', $form_state->getValue('example'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
