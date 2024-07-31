<?php

namespace Drupal\custom_config_form\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * Configure Custom config form settings for this site.
 */
final class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'custom_config_form_settings';
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
    $config = $this->config('custom_config_form.settings');

    $form['full_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Full Name'),
      '#suffix' => '<div class="error" id="name_err"></div>',
      '#default_value' => $config->get('full_name'),
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::nameCheckError',
        'event' => 'keyup'
      ]
    ];

    $form['phone_number'] = [
      '#type' => 'tel',
      '#title' => $this->t('Phone Number'),
      '#default_value' => $config->get('phone_number'),
      '#required' => TRUE,
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email ID'),
      '#suffix' => '<div class="error" id="email_err"></div>',
      '#default_value' => $config->get('email'),
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::emailCheckError',
        'event' => 'change'
      ]
    ];

    $form['gender'] = [
      '#type' => 'radios',
      '#title' => $this->t('Gender'),
      '#options' => [
        'male' => $this->t('Male'),
        'female' => $this->t('Female'),
        'other' => $this->t('Other'),
      ],
      '#default_value' => $config->get('gender'),
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * This Function checks if the Full name field contains only alphabets or not.
   * 
   * @param array $form
   *  An array contains render output of Form.
   * @param FormStateInterface $form_state
   *  An object which stores current state of Form.
   * 
   * @return object
   *  An object which stores the AjaxResponse object.
   */
  public function nameCheckError(array &$form, FormStateInterface $form_state) {
    $name = $form_state->getValue('full_name');
    $error = '';
    if (!preg_match('/^[a-z A-Z]+$/', $name)) {
      $error = 'It is not a Valid Name Format.';
    }
    $ajaxResponse = new AjaxResponse();
    $ajaxResponse->addCommand(new HtmlCommand('#name_err', $error));
    return $ajaxResponse;
  }

  /**
   * This Function checks if the Email field has any Error or not.
   * 
   * @param array $form
   *  An array contains render output of Form.
   * @param FormStateInterface $form_state
   *  An object which stores current state of Form.
   * 
   * @return object
   *  An object which stores the AjaxResponse object.
   */
  public function emailCheckError(array &$form, FormStateInterface $form_state) {
    $email = $form_state->getValue('email');
    $error = '';
    $public_domains = ['yahoo.com', 'gmail.com', 'outlook.com', 'hotmail.com'];
    $domain = substr(strrchr($email, "@"), 1);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error = 'Please check the Email Format.';
    }
    else if (in_array($domain, $public_domains)) {
      $error = 'Email addresses from public domains are not allowed.';
    }
    $ajaxResponse = new AjaxResponse();
    $ajaxResponse->addCommand(new HtmlCommand('#email_err', $error));
    return $ajaxResponse;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->config('custom_config_form.settings')
      ->set('full_name', $form_state->getValue('full_name'))
      ->set('phone_number', $form_state->getValue('phone_number'))
      ->set('email_id', $form_state->getValue('email_id'))
      ->set('gender', $form_state->getValue('gender'))
      ->save();

      $this->messenger()->addStatus($this->t('Your Name is @name, Your phone number is @number, Your Email is @email, Your Gender is @gender', ['@name' => $form_state->getValue('full_name'),'@number' => $form_state->getValue('phone_number'), '@email' => $form_state->getValue('email_id'), '@gender' => $form_state->getValue('gender')]));
    parent::submitForm($form, $form_state);
  }

}
