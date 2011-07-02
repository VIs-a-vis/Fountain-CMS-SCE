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


namespace app\models;

use core\Model;

/**
 * Category model
 * 
 * @author Kanat Gailimov <gailimov@gmail.com>
 */
class CategoryModel extends Model
{
    public function getAll()
    {
        $query = "SELECT slug, title FROM category";
        return $this->_db->fetchAll($query);
    }

    public function getBySlug($slug)
    {
        $query = "SELECT id, title, description FROM category
                  WHERE slug = ?";
        return $this->_db->fetchRow($query, $slug);
    }
}
