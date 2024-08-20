<?php

namespace Drupal\os2loop_section_page_fixtures\Fixture;

use Drupal\content_fixtures\Fixture\AbstractFixture;
use Drupal\content_fixtures\Fixture\FixtureGroupInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Front page fixture.
 *
 * @package Drupal\os2loop_section_page_fixtures\Fixture
 */
class FrontPageFixture extends AbstractFixture implements FixtureGroupInterface {
  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  private $configFactory;

  /**
   * Constructor.
   */
  public function __construct(ConfigFactoryInterface $configFactory) {
    $this->configFactory = $configFactory;
  }

  /**
   * {@inheritdoc}
   */
  public function load() {
    $page = Node::create([
      'type' => 'os2loop_section_page',
      'title' => 'Front page',
    ]);

    // 1 Highlighted Indhold
    $paragraph = Paragraph::create([
      'type' => 'os2loop_section_page_info_block',
      'os2loop_section_page_title' => '',
      'os2loop_section_page_info_text' => [
        'value' => <<<'BODY'
          <p><strong><a href="https://www.w3schools.com/">Heste</a></strong> har i årtusinder fascineret og tjent mennesket på utallige måder. De er blevet værdsat for deres styrke, hastighed, og ikke mindst for deres evne til at knytte bånd til mennesker. Gennem tiderne har heste været uundværlige partnere i både krig og fred, fra at bære soldater i kamp til at hjælpe landmænd med at pløje markerne. Deres ynde og kraft har gjort dem til symbolske skikkelser i mange kulturer, ofte forbundet med frihed, styrke, og skønhed.</p>
    BODY,
        'format' => 'os2loop_section_page',
      ],

    ]);
    $paragraph->save();
    $page->get('os2loop_section_page_paragraph')->appendItem($paragraph);

    // 2 Search Bar
    $paragraph = Paragraph::create([
      'type' => 'os2loop_section_page_search',
      'os2loop_section_page_block' => [
        'plugin_id' => 'views_exposed_filter_block:os2loop_search_db-page_search_form',
        'settings' => [
          'id' => 'views_exposed_filter_block:os2loop_search_db-page_search_form',
          'label' => '',
          'label_display' => 0,
        ],
      ],
    ]);

    $paragraph->save();
    $page->get('os2loop_section_page_paragraph')->appendItem($paragraph);
    $page->save();

    // 3 Normal Content med Overskrift
    $paragraph = Paragraph::create([
      'type' => 'os2loop_section_page_views_refer',
      'os2loop_section_page_view_header' => 'I dag findes der over 300 forskellige hesteracer!',
      'os2loop_section_page_view_text' => [
        'value' => <<<'BODY'
    <p>Hver med deres egne særlige egenskaber og anvendelsesmuligheder. De mest kendte racer inkluderer den elegante Araber, den robuste Clydesdale, og den lynhurtige Fuldblodshest. Araberen er måske en af de ældste hesteracer, og dens elegance, intelligens, og udholdenhed har gjort den populær verden over. Clydesdalen, derimod, er kendt for sin store styrke og rolige temperament, hvilket gør den ideel til tungt arbejde og ceremonielle opgaver.</p>
    BODY,
        'format' => 'os2loop_section_page',
      ],
    ]);
    $paragraph->save();
    $page->get('os2loop_section_page_paragraph')->appendItem($paragraph);

    // Set page as the site front page.
    $config = $this->configFactory->getEditable('system.site');
    $pageConfig = $config->get('page');
    $pageConfig['front'] = $page->toUrl()->toString();
    $config->set('page', $pageConfig);
    $config->save();
  }

  /**
   * {@inheritdoc}
   */
  public function getGroups() {
    return ['os2loop_section_page'];
  }

}
