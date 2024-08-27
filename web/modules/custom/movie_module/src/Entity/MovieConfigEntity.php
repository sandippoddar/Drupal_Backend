<?php

namespace Drupal\movie_module\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\movie_module\MovieConfigEntityInterface;

/**
 * Defines the Movie Config Entity entity.
 *
 * @ConfigEntityType(
 *   id = "movie_config_entity",
 *   label = @Translation("Movie Config Entity"),
 *   handlers = {
 *     "list_builder" = "Drupal\movie_module\MovieConfigEntityListBuilder",
 *     "form" = {
 *       "add" = "Drupal\movie_module\Form\MovieConfigEntityForm",
 *       "edit" = "Drupal\movie_module\Form\MovieConfigEntityForm",
 *       "delete" = "Drupal\movie_module\Form\MovieConfigEntityDeleteForm"
 *     }
 *   },
 *   config_prefix = "movie_config_entity",
 *   admin_permission = "administer movie config entity",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "year" = "year",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "year",
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/movie_config_entity/add",
 *     "edit-form" = "/admin/structure/movie_config_entity/{movie_config_entity}/edit",
 *     "delete-form" = "/admin/structure/movie_config_entity/{movie_config_entity}/delete",
 *     "collection" = "/admin/structure/movie_config_entity"
 *   }
 * )
 */
class MovieConfigEntity extends ConfigEntityBase implements MovieConfigEntityInterface {

  /**
   * The Example ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Example label.
   *
   * @var string
   */
  protected $label; 

  /**
   * The year of the movie.
   *
   * @var string
   */
  protected string $year = '';

  /**
   * Gets the year of the movie.
   *
   * @return string
   *   The year of the movie.
   */
  public function getYear(): string {
    return $this->year;
  }

}
