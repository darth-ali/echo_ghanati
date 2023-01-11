<?php


namespace WP;

use BaseModel\MediaType;
use Instrument\WPInstrument;
use SmartDate\SmartDate;

class WPAttachment extends WPCustomPost
{

    // region Class Public Functions::

    /**
     * @return string
     */
    public function getThumbnailURL(): string
    {
        return WPInstrument::GetFileThumbnail($this->getURL());

    }

    /**
     * @return  string Attachment URL, otherwise ''.
     */
    public function getURL(): string
    {
        $attachmentUrl = wp_get_attachment_url($this->getID());
        return !$attachmentUrl ? '' : $attachmentUrl;
    }

    /**
     * @return MediaType
     */
    public function getMediaType(): MediaType
    {
        return MediaType::FindMediaType($this->getURL());
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        $size = filesize(get_attached_file($this->getID()));
        return !$size ? 0 : $size;
    }

    /**
     * @return string
     */
    public function getDisplaySize(): string
    {
        $size = $this->getSize();
        return WPInstrument::ConvertFileSizeToDisplaySize($size);

    }


    public function getCustomPostType(): string
    {
        return self::GetThisCustomPostType();
    }

    //endregion

    // region Class Static Functions::

    /**
     * @param string|int $attachment
     * @return WPAttachment|false
     */
    public static function Create($attachment)
    {
        if (is_numeric($attachment)) {
            $ID = $attachment;
        }
        else {
            $ID = attachment_url_to_postid($attachment);
        }
        return parent::__Create($ID);
    }

    /**
     * @param array $metaQuery
     * @param int $count
     * @param SmartDate|null $from
     * @param SmartDate|null $to
     * @param string $orderBy
     * @param string $order
     * @return WPAttachment[]
     */
    public static function GetAttachmentsList(array $metaQuery, int $count = -1, SmartDate $from = null, SmartDate $to = null, string $orderBy = 'date', string $order = 'DESC'): array
    {
        return parent::__SelectFiltered(self::GetThisCustomPostType(), $metaQuery, $count, $from, $to, $orderBy, $order);
    }

    /**
     * @param WPAttachment[] $attachments
     * @return string
     */
    public static function GetAttachmentsTotalSize(array $attachments): string
    {
        $size = 0;
        foreach ($attachments as $attachment) {
            $size += $attachment->getSize();
        }
        return WPInstrument::ConvertFileSizeToDisplaySize($size);
    }


    public static function GetThisCustomPostType(): string
    {
        return 'attachment';
    }
    //endregion
}