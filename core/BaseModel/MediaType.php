<?php


namespace BaseModel;

class MediaType extends Type
{
    private array $extensions;

    /**
     * MediaType constructor.
     * @param int $ID
     * @param string $name
     * @param string $slug
     * @param string $description
     * @param string[] $extensions
     */
    private function __construct(int $ID, string $name, string $slug = '', string $description = '', array $extensions = [])
    {
        parent::__construct($ID, $name, $slug, $description);
        $this->extensions = $extensions;
    }

    // region Class Public Functions::

    /**
     * @return array
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }

    /**
     * @param string $file :: file name or url
     * @return MediaType
     */
    public static function FindMediaType(string $file): MediaType
    {
        global $mediaTypes;
        $fileExtension = wp_check_filetype($file)['ext'];
        /* @var $mediaType MediaType */
        foreach ($mediaTypes as $mediaType) {
            if (in_array($fileExtension, $mediaType->getExtensions()))
                return $mediaType;
        }
        return $mediaTypes[1];
    }

    //endregion

    // region Class Static Functions::
    public static function Create(int $ID, string $name, string $slug = '', string $description = '', array $extensions = []): MediaType
    {
        return new self($ID, $name, $slug, $description, $extensions);
    }
    //endregion

}
