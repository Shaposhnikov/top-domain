<?php
/**
 * Top Domain
 *
 * @version    0.1 (2017-07-05 00:01:00 GMT)
 * @author     Peter Kahl <peter.kahl@colossalmind.com>
 * @since      2017-07-05
 * @copyright  2017 Peter Kahl
 * @license    Apache License, Version 2.0
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      <http://www.apache.org/licenses/LICENSE-2.0>
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace peterkahl\TopDomain;

use \SplFileObject;
use \Exception;

class TopDomain {

  public $CacheDir;

  private $dbFile = '/top-1m.csv';

  #===================================================================

  /**
   * Returns random domain and it's rank.
   *
   */
  public function RandomDomain() {
    $max = $this->CountLines();
    $rand = mt_rand(0, $max-1);
    $file = $this->CacheDir . $this->dbFile;
    $openObj = new SplFileObject($file);
    $openObj->seek($rand);
    $line = trim($openObj->current());
    list($rank, $domain) = explode(',', $line);
    return array(
      'domain' => $domain,
      'rank'   => $rank,
    );
  }

  #===================================================================

  /**
   * Find domain's rank. Whether or not in top 1 million.
   *
   */
  public function FindDomain($needle) {
    $needle = strtolower($needle);
    $needle = preg_replace('/[^a-z0-9\.-]/', '', $needle);
    $needle = preg_replace('/^www\./', '', $needle);
    $file = $this->CacheDir . $this->dbFile;
    $openObj = new SplFileObject($file);
    $candidate = array();
    while (!$openObj->eof()) {
      $line = trim($openObj->fgets());
      if (!empty($line)) {
        list($rank, $domain) = explode(',', $line);
        if ($domain == $needle) {
          return array(
            'domain' => $domain,
            'rank'   => $rank,
          );
        }
        $escaped = str_replace('.', '\.', $needle);
        if (preg_match('/'. $escaped .'$/', $domain)) {
          $candidate = array(
            'domain' => $domain,
            'rank'   => $rank,
          );
        }
      }
    }
    return $candidate;
  }

  #===================================================================

  /**
   * Returns an array of domains.
   * @var start  boolean .... range 1-1000000
   * @var size   integer .... range 1-1000
   */
  public function GetDomains($start, $size = 1) {
    $max = $this->CountLines();
    if ($start < 1 || $start > $max) {
      throw new Exception('Illegal value argument start');
    }
    if ($size < 1 || $size > 1000) {
      throw new Exception('Illegal value argument size');
    }
    $new = array();
    $file = $this->CacheDir . $this->dbFile;
    $openObj = new SplFileObject($file);
    $openObj->seek($start-1);
    $line = trim($openObj->current());
    list($rank, $domain) = explode(',', $line);
    $new[] = array(
      'domain' => $domain,
      'rank'   => $rank,
    );
    $n = 1;
    while (!$openObj->eof() && $n < $size) {
      $openObj->next();
      $line = trim($openObj->current());
      list($rank, $domain) = explode(',', $line);
      $new[] = array(
        'domain' => $domain,
        'rank'   => $rank,
      );
      $n++;
    }
    return $new;
  }

  #===================================================================

  private function CountLines() {
    $file = $this->CacheDir . $this->dbFile;
    if (!file_exists($file)) {
      throw new Exception('Unable to read '. $file);
    }
    if (function_exists('shell_exec')) {
      return trim(shell_exec('cat '. $file .' | wc -l'));
    }
    return 1000000;
  }

  #===================================================================
}