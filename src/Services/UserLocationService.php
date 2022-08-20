<?php

namespace Drupal\user_location\Services;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DrupalDateTime;


/**
 * Class UserLocationService.
 */
class UserLocationService implements UserLocationServiceInterface {

  /**
   * Drupal\Core\Config\ConfigFactoryInterface definition.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a new UserLocationService object.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }

  /**
   * GetTimezone.
   */
  public function getTimezone() {

    // Get timezone configurations location.
    $timezone = $this->configFactory->get('user_location.adminconfig')
        ->get('timezone');

    if (!isset($timezone) && empty($timezone)) {
      return false;
    }
        
    $zone = new \DateTimezone($timezone);
    return DrupalDateTime::createFromTimestamp(time())
        ->setTimezone($zone)
        ->format('dS M Y - H:i:s A');
  }

}
