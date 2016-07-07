<?php
/**
 * @file
 * Contains \Drupal\art\Plugin\Field\FieldFormatter\artFormatter.
 */

namespace Drupal\art\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\node\Entity\Node;
use Drupal\node\Controller\NodeController;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\file\FileInterface;
use Drupal\file\Entity\File;
/**
 * Plugin implementation of the 'dice' formatter.
 *
 * @FieldFormatter (
 *   id = "art",
 *   label = @Translation("Art"),
 *   field_types = {
 *     "art"
 *   }
 * )
 */
class artFormatter extends FormatterBase
{


    public $wpm = NULL;
    public $time_format = NULL;
    public $img_time = NULL;
    public $art = NULL;
    public $read_time = NULL;
    public $title = NULL;
    public $body = NULL;
    public $img_count = NULL;

    public function viewElements(FieldItemListInterface $items, $langcode = NULL)
    {
        $elements = array();

        foreach ($items as $delta => $item) {
            $this->wpm = $item->wpm;
            $this->img_time = $item->image_time;
            $this->time_format = $item->time_format;
            $elements[$delta] = array(
                '#type' => 'markup',
                '#markup' => $this->readTime(),
            );
        }
        return $elements;
    }

    public function readTime()
    {
        /** Calculating article read time based on words per minute and image time */
        $query = \Drupal::entityQuery('node');
        $nids = $query->execute();
        $nodes = Node::loadMultiple($nids);
        foreach ($nodes as $node) {
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
            $art_b = $art_a * 60 + $this->img_count;
            $art_format = gmdate('H:i:s', floor($art_a * 60));
            $art_format_split = str_split($art_format);
            /** Time test */
            if (in_array($format, array('hour_short', 'hour_long'))) {
                $hrs = floor($art_a / 60);
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
        }
        return $check_a;
    }
}