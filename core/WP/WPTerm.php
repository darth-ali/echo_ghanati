<?php

namespace WP;

abstract class WPTerm
{
    private int    $ID;
    private string $name;
    private string $slug;
    private string $description;
    private int    $taxonomyID;
    private int    $parentID;

    /**
     * WPTerm constructor.
     * @param int $ID
     * @param string $name
     * @param string $slug
     * @param int $taxonomyID
     * @param string $description
     * @param int $parentID
     */
    private function __construct(int $ID, string $name, string $slug, int $taxonomyID, string $description, int $parentID)
    {
        $this->ID = $ID;
        $this->name = $name;
        $this->slug = $slug;
        $this->taxonomyID = $taxonomyID;
        $this->description = $description;
        $this->parentID = $parentID;

    }

    // region Class Getter Functions::

    /**
     * @return int
     */
    public function getID(): int
    {
        return $this->ID;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return WPTaxonomy | bool
     */
    protected function getTaxonomy()
    {
        if ($this->taxonomyID == 0)
            return false;
        return new WPTaxonomy($this->taxonomyID);
    }

    /**
     * @return int
     */
    public function getParentID(): int
    {
        return $this->parentID;
    }

    public function getClassName(): string
    {
        return get_called_class();
    }
    //endregion

    // region Class Static Functions::

    public static function Create(int $ID)
    {
        $className = self::GetThisClassName();

        if ($ID < 1)
            return false;
        else {
            $_selectedObject = get_term($ID);

            return $_selectedObject == null ? false : new $className($ID);
        }
    }

    protected static function GetTerms(int $postID, string $taxonomy): array
    {
        $className = self::GetThisClassName();
        $result = [];
        $array = get_the_terms($postID, $taxonomy);
        if ($array) {
            foreach ($array as $tag) {
                $result[] = new $className($tag->term_id);
            }
        }

        return $result;
    }

    protected static function GetAllTermsOfTaxonomy(string $taxonomy): array
    {
        $className = self::GetThisClassName();

        $result = [];
        $array = get_terms(['taxonomy' => $taxonomy, 'hide_empty' => false]);
        if ($array) {
            foreach ($array as $tag) {
                $result[] = new $className($tag->term_id);
            }
        }

        return $result;
    }

    public static function GetThisClassName(): string
    {
        return get_called_class();
    }

    //endregion
}