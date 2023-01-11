<?php


namespace BaseModel;

class Status
{

    private int    $ID;
    private string $name  = '';
    private string $slug  = '';
    private string $color = '';

    /**
     * CustomStatus constructor.
     * @param int $ID
     * @param string $name
     * @param string $slug
     * @param string $color
     */
    private function __construct(int $ID = 0, string $name = '', string $slug = '', string $color = '')
    {
        $this->ID = $ID;
        $this->name = $name;
        $this->slug = $slug;
        $this->color = $color;
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
     * @return mixed|string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed|string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }


    //endregion

    // region Class Static Functions::

    /**
     * @param int $ID
     * @param string $name
     * @param string $slug
     * @param string $color
     * @return Status
     */
    public static function Create(int $ID = 0, string $name = '', string $slug = '', string $color = ''): Status
    {
        return new self($ID, $name, $slug, $color);
    }

    //endregion

}
