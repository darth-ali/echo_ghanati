<?php


namespace BaseModel;

class Type
{
    private int    $ID;
    private string $name;
    private string $slug;
    private string $description = '';

    /**
     * Type constructor.
     * @param int $ID
     * @param string $name
     * @param string $slug
     * @param string $description
     */
    protected function __construct(int $ID, string $name, string $slug = '', string $description = '')
    {
        $this->ID = $ID;
        $this->name = $name;
        $this->slug = $slug;
        $this->description = $description;
    }

    // region Class Public Functions::

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
    //endregion

    // region Class Static Functions::

    /**
     * @param int $ID
     * @param string $name
     * @param string $slug
     * @param string $description
     * @return Type
     */
    public static function Create(int $ID, string $name, string $slug = '', string $description = ''): Type
    {
        return new self($ID, $name, $slug, $description);
    }


    //endregion

}