<?php

declare(strict_types=1);

namespace Drupal\event_details\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * Provides a Event Details form.
 */
final class TermDetailsForm extends FormBase {

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Constructs a new TermInfoController object.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'event_details_term_details';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $form['term_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter Term Name'),
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'button',
      '#value' => $this->t('See More'),
      '#ajax' => [
        'callback' => '::showTermDetails',
        'event' => 'click'
      ]
    ];

    $form['message'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'term-details-container'],
    ];

    return $form;
  }

  public function showTermDetails(array &$form, FormStateInterface $form_state) {
    $term_name = $form_state->getValue('term_name');
    $termInfoResult = $this->getTermInfo($term_name);

    if ($termInfoResult) {
      $nodeInforesult = $this->getNodeInfo($termInfoResult['tid']);
      $markup = [
        '#theme' => 'term_dashboard',
        '#termInfo' => $termInfoResult,
        '#nodeInfo' => $nodeInforesult,
      ];
    } 
    else {
      $markup = 'No term found with the given name.';
    }

    $ajaxResponse = new AjaxResponse();
    $ajaxResponse->addCommand(new HtmlCommand('#term-details-container', $markup));
    return $ajaxResponse;
  }

  public function getTerminfo($term_name) {

    $query = $this->database->select('taxonomy_term_field_data', 's');
    $query->join('taxonomy_term_data', 'q', 's.tid = q.tid');
    $query
      ->fields('q', ['tid', 'uuid'])
      ->condition('s.name', $term_name, '=');
    $result = $query->execute()->fetchAssoc();
    return $result;
  }

  public function getNodeInfo($termId) {

    $query1 = $this->database->select('taxonomy_index', 'n');
    $query1->join('node_field_data', 'm', 'n.nid = m.nid');
    $query1
      ->fields('m', ['nid', 'title'])
      ->condition('n.tid', $termId, '=');
    $node_ids = $query1->execute()->fetchAll();
    return $node_ids;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->messenger()->addStatus($this->t('The message has been sent.'));
    $form_state->setRedirect('<front>');
  }

}
