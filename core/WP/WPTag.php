<?php

namespace WP;

class WPTag extends WPTerm
{

    /**
     * @return string
     */
    public function getChain(): string
    {
        return str_replace('-', '_', htmlspecialchars(rawurldecode($this->getSlug()), ENT_NOQUOTES, 'UTF-8'));
    }

    /**
     * @param int $postID
     * @param string $taxonomy
     * @return WPTag[]
     */
    public static function GetAllTags(int $postID, string $taxonomy): array
    {
        return self::GetTerms($postID, $taxonomy);
    }
}