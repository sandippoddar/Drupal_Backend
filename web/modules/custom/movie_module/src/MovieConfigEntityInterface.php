<?php

namespace Drupal\movie_module;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Movie Config Entity entities.
 */
interface MovieConfigEntityInterface extends ConfigEntityInterface {

  /**
   * Gets the year of the movie.
   *
   * @return string
   *   The year of the movie.
   */
  public function getYear(): string;


}
