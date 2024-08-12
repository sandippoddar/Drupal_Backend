<?php

namespace Drupal\sample_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Database\Connection;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Provides a 'DisplayFormDataBlock' block.
 *
 */
#[Block(
  id: "display_form_data_block",
  admin_label: new TranslatableMarkup("Display Form Data Block"),
)]
 
class FormDataBlock extends BlockBase implements ContainerFactoryPluginInterface {

  protected $database;

   /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   *
   * @return static
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('database')
    );
  }

  /**
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param  $database
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, Connection $database) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->database = $database;
  }

  /** 
   * {@inheritdoc}
   */
  public function build() {
    $query = $this->database->select('sample_block_data', 's')
      ->fields('s', ['group_name', 'label_1_name', 'label_1_value', 'label_2_name', 'label_2_value']);
    $results = $query->execute()->fetchAll();
    return [
      '#theme' => 'my_template',
      '#rows' => $results,
      '#attached' => [
        'library' => [
          'sample_block/custom',
        ],
      ],
      '#cache' => [
        'tags' => ['sample_block_data'],
        'contexts' => ['url.path']
      ]
    ];
  }
}
