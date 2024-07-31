<?php

namespace Drupal\sample_routing\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;

/**
 * This class represents to show Hello User message. 
 */
class SampleRoutingController extends ControllerBase
{

  /**
   * @var \Drupal\Core\Session\AccountInterface $account
   *  Stores Metadata of AccountInterface.
   */
  protected $account;

  /**
   * This Constructor initialize the object of AccountInterface to class member.
   * 
   * @param \Drupal\Core\Session\AccountInterface $account
   */
  public function __construct(AccountInterface $account) {
    $this->account = $account;
  }

  /**
   * This Function returns an Render array. 
   * 
   * @return array
   *  An array which stores the Display Markup.
   */
  public function content()
  {
    if ($this->account->hasPermission('access the custom page:')) {
      return [
        '#markup' => 'Hello ',
      ];
    }
  }
}
