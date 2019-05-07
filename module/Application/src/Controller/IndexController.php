<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController {
  public function indexAction() {
    $hits = [];

    $log_dir = getcwd() . '/data/cache/';
    $files = scandir($log_dir);

    // Loop through log files.
    foreach ($files as $file) {
      $file_parts = pathinfo($file);
      if ($file_parts['extension'] === 'log') {
        // We know that each line is composed of
        // 46 characters + 1 newline so we can
        // deduce the line count from the filesize.
        $hit_count = filesize($log_dir . $file) / 47;

        // Filename contains day for data.
        $day = $file_parts['filename'];

        $hits[$day] = $hit_count;
      }
    }

    return new ViewModel([
      'hits' => $hits,
    ]);
  }
}
