<?php


namespace WP;

use BaseModel\Status;
use BaseModel\Type;
use SmartDate\SmartDate;
use UserManagement\User;

class WPComment
{


    private int $ID;
    private     $commentInfo;

    /**
     * Comment constructor.
     * @param int $ID
     */
    private function __construct(int $ID, $commentInfo)
    {
        $this->ID = $ID;
        $this->commentInfo = $commentInfo;
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
     * @return int
     */
    public function getPostID(): int
    {
        return $this->commentInfo->comment_post_ID;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->commentInfo->comment_content;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        global $commentStatus;
        $statusID = $this->commentInfo->comment_approved;
        if ($statusID == 1)
            $statusID = 1;
        elseif ($statusID == 'trash')
            $statusID = 4;
        elseif ($statusID == 'spam')
            $statusID = 3;
        else
            $statusID = 2;
        return $commentStatus[$statusID];
    }

    /**
     * @return Type|false
     */
    public function getType()
    {
        global $commentTypes;
        /* @var Type $type */
        foreach ($commentTypes as $type) {
            if ($type->getSlug() == $this->commentInfo->comment_type)
                return $type;
        }
        return false;
    }

    public function getUser()
    {
        $userID = $this->commentInfo->user_id;
        if (!$userID)
            return false;
        return User::Create($userID);
    }

    /**
     * @return SmartDate
     */
    public function getDate(): SmartDate
    {
        return new SmartDate($this->commentInfo->comment_date, 'string');
    }

    /**
     * @return WPComment|false
     */
    public function getParent()
    {
        $commentParentID = $this->commentInfo->comment_parent;
        return self::Create($commentParentID);
    }

    /**
     * @return WPAttachment[]
     */
    public function getAttachments(): array
    {
        $attachments = [];
        $serializedAttachmentsID = get_comment_meta($this->getID(), 'attachments', true);
        if ($serializedAttachmentsID) {
            $attachmentsIDArray = unserialize($serializedAttachmentsID);
            foreach ($attachmentsIDArray as $attachmentID) {
                $attachments[] = WPAttachment::Create($attachmentID);
            }
        }
        return $attachments;
    }

    /**
     * @return false|User
     */
    public function getMentionedUser()
    {
        $userID = intval(get_comment_meta($this->getID(), "mentioned_user_id", true));
        if (!$userID || $userID == 0)
            return false;
        return User::Create($userID);
    }


    //endregion

    // region Class Update Functions::

    /**
     * @param string $userID
     * @return bool
     */
    public function updateMentionedUser(string $userID): bool
    {
        return $this->updateCommentMeta('mentioned_user_id', $userID);
    }

    /**
     * @param int[] $attachments
     * @return bool
     */
    public function updateAttachments(array $attachments): bool
    {
        return $this->updateCommentMeta('attachments', serialize($attachments));

    }

    /**
     * @param string $key
     * @param string $value
     * @return true
     * key not exist            => insert       => must return true
     * key exist but value new  => update value => must return true
     * key exist but value same => return false => we return true
     */
    private function updateCommentMeta(string $key, string $value): bool
    {
        update_comment_meta($this->getID(), $key, $value);
        return true;
    }
    //endregion

    // region Class Static Functions::

    /**
     * @param int $postID
     * @param string $commentContent
     * @param User $user
     * @param int $commentTypeID
     * @param int $commentParentID
     * @param int $commentStatusID
     * @return WPComment|false
     */
    private static function Insert(int $postID, string $commentContent, User $user, int $commentTypeID = 1, int $commentParentID = 0, int $commentStatusID = 1)
    {
        $data = [
            'comment_post_ID' => $postID,
            'comment_author' => $user->getFullName(),
            'comment_content' => $commentContent,
            'comment_type' => $commentTypeID,
            'user_id' => $user->getID(),
            'comment_date' => current_time('mysql'),
            'comment_parent' => $commentParentID,
            'comment_approved' => $commentStatusID,
        ];
        $newCommentID = wp_insert_comment($data);

        return self::Create($newCommentID);
    }

    /**
     * @param string $message
     * @param int $taskID
     * @param int $mentionedUserID
     * @param array $attachments
     * @return WPComment|false
     */
    public static function SendNewComment(string $message, int $taskID, int $mentionedUserID = 0, array $attachments = [])
    {
        $user = User::GetCurrentUser();
        $comment = self::Insert($taskID, $message, $user);
        if ($comment) {
            if ($mentionedUserID > 0)
                $comment->updateMentionedUser($mentionedUserID);
            if (count($attachments) > 0)
                $comment->updateAttachments($attachments);

            return $comment;
        }
        return false;
    }

    /**
     * @param string $message
     * @param int $taskID
     * @param int $mentionedUserID
     * @param array $attachments
     * @param $parentCommentID
     * @return false|WPComment
     */
    public static function ReplyComment(string $message, int $taskID, int $mentionedUserID = 0, array $attachments = [], $parentCommentID = 0)
    {
        $user = User::GetCurrentUser();
        $comment = self::Insert($taskID, $message, $user, 1, $parentCommentID);
        if ($comment) {
            if ($mentionedUserID > 0)
                $comment->updateMentionedUser($mentionedUserID);
            if (count($attachments) > 0)
                $comment->updateAttachments($attachments);
            return $comment;
        }
        return false;
    }

    /**
     * @param $originalCommentsArray
     * @return array
     */
    private static function ConvertCommentsList($originalCommentsArray): array
    {
        $list = [];
        foreach ($originalCommentsArray as $comment) {
            $list[] = self::Create($comment->comment_ID);
        }
        return $list;
    }

    /**
     * @param int $postID
     * @param array|null $metaQuery
     * @param int|null $parentID
     * @param array|null $commentTypes
     * @param int $count
     * @param SmartDate|null $from
     * @param SmartDate|null $to
     * @param string $orderBy
     * @param string $order
     * @return WPComment[]
     */
    public static function GetCommentsList(int $postID = 0, array $metaQuery = null, int $parentID = null, array $commentTypes = null, int $count = -1, SmartDate $from = null, SmartDate $to = null, string $orderBy = 'date', string $order = 'DESC'): array
    {

        $args['post_id'] = $postID;
        $args['meta_query'] = ($metaQuery == null) ? '' : $metaQuery;
        $args['parent'] = ($parentID != null && $parentID > 0) ? $parentID : '';
        $args['type'] = ($commentTypes == null) ? '' : $commentTypes;
        $args['number'] = ($count < 1) ? '' : $count;
        $args['orderby'] = $orderBy;
        $args['order'] = $order;

        $dateQuery['after'] = ($from != null) ? $from->getGregorianDateString() . ' 00:00:00' : '';
        $dateQuery['before'] = ($to != null) ? $to->getGregorianDateString() . ' 23:59:00' : '';
        $dateQuery['inclusive'] = true;

        $args['date_query'] = ($from == null && $to == null) ? '' : $dateQuery;

        //        $args=array(
        //            'post_id'=>$postID
        //        );
        $commentsArray = get_comments($args);

        return self::ConvertCommentsList($commentsArray);

    }

    /**
     * @param int $ID
     * @return WPComment|false
     */
    public static function Create(int $ID): WPComment
    {
        $commentInfo = get_comment($ID);
        if ($commentInfo == null)
            return false;
        else
            return new self($ID, $commentInfo);
    }
    //endregion


}