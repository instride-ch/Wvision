<?php
/**
 * w-vision
 *
 * LICENSE
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that is distributed with this source code.
 *
 * @copyright  Copyright (c) 2018 w-vision AG (https://www.w-vision.ch)
 */

namespace WvisionBundle\Installer;

use Doctrine\DBAL\Connection;

final class DemoInstaller
{
    /**
     * @var Connection
     */
    private $db;

    /**
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * {@inheritdoc}
     */
    public function installDemo()
    {
        $this->db->exec(
            '
            SET NAMES utf8mb4;

            -- Documents
            DELETE FROM `documents` WHERE `id`=1 OR `id`=2 OR `id`=3;
            INSERT INTO `documents` VALUES (1, 0, \'page\', \'\', \'/\', 999999, 1, 1368522989, 1505139865, 1, 2);
            INSERT INTO `documents` VALUES (2, 1, \'page\', \'impressum\', \'/\', 0, 1, 1505139774, 1505139846, 2, 2);
            INSERT INTO `documents` VALUES (3, 1, \'page\', \'fehler\', \'/\', 1, 1, 1503510199, 1505139888, 2, 2);
            
            DELETE FROM `documents_page` WHERE `id`=1 OR `id`=2 OR `id`=3;
            INSERT INTO `documents_page` VALUES (1, NULL, \'@AppBundle\\Controller\\DefaultController\', \'default\', NULL, \'Startseite\', \'\', \'a:0:{}\', NULL, 0, \'\', 0);
            INSERT INTO `documents_page` VALUES (2, NULL, \'@AppBundle\\Controller\\DefaultController\', \'imprint\', NULL, \'Impressum\', \'\', \'a:0:{}\', NULL, NULL, \'\', 0);
            INSERT INTO `documents_page` VALUES (3, NULL, \'@AppBundle\\Controller\\ErrorController\', \'error\', NULL, \'Fehler\', \'\', \'a:0:{}\', NULL, NULL, \'\', 0);
            
            -- Assets
            DELETE FROM `assets` WHERE `id`=1;
            INSERT INTO `assets` VALUES (1, 0, \'folder\', \'\', \'/\', NULL, 1368522989, 1368522989, 1, 1, \'\', 0);
            INSERT INTO `assets` VALUES (2, 1, \'folder\', \'documents\', \'/demo\', NULL, 1505140576, 1505140576, 1, 1, \'a:0:{}\', 0);
            INSERT INTO `assets` VALUES (3, 1, \'folder\', \'images\', \'/demo\', NULL, 1505140572, 1505140572, 1, 1, \'a:0:{}\', 0);
            INSERT INTO `assets` VALUES (4, 2, \'document\', \'muster_excel.xlsx\', \'/demo/documents/\', \'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet\', 1505141170, 1505141660, 2, 2, \'a:0:{}\', 0);
            INSERT INTO `assets` VALUES (5, 2, \'document\', \'muster_pdf.pdf\', \'/demo/documents/\', \'application/pdf\', 1505141170, 1505141653, 1, 1, \'a:1:{s:19:\"document_page_count\";s:1:\"1\";}\', 0);
            INSERT INTO `assets` VALUES (6, 2, \'document\', \'muster_powerpoint.pptx\', \'/demo/documents/\', \'application/vnd.openxmlformats-officedocument.presentationml.presentation\', 1505141170, 1505141669, 2, 2, \'a:0:{}\', 0);
            INSERT INTO `assets` VALUES (7, 2, \'document\', \'muster_word.docx\', \'/demo/documents/\', \'application/vnd.openxmlformats-officedocument.wordprocessingml.document\', 1505141170, 1505141676, 2, 2, \'a:0:{}\', 0);
            INSERT INTO `assets` VALUES (8, 3, \'image\', \'wvision_baum.jpg\', \'/demo/images/\', \'image/jpeg\', 1505141522, 1505141522, 1, 1, \'a:3:{s:10:\"imageWidth\";i:2048;s:11:\"imageHeight\";i:1366;s:25:\"imageDimensionsCalculated\";b:1;}\', 0);
            INSERT INTO `assets` VALUES (9, 3, \'image\', \'wvision_newyork.jpg\', \'/demo/images/\', \'image/jpeg\', 1505141522, 1505141522, 1, 1, \'a:3:{s:10:\"imageWidth\";i:2048;s:11:\"imageHeight\";i:1366;s:25:\"imageDimensionsCalculated\";b:1;}\', 0);
            INSERT INTO `assets` VALUES (10, 3, \'image\', \'wvision_pusteblume.jpg\', \'/demo/images/\', \'image/jpeg\', 1505141522, 1505141522, 1, 1, \'a:3:{s:10:\"imageWidth\";i:2048;s:11:\"imageHeight\";i:1366;s:25:\"imageDimensionsCalculated\";b:1;}\', 0);
            INSERT INTO `assets` VALUES (11, 3, \'image\', \'wvision_windraeder.jpg\', \'/demo/images/\', \'image/jpeg\', 1505141522, 1505141522, 1, 1, \'a:3:{s:10:\"imageWidth\";i:2048;s:11:\"imageHeight\";i:1366;s:25:\"imageDimensionsCalculated\";b:1;}\', 0);
            INSERT INTO `assets` VALUES (12, 3, \'image\', \'wvision_winter.jpg\', \'/demo/images/\', \'image/jpeg\', 1505141522, 1505141522, 1, 1, \'a:3:{s:10:\"imageWidth\";i:2048;s:11:\"imageHeight\";i:1366;s:25:\"imageDimensionsCalculated\";b:1;}\', 0);
            
            -- Objects
            DELETE FROM `objects` WHERE `o_id`=1;
            INSERT INTO `objects` VALUES (1, 0, \'folder\', \'\', \'/\', 999999, 1, 1368522989, 1368522989, 1, 1, 0, \'\', NULL);
            
            -- Properties
            DELETE FROM `properties` WHERE `cid`=1 OR `cid`=2 OR `cid`=3; 
            INSERT INTO `properties` VALUES (1, \'document\', \'/\', \'language\', \'text\', \'de\', 1);
            INSERT INTO `properties` VALUES (1, \'document\', \'/\', \'imprintPage\', \'document\', 2, 1);
            INSERT INTO `properties` VALUES (2, \'document\', \'/impressum\', \'navigation_exclude\', \'bool\', 1, 0);
            INSERT INTO `properties` VALUES (2, \'document\', \'/impressum\', \'navigation_name\', \'text\', \'Impressum\', 0);
            INSERT INTO `properties` VALUES (3, \'document\', \'/fehler\', \'navigation_exclude\', \'bool\', 1, 0);
            INSERT INTO `properties` VALUES (3, \'document\', \'/fehler\', \'navigation_name\', \'text\', \'Fehler\', 0);
            
            -- Translations
            INSERT INTO translations_website VALUES (\'app.language_switcher.language\', \'de\', \'Sprache\', 1368608505, 1368608505);
            INSERT INTO translations_website VALUES (\'app.fulltext_search.placeholder\', \'de\', \'Suchen ...\', 1368608505, 1368608505);
            INSERT INTO translations_website VALUES (\'app.fulltext_search.no_results\', \'de\', \'Keine Resultate\', 1368608505, 1368608505);
            
            -- Users
            UPDATE `users`
            SET `firstname` = \'Adrian\',
              `lastname` = \'Hess\',
              `email` = \'support@w-vision.ch\',
              `welcomescreen` = \'0\',
              `closeWarning` = \'1\',
              `memorizeTabs` = \'1\',
              `allowDirtyClose` = \'1\'
            WHERE `name` = \'wvision\';
        '
        );
    }
}
