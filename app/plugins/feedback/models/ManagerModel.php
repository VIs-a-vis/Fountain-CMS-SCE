<?php

/**
 * Fountain CMS Site Card Edition
 * 
 * Lightweight content management system for site cards and minisites
 * 
 * @author    Kanat Gailimov <gailimov@gmail.com>
 * @copyright Copyright (c) Kanat Gailimov (http://gailimov.info) 2011
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License v3
 */


namespace app\plugins\feedback\models;

use core\Model;

/**
 * Manager model
 * 
 * @author Kanat Gailimov <gailimov@gmail.com>
 */
class ManagerModel extends Model
{
    /**
     * Table
     * 
     * @var string
     */
    private $_table = 'manager';

    public function get()
    {
        $query = "SELECT * FROM " . $this->_table;
        return $this->_db->fetchRow($query);
    }
}
