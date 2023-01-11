<?php

namespace WP;

use Cassandra\Function_;

class WPCategory extends WPTerm
{


    /**
     * @return false|WPCategory
     */
    public function getParent()
    {
        return self::Create($this->getParentID());
    }

    /**
     * @param int $customPostID
     * @param string $taxonomy
     * @return WPCategory[]
     */
    public static function GetPostCategories(int $customPostID, string $taxonomy): array
    {
        return parent::GetTerms($customPostID, $taxonomy);
    }

    /**
     * @param string $taxonomy
     * @return WPCategory[]
     */
    public static function GetAllCategories(string $taxonomy): array
    {
        return self::GetAllTermsOfTaxonomy($taxonomy);
    }
}