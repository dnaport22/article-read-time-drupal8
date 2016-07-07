<?php
/**
 * @file
 * Conatins \Drupal\art\Controller\art_controller
 */

namespace Drupal\art\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Driver\mysql\Connection;
use Drupal\node\Controller\NodeController;
use Drupal\node\Entity\Node;
use Drupal\views\Plugin\views\field\RenderedEntity;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\file\FileInterface;
use Drupal\file\Entity\File;


class art_controller extends ControllerBase{

    public $title = NULL;
    public $body = NULL;
    public $wpm = NULL;
    public $art = NULL;
    public $img_count = NULL;
    public $read_time = NULL;
    public $img_time = 12;

    public function content() {
        $entity_type = 'node';
        $view_mode = 'teaser';
        $view_builder = \Drupal::entityTypeManager()->getViewBuilder($entity_type);
        $storage = \Drupal::entityTypeManager()->getStorage($entity_type);
        $query = \Drupal::entityQuery('node');
        $nids = $query->execute();
        $content = [];
        $nodes = Node::loadMultiple($nids);
        $this->wpm = 225;
//        $img_query = \Drupal::database()->select('node__field_image', 'nfi');
//        $img_query->fields('nfi', ['nfi', 'entity_id']);

//        $ids = $img_query->execute()->fetchAssoc();
        foreach ($nodes as $node){
            $this->img_count = count($node->field_image);
            $this->title = $node->get('title')->value;
            $this->body = $node->get('body')->value;
            /** Body content */
            $body_text = strip_tags($this->body);
            /** Body word count */
            $body_word_count = str_word_count($body_text);
            /** Title content */
            $title_text = strip_tags($this->title);
            /** Title word count */
            $title_word_count = str_word_count($title_text);
            /** Body word count + Title word count */
            $check_a = $body_word_count + $title_word_count;
            $check_b = $this->img_count * $this->img_time;
            $art_a = $check_a / $this->wpm;
            $art_b = $art_a * 60 + $this->img_;
            $art_format = gmdate('H:i:s', floor($art_a * 60));
            $art_format_split = str_split($art_format);
            /** Time test */
            if (in_array($format, array('hour_short', 'hour_long'))) {
                $hrs = floor($art_a/60);
                $mins = ceil(fmod($art_a, 60));
            } else {
                $mins = ceil($art_a);
            }
            if (in_array($format, array('hour_long', 'min_long'))) {
                $hour_suffix = 'hour';
                $min_suffix = 'minute';
                $sec_suffix = 'second';
            } else {
                $hour_suffix = 'hr';
                $min_suffix = 'min';
                $sec_suffix = 'sec';
            }
            $minute_format = \Drupal::translation()->formatPlural($mins, '1 ' . $min_suffix, '@count ' . $min_suffix . 's');
            $hour_format = \Drupal::translation()->formatPlural($hrs, '1 ' . $hour_suffix, '@count ' . $hour_suffix . 's');
            $sec_format = \Drupal::translation()->formatPlural($sec, '1 ' . $hour_suffix, '@count ' . $hour_suffix . 's');
            if (!empty($hours)) {

                $this->read_time = format_string('@h, @m', array('@h' => $hour_format, '@m' => $minute_format));
            } else {
                $this->read_time = $minute_format;
            }
            $build = $view_builder->view($node, $view_mode);
            $output = render($build);
            $ART = $this->read_time;
            $content[] = array(
                '#markup' => $title_text . '<br/>' .$body_text . '<br/> Image time:'
                    .  $check_b . '<br/>WPM:' . $this->wpm . '<br/> ART:' . $mins . '<br/> ',
            );
        }
        return $content;
    }

}