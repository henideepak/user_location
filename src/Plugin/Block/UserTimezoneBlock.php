<?php

namespace Drupal\user_location\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\user_location\Services\UserLocationServiceInterface;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a 'UserTimezoneBlock' block.
 *
 * @Block(
 *  id = "user_timezone_block",
 *  admin_label = @Translation("User timezone block"),
 * )
 */
class UserTimezoneBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\Core\Config\ConfigFactoryInterface definition.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Drupal\user_location\Services\UserLocationServiceInterface definition.
   *
   * @var \Drupal\user_location\Services\UserLocationServiceInterface
   */
  protected $userLocationTimezone;

  /**
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Drupal\user_location\Services\UserLocationServiceInterface $userLocationTimezone
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, UserLocationServiceInterface $userLocationTimezone, ConfigFactoryInterface $configFactory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->userLocationTimezone = $userLocationTimezone;
    $this->configFactory = $configFactory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration, 
      $plugin_id, 
      $plugin_definition,
      $container->get('user_location.timezone'),
      $container->get('config.factory')
    );
  }

  /**
   * @return int
   */
  public function getCacheMaxAge() {
    return 0;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $config = $this->configFactory->get('user_location.adminconfig');

    $build = [];
    $build['#theme'] = 'user_timezone_block';
    $build['#content']['#city'] = $config->get('city');
    $build['#content']['#country'] = $config->get('country');
    $build['#content']['#timezone'] = $this->userLocationTimezone->getTimezone();
    $this->messenger()->addStatus('Thanks Regards Deepak Bhati.');

    return $build;
  }

}
