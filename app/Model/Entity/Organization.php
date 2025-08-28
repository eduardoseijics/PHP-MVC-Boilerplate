<?php

namespace App\Model\Entity;

class Organization {
  /**
   * @var integer
   */
  private $id = 1;

  /**
   * @var string
   */
  private $name = 'Canal WDEV';

  /**
   * @var string
   */
  private $site = 'https://youtube.com.br/wdevoficial';
  /**
   * @var string
   */
  private $description = 'Um canal sobre desenvolvimento web.';

  // Getters and Setters for each property
  public function getId() {
    return $this->id;
  }

  public function getName() {
    return $this->name;
  }

  /**
   * Set name
   * @return Organization
   */
  public function setName($name) {
    $this->name = $name;
    return $this;
  }

  /**
   * Get description
   * @return string
   */
  public function getDescription() {
    return $this->description;
  }

  /**
   * Set description
   * @return Organization
   */
  public function setDescription($description) {
    $this->description = $description;
    return $this;
  }

  /**
   * Get site
   * @return string
   */
  public function getSite() {
    return $this->site;
  }
}