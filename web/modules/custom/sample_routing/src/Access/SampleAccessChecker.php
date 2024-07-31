<?php

declare(strict_types=1);

namespace Drupal\sample_routing\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Session\AccountInterface;

class SampleAccessChecker implements AccessInterface {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * Constructs a new CustomAccessCheck object.
   *
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   */
  public function __construct(AccountInterface $current_user) {
    $this->currentUser = $current_user;
  }

  /**
   * Checks access for the custom route.
   *
   * @return \Drupal\Core\Access\AccessResult
   *   The access result.
   */
  public function access() {
    $role = $this->currentUser->getRoles();
    // Custom logic to determine if the user can view the page.
    if ($this->currentUser->hasPermission('access the custom page') && $role != 'content_editor') {
      return AccessResult::allowed();
    }

    // If the user does not have the required permission, deny access.
    return AccessResult::forbidden();
  }

}
