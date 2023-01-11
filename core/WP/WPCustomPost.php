<?php

namespace WP;

use DataLogging\Log;
use SmartDate\SmartDate;
use SmartDB\SmartDB;
use WP_Query;

abstract class WPCustomPost extends WPClass
{
    private int $ID;

    /**
     * WPCustomPost constructor.
     * @param int $ID
     */
    private function __construct (int $ID)
    {
        $this->ID = $ID;
    }

    // region Class Abstract Functions::

    abstract public static function GetThisCustomPostType ();

    abstract public function getCustomPostType ();

    //endregion

    // region Class Getter Functions::

    /**
     * @return int
     */
    public function getID (): int
    {
        return $this->ID;
    }

    /**
     * @return string
     */
    public function getTitle (): string
    {
        return get_the_title($this->getID());
    }

    /**
     * @param bool $editMode
     * @return string
     */
    public function getContent (bool $editMode = true): string
    {
        $result = get_the_content(NULL, false, $this->getID());
        return $editMode ? $result : apply_filters('the_content', $result);
        //        return wp_strip_all_tags();
    }

    /**
     * @return SmartDate
     */
    public function getDate (): SmartDate
    {
        return new SmartDate(get_post_time('U', false, $this->getID()));
    }

    /**
     * @return string
     */
    public function getEditLink (): string
    {
        $_editLink = get_edit_post_link($this->getID());
        return ($_editLink == NULL) ? '' : $_editLink;

    }

    /**
     * @param string $attachmentDBKeyName
     * @return WPAttachment[]
     */
    protected function getAttachmentsBy (string $attachmentDBKeyName): array
    {
        $attachments        = [];
        $attachmentsIDArray = $this->getPostMeta($attachmentDBKeyName, 'array');
        foreach ($attachmentsIDArray as $attachmentID) {
            $attachments[] = WPAttachment::Create($attachmentID);
        }
        return $attachments;
    }

    /**
     * @param string $key
     * @param string $metaValueType :: 'int' , 'bool' , 'array'
     * @return array|int|string
     */
    protected function getPostMeta (string $key, string $metaValueType = 'string')
    {
        $metaValue = get_post_meta($this->getID(), $key, true);

        if ($metaValueType == 'int')
            return !$metaValue ? 0 : $metaValue;
        elseif ($metaValueType == 'bool')
            return $metaValue;
        elseif ($metaValueType == 'array')
            return !$metaValue ? [] : $metaValue;
        else
            return !$metaValue ? '' : $metaValue;

    }

//    /**
//     * @return WPAttachment | false
//     */
//    public function getCover ()
//    {
//        $attachmentID = get_post_thumbnail_id($this->getID());
//        return (!$attachmentID || $attachmentID == 0) ? TEMPLATE_URL . '/asset/media/no-image.jpg' : WPAttachment::Create($attachmentID);
//    }


    //endregion

    // region Class Update Functions::

    /**
     * @param string $title
     * @return bool
     */
    public function updateTitle (string $title): bool
    {

        Log::Insert(1, $this->getID(), $this->getTitle(), $title);
        return $this->updatePostData('title', $title, '%s');
    }

    /**
     * @param string $publishStatus
     * @return bool
     */
    public function updatePublishStatus (string $publishStatus = 'publish'): bool
    {
        return $this->updatePostData('post_status', $publishStatus, '%s');
    }

    /**
     * @param string $content
     * @return bool
     */
    public function updateContent (string $content): bool
    {
        Log::Insert(2, $this->getID(), $this->getContent(), $content);

        return $this->updatePostData('post_content', $content, '%s');

    }

//    /**
//     * @param int $thumbnailID
//     * @return bool
//     */
//    public function updateCover (int $thumbnailID): bool
//    {
//        //        Log::Insert(2, $this->getID(), $this->getContent(), $content);
//        return set_post_thumbnail($this->getID(), $thumbnailID);
//    }

    /**
     * @param string $date :: persian string -> 1392-02-23
     * @return bool
     */
    public function updateDate (string $date): bool
    {
        $publishDate = new SmartDate($date, 'string', 'jalali');

        Log::Insert(3, $this->getID(), $this->getDate()->getJalaliDateString(), $publishDate->getJalaliDateString());

        return $this->updatePostData('post_date', $publishDate->getGregorianDateString() . " 00:00:00", '%s');

    }


