<?php

declare(strict_types=1);

namespace Drupal\event_details\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for Event Details routes.
 */
final class EventDetailsController extends ControllerBase {

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
   * Builds the response.
   */
  public function dashboard(): array {

    $eventDetails = $this->getEventDetails();
    $eventDate = $this->getEventDate();
    $eventType = $this->getEventType();
    $build['content'] = [
      '#theme' => 'events_dashboard',
      '#eventDetails' => $eventDetails,
      '#eventDate' => $eventDate,
      '#eventType' => $eventType,
    ];

    return $build;
  }

  /**
   * This function queries the database to calculate the number of events for 
   * each year based on the field 'field_event_date_value'. It returns an array 
   * where the keys are the event years and the values are the counts of events 
   * in those years.
   *
   * @return array
   *   An associative array where the keys are event years and the values are 
   *   the count of events for that year.
   */
  public function getEventDetails() {
    $query = $this->database->select('node__field_event_date', 'n');
    $query->addExpression('YEAR(n.field_event_date_value)', 'event_year');
    $query->addExpression('COUNT(n.entity_id)', 'event_count');
    $query->groupBy('event_year');
    $results = $query->execute()->fetchAllKeyed();
    return $results;
  }

  /**
   * This function queries the database to calculate the number of events for 
   * each quarter of a year based on the field 'field_event_date_value'. It 
   * returns an array where the keys are the event quarters (formatted as 
   * 'YYYY-QX') and the values are the counts of events in those quarters.
   *
   * @return array
   *   An associative array where the keys are event quarters (formatted as 
   *   'YYYY-QX') and the values are the count of events for that quarter.
   */
  public function getEventDate() {

    $query = $this->database->select('node__field_event_date', 'n');
    $query->addExpression("CONCAT(YEAR(n.field_event_date_value), '-Q', QUARTER(n.field_event_date_value))", 'event_quarter');
    $query->addExpression('COUNT(n.entity_id)', 'event_count');
    $query->groupBy('event_quarter');
  
    $results = $query->execute()->fetchAllKeyed();
    
    return $results;
  }
  
  /**
   * This function queries the database to calculate the number of events for 
   * each event type based on the field 'field_event_type_value'. It returns an 
   * array where the keys are the event types and the values are the counts of 
   * events of that type.
   *
   * @return array
   *   An associative array where the keys are event types and the values are 
   *   the count of events for each type.
   */
  public function getEventType(): array {

    $query = $this->database->select('node__field_event_type', 'n');
    $query->addField('n', 'field_event_type_value', 'event_type');
    $query->addExpression('COUNT(n.entity_id)', 'event_count');
    $query->groupBy('event_type');
    $results = $query->execute()->fetchAllKeyed();
    return $results;
  }
}
