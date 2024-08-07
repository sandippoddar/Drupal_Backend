<?php

declare(strict_types=1);

namespace Drupal\sample_block\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Cache\Cache;

/**
 * Provides a Sample Block form.
 */
final class BlockForm extends FormBase {

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Constructs a new CheckTableController object.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'sample_block_block';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $form['#tree'] = TRUE;

    if ($form_state->get('num_groups') === NULL) {
      $form_state->set('num_groups', 1);
    }

    $num_groups = $form_state->get('num_groups');

    for ($i = 0; $i < $num_groups; $i++) {
      $form['groups'][$i] = [
        'group_name' => [
          '#type' => 'textfield',
          '#title' => $this->t('Name of the group'),
          '#required' => TRUE,
        ],
        'label_1_name' => [                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
          '#type' => 'textfield',
          '#title' => $this->t('Name of the 1st label'),
          '#required' => TRUE,
        ],
        'label_1_value' => [
          '#type' => 'textfield',
          '#title' => $this->t('Value of the 1st label'),
          '#required' => TRUE,
        ],
        'label_2_name' => [
          '#type' => 'textfield',
          '#title' => $this->t('Name of the 2nd label'),
          '#required' => TRUE,
        ],
        'label_2_value' => [
          '#type' => 'textfield',
          '#title' => $this->t('Value of the 2nd label'),
          '#required' => TRUE,                                                                                                                                  
        ],                                                                                        
        'remove' => [
          '#type' => 'submit',
          '#value' => $this->t('Remove'),
          '#submit' => ['::removeCallback'],
          '#limit_validation_errors' => [],
          '#attributes' => ['class' => ['remove-button']],
          '#name' => 'remove-' . $i,
        ],
      ];
    }

    $form['add_more'] = [
      '#type' => 'button',
      '#value' => $this->t('Add more'),
      '#ajax' => [
        'callback' => '::addMoreCallback',
        'event' => 'click',
        
      ],
      '#limit_validation_errors' => [],
      '#attributes' => ['class' => ['add-more-button']],
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];
    return $form;
  }
  
  /**
   * This Function implements to Add more group fields.
   * 
   * @param array $form
   *  This Array Stores the Renderable array of Form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *  Stores Current state of the Form.
   * 
   * @return mixed
   */
  public function addMoreCallback(array &$form, FormStateInterface $form_state) {
    // $response = new AjaxResponse();
    $num_groups = $form_state->get('num_groups');
    $num_groups++;
    $form_state->set('num_groups', $num_groups);
    $form_state->setRebuild();
    return $form['groups'];
  }

  /**
   * This Function implements to remove group fields.
   * 
   * @param array $form
   *  This Array Stores the Renderable array of Form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *  Stores Current state of the Form.
   * 
   * @return mixed
   */
  public function removeCallback(array &$form, FormStateInterface $form_state) {
    $triggering_element = $form_state->getTriggeringElement();
    $remove_index = str_replace('remove-', '', $triggering_element['#name']);
    $num_groups = $form_state->get('num_groups');
    $num_groups--;
    $form_state->set('num_groups', $num_groups);

    $form_state->setRebuild(TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    // @todo Validate the form here.
    // Example:
    // @code
    //   if (mb_strlen($form_state->getValue('message')) < 10) {
    //     $form_state->setErrorByName(
    //       'message',
    //       $this->t('Message should be at least 10 characters.'),
    //     );
    //   }
    // @endcode
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValue('groups');

    foreach ($values as $value) {
      $this->database->insert('sample_block_data')
        ->fields([
          'group_name' => $value['group_name'],
          'label_1_name' => $value['label_1_name'],
          'label_1_value' => $value['label_1_value'],
          'label_2_name' => $value['label_2_name'],
          'label_2_value' => $value['label_2_value'],
        ])
        ->execute();
    }
    Cache::invalidateTags(['sample_block_data']);
  }

}
