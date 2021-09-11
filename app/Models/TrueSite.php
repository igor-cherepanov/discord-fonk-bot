<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class TrueSite extends Model
{

    public const URL = 'https://wfts.su/';

    public const ROUTE_PROFILE = 'profile/';
    public const ROUTE_PVP = 'pvp/';
    public const ROUTE_PVE = 'pve/';
    public const ROUTE_GUNS = 'weapons/';
    public const ROUTE_ACHIEVEMENTS = 'achievements/';

    /**
     * @param string $route
     * @param string $playerName
     * @return \Illuminate\Http\Client\Response
     */
    public static function getStatRequest(string $route, string $playerName): \Illuminate\Http\Client\Response
    {
        return Http::get(self::URL . $route . $playerName);
    }

    /**
     * @param string $route
     * @param string $playerName
     * @return string
     */
    public static function getStat(string $route, string $playerName): ?string
    {
        $response = self::getStatRequest($route, $playerName);
        $result = null;
        if ($response->ok()) {
            $crawler = new Crawler($response->body());
            $stats = $crawler->filter('.statistics-block')->each(function (Crawler $node, $i) {
                return $node->filter('span')->each(function (Crawler $node, $i) {
                    return $node->text();
                });
            });
            $break = false;
            foreach ($stats as $statColumn) {
                foreach ($statColumn as $stat) {
                    $result .= $stat;
                    if ($break) {
                        $result .= PHP_EOL;
                    }
                    $break = !$break;
                }
            }
        }

        return $result;
    }

    /**
     * @param string $playerName
     * @return string|null
     */
    public static function getPvpStat(string $playerName): ?string
    {
        return self::getStat(self::ROUTE_PVP, $playerName);
    }


}
