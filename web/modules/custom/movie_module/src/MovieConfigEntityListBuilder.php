<?php

namespace Drupal\movie_module;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of movie config entities.
 */
final class MovieConfigEntityListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader(): array {
    $header['movie_name'] = $this->t('Movie Name');
    $header['year'] = $this->t('Year');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity): array {
    /** @var \Drupal\movie_module\MovieConfigEntityInterface $entity */
    $row['movie_name'] = $entity->label();
    $row['year'] = $entity->getYear();
    return $row + parent::buildRow($entity);
  }

}
