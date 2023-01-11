<?php

namespace Instrument;

use BaseModel\MediaType;

class WPInstrument
{

    /**
     * @param string $file
     * @return string
     */
    public static function GetFileThumbnail(string $file): string
    {
        $fileType = MediaType::FindMediaType($file);
        if ($fileType->getID() == 2) {
            $attachmentID = attachment_url_to_postid($file);
            return wp_get_attachment_image_src($attachmentID, 'thumbnail')[0];

        }
        elseif ($fileType->getID() == 3) {
            return get_template_directory_uri() . '/asset/media/svg/files/mp4.svg';

        }
        elseif ($fileType->getID() == 4) {
            return get_template_directory_uri() . '/asset/media/mp3.png';
        }
        else {
            return get_template_directory_uri() . '/asset/media/svg/files/doc.svg';
        }
    }

    /**
     * @param string $currentVersion
     * @param string $changeType :: LOW , MEDIUM , HIGH
     * @return string
     */
    public static function UpgradeVersion(string $currentVersion, string $changeType = LOW): string
    {
        $upgradeVersion = $currentVersion;
        $versionArray = explode('.', $currentVersion);
        if ($changeType == LOW) {
            $upgradeVersion = $versionArray[0] . '.' . $versionArray[1] . '.' . ($versionArray[2] + 1);
        }
        elseif ($changeType == MEDIUM) {
            $upgradeVersion = $versionArray[0] . '.' . ($versionArray[1] + 1) . '.' . $versionArray[2];

        }
        elseif ($changeType == HIGH) {
            $upgradeVersion = ($versionArray[0] + 1) . '.' . $versionArray[1] . '.' . $versionArray[2];

        }
        return $upgradeVersion;
    }

    /**
     * @return false|string
     */
    public static function GetCustomPostType()
    {
        if (isset($_GET['post_type']))
            return $_GET['post_type'];

        elseif (isset($_GET['post']))
            return get_post_type($_GET['post']);

        elseif (isset($post->post_type))
            return $post->post_type;

        elseif (isset($_POST['post_type']))
            return $_POST['post_type'];

        return false;

    }

    /**
     * @param string[] $customPostTypes
     * @return bool
     */
    public static function CheckCurrentCustomPostTypeInArray(array $customPostTypes): bool
    {
        $currentPostType = self::GetCustomPostType();
        if ($currentPostType && in_array($currentPostType, $customPostTypes))
            return true;
        return false;
    }

    /**
     * @return bool
     */
    public static function IsEditCustomPostType(): bool
    {
        if (!isset($_GET['post_type']) && isset($_GET['post']))
            return true;
        return false;
    }

    /**
     * @param int $size
     * @return string
     */
    public static function ConvertFileSizeToDisplaySize(int $size): string
    {
        $sizesSuffix = [" بایت", " کیلوبایت", " مگابایت", " گیگابایت", " ترابایت"];

        return (round($size / pow(1024, ($i = floor(log($size, 1024)))), 2));
    }

    /**
     * @param string $content
     * @param bool $editMode
     * @return mixed|string|void
     */
    public static function ContentModeSwitcher(string $content, bool $editMode = true)
    {
        return $editMode ? $content : apply_filters('the_content', $content);
    }

}