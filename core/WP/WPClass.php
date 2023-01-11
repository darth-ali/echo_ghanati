<?php

namespace WP;
abstract class WPClass
{
    abstract protected function getSortablePropertiesList();

    /**
     * @param $property
     * @return mixed
     */
    public function getSortablePropertyValue($property)
    {
        $sortablePropertiesList = $this->getSortablePropertiesList();
        if (!array_key_exists($property, $sortablePropertiesList))
            $property = 'ID';
        return $sortablePropertiesList[$property];
    }

    public function getClassName(): string
    {
        return get_called_class();
    }

    /**
     * @param $list
     * @param $on
     * @param string $order
     * @return array
     */
    public static function MultiSort($list, $on, string $order = 'ASC'): array
    {

        if ($order == 'DESC') {
            usort($list,
                function ($x, $y) use ($on) {
                    return $y->getSortablePropertyValue($on) - $x->getSortablePropertyValue($on);
                }
            );
        }
        else {
            usort($list,
                function ($x, $y) use ($on) {
                    return $x->getSortablePropertyValue($on) - $y->getSortablePropertyValue($on);
                }
            );
        }

        return $list;
    }

    /**
     * @param array $dbObjectsArray :: این همان آرایه‌ای است که از طریق توابع وردپرس لیست محتواها را گرفته‌ایم
     * @return array
     */
    protected static function ConvertToObjectList(array $dbObjectsArray): array
    {
        /** @var WPCustomPost $className */
        $className = self::GetThisClassName();
        $list = [];
        foreach ($dbObjectsArray as $originalPost) {
            $post = $className::__Create($originalPost->ID);
            $list[] = $post;
        }
        return $list;
    }


    public static function GetThisClassName(): string
    {
        return get_called_class();
    }
}