    /**
     * @param string $key
     * @param string $value
     * @param string $format
     * @return bool
     */
    private function updatePostData (string $key, string $value, string $format): bool
    {
        global $wpdb;

        $table = $wpdb->posts;

        $db            = new SmartDB();
        $updatedObject = $db->update($table)
                            ->addData($key, $value, $format)
                            ->addConditionData('ID', $this->getID(), '%d');
        $updatedObject = $updatedObject->save();

        return (bool)$updatedObject;

    }

    /**
     * @param string $key
     * @param        $value
     * @return true
     * key not exist            => insert       => must return true
     * key exist but value new  => update value => must return true
     * key exist and value same => return false => we return true
     */
    protected function updatePostMeta (string $key, $value): bool
    {

        update_post_meta($this->getID(), $key, $value);
        return true;
    }

    /**
     * @param        $attachments :: به دلیل خالی بودن در بعضی موارد نوع ندارد
     * @param string $attachmentDBKeyName
     * @param int    $logTypeID
     * @return bool
     */
    protected function updateAttachmentsBy ($attachments, string $attachmentDBKeyName, int $logTypeID): bool
    {
        // region Log Old Files Holder::
        $oldFiles      = $this->getAttachmentsBy($attachmentDBKeyName);
        $oldFilesLinks = [];
        foreach ($oldFiles as $file) {
            $oldFilesLinks[] = $file->getURL();
        }
        //endregion

        $result = $this->updatePostMeta($attachmentDBKeyName, $attachments);

        // region New Old Files Holder::
        $newFiles      = $this->getAttachmentsBy($attachmentDBKeyName);
        $newFilesLinks = [];
        foreach ($newFiles as $file) {
            $newFilesLinks[] = $file->getURL();
        }
        //endregion

        Log::Insert($logTypeID, $this->getID(), implode('<br>', $oldFilesLinks), implode('<br>', $newFilesLinks));

        return $result;
    }


    //endregion

    // region Class static Functions::

    /**
     * @param string $className
     * @param int    $ID
     * @return mixed
     */
    protected static function __Create (int $ID)
    {
        $className = self::GetThisClassName();
        if ($ID == 0)
            return false;
        else {
            $_selectedObject = get_post($ID);

            return $_selectedObject == NULL ? false : new $className($ID);
        }
    }


    /**
     * @param string         $customPostType
     * @param string         $className
     * @param array          $metaQuery
     * @param int            $count
     * @param SmartDate|null $from
     * @param SmartDate|null $to
     * @param string         $orderBy
     * @param string         $order
     * @return array
     */
    protected static function __SelectFiltered (string $customPostType, array $metaQuery = [], int $count = -1, SmartDate $from = NULL, SmartDate $to = NULL, string $orderBy = 'date', string $order = 'DESC'): array
    {
        if ($count < 1)
            $count = -1;
        if ($from == NULL) {
            $dateQuery['after'] = [];
        }
        else {
            $dateQuery['after'] = $from->find(-1)->getGregorianDateString() . ' 23:59:00';
        }
        if ($to != NULL) {
            $dateQuery['before'] = $to->getGregorianDateString() . ' 23:59:00';
        }
        else {
            $dateQuery['before'] = [];

        }
        $args      = [
            'post_type'      => [$customPostType],
            'posts_per_page' => $count,
            'post_status'    => ['publish', 'future'],
            'orderby'        => $orderBy,
            'order'          => $order,
            'date_query'     => [
                $dateQuery,
                'inclusive' => true,
            ],
            'meta_query'     => $metaQuery,
        ];
        $list      = new WP_Query($args);
        $listArray = $list->get_posts();
        return self::ConvertToObjectList($listArray);

    }

    /**
     * @param string $customPostType
     * @param string $className
     * @return array
     */
    protected static function __SelectAll (string $customPostType): array
    {
        return WPCustomPost::__SelectFiltered($customPostType);

    }


    protected static function __Insert (string $customPostType, string $title)
    {
        $className = self::GetThisClassName();

        $ID = wp_insert_post(
            [
                'post_type'  => $customPostType,
                'post_staus' => 'publish',
                'post_title' => $title,
            ]
        );
        return !is_wp_error($ID) ? new $className($ID) : false;
    }

    //endregion

    // region Class Protected Functions::

    protected function getSortablePropertiesList (): array
    {
        return [
            'ID' => $this->getID(),
        ];
    }

    //endregion


}