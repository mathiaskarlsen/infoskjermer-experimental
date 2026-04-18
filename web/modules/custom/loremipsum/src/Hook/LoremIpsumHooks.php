<?php

namespace Drupal\loremipsum\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Hook\Attribute\PreprocessTheme;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

class LoremIpsumHooks {

  use StringTranslationTrait;

  #[Hook('help')]
  function help($route_name, RouteMatchInterface $route_match) {
    switch ($route_name) {
      case 'help.page.loremipsum':
        $output = '';
        $output .= '<h2>' . $this->t('Lorem ipsum generator for Drupal.') . '</h2>';
        $output .= '<h3>' . $this->t('Instructions') . '</h3>';
        $output .= '<p>' . $this->t('Lorem ipsum dolor sit amet... <em>Just kidding!</em>') . '<p>';
        $output .= '<p>' . $this->t('If you\'re reading this, you\'ve already installed the module either via <code>composer require drupal/loremipsum</code> or directly downloading it (which <em>should</em> be safe) and have already enabled it in <strong>/admin/modules</strong>.') . '</p>';
        $output .= '<p>' . $this->t('Then, visit <strong>/admin/config/development/loremipsum</strong> and enter your own set of phrases to build random-generated text (or go with the default Lorem ipsum).') . '</p>';
        $output .= '<p>' . $this->t('Lastly, visit <strong>/loremipsum/generate/P/S</strong> where:') . '</p>';
        $output .= '<ul>';
        $output .= '<li>' . $this->t('<strong>P</strong> is the number of <em>paragraphs</em>.') . '</li>';
        $output .= '<li>' . $this->t('<strong>S</strong> is the maximum number of <em>sentences</em>.') . '</li>';
        $output .= '</ul>';
        $output .= '<p>' . $this->t('There is also a generator block in which you can choose how many paragraphs and phrases you want and it\'ll do the rest.') . '</p>';
        $output .= '<p>' . $this->t('If you need, there\'s also a specific <em>generate lorem ipsum</em> permission.') . '</p>';
        $output .= '<h3>' . $this->t('Attention') . '</h3>';
        $output .= '<p>' . $this->t('Most bugs have been ironed out, holes covered, features added. But this module is a work in progress. Please report bugs and suggestions, ok?') . '</p>';
        return $output;
    }
  }

  #[Hook('theme')]
  public function theme(): array {
    return [
      'loremipsum' => [
        'variables' => [
          'source_text' => NULL,
        ],
        'template' => 'loremipsum',
      ],
    ];
  }

  #[PreprocessTheme('loremipsum')]
  public function preprocessLoremIpsum(array &$variables): void {
    $punctuation = ['. ', '! ', '? ', '... ', ': ', '; '];
    for ($i = 0; $i < count($variables['source_text']); $i++) {
      $big_text = explode('. ', $variables['source_text'][$i]);
      for ($j = 0; $j < count($big_text)-1; $j++) {
        $big_text[$j] .= $punctuation[floor(mt_rand(0, count($punctuation)-1))];
      }
      $variables['source_text'][$i] = implode('', $big_text);
    }
  }
}
