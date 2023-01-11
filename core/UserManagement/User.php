<?php


namespace UserManagement;

use BaseModel\Status;
use BaseModel\Step;
use BaseModel\Type;
use SmartDate\SmartDate;
use TaskManagement\Task;
use WP\WPAttachment;
use WP\WPClass;
use WP_User_Query;

class User extends WPClass
{
    private int $ID;
    private     $userInfo;

    /**
     * User constructor.
     * @param int $ID
     */
    private function __construct (int $ID)
    {

        $this->ID = $ID;

        /*
         * (WP_User|false) WP_User object on success, false on failure.
         */
        $this->userInfo = get_userdata($this->ID);
    }

    // region Base Functions::

    /**
     * @return string[]
     */
    protected function getSortablePropertiesList (): array
    {
        return [
            'ID' => $this->getID(),
        ];
    }

    /**
     * @param string $key
     * @param string $metaValueType
     * @return array|int|string
     */
    protected function getUserMeta (string $key, string $metaValueType = 'string')
    {
        $metaValue = get_user_meta($this->getID(), $key, true);

        if ($metaValueType == 'int')
            return !$metaValue ? 0 : $metaValue;
        elseif ($metaValueType == 'bool')
            return $metaValue;
        else
            return !$metaValue ? '' : $metaValue;

    }

    /**
     * @param string $key
     * @param        $value
     * @return bool
     */
    protected function updateUser (string $key, $value): bool
    {
        $userData = wp_update_user(['ID' => $this->getID(), $key => $value]);
        return !is_wp_error($userData);
    }

    /**
     * @param string $key
     * @param        $value
     * @return bool
     */
    protected function updateUserMeta (string $key, $value): bool
    {
        if ($value == NULL)
            return false;
        update_user_meta($this->getID(), $key, $value);
        return true;
    }


    //endregion

    // region Class Auto Generated Getter::

    /**
     * @return int
     */
    public function getID (): int
    {
        return $this->ID;
    }

    /**
     * @return SmartDate
     */
    public function getRegisteredDate (): SmartDate
    {
        return new SmartDate($this->userInfo->user_registered, 'string');
    }

    /**
     * @return string | false
     */
    public function getFullName (): string
    {
        return !$this->userInfo ? false : $this->getFirstName() . ' ' . $this->getLastName();
    }

    //endregion


    // region Class Main Getter::

    /**
     * @return string | false
     */
    public function getFirstName (): string
    {

        return !$this->userInfo ? false : $this->userInfo->first_name;
    }

    /**
     * @return string | false
     */
    public function getLastName (): string
    {
        return !$this->userInfo ? false : $this->userInfo->last_name;
    }

    /**
     * @return string | false
     */
    public function getUsername (): string
    {
        return !$this->userInfo ? false : $this->userInfo->user_login;
    }

    /**
     * @return string
     */
    public function getDescription (): string
    {
        /*
         * (string) The author's field from the current author's DB object, otherwise an empty string.
         */
        return get_the_author_meta('description', $this->getID());
    }

    /**
     * @return string | false
     */
    public function getEmail (): string
    {

        return !$this->userInfo ? false : $this->userInfo->user_email;
    }


    //endregion

    // region Class Main Updater::

    /**
     * @param string $firstName
     * @return bool
     */
    public function updateFirstName (string $firstName): bool
    {
        return $this->updateUser('first_name', $firstName);
    }

    /**
     * @param string $lastName
     * @return bool
     */
    public function updateLastName (string $lastName): bool
    {
        return $this->updateUser('last_name', $lastName);
    }

    /**
     * @param string $username
     * @return bool
     */
    public function updateUsername (string $username): bool
    {
        return $this->updateUser('user_login', $username);
    }

    /**
     * @param string $description
     * @return bool
     */
    public function updateDescription (string $description): bool
    {
        return $this->updateUser('description', $description);
    }

    /**
     * @param string $email
     * @return bool
     */
    public function updateEmail (string $email): bool
    {
        return $this->updateUser('email', $email);
    }

    //endregion


    // region Class Personal Information Getter::

    /**
     * @return WPAttachment|false
     */
    public function getAvatar ()
    {
        $metaValue = $this->getUserMeta('avatar', 'int');
        return $metaValue == 0 ? false : WPAttachment::Create($metaValue);
    }

    /**
     * @return SmartDate
     */
    public function getBirthday (): SmartDate
    {
        $metaValue = $this->getUserMeta('birthday');
        return new SmartDate($metaValue, 'string');
    }

    /**
     * @return string
     */
    public function getNationalCode (): string
    {
        return $this->getUserMeta('national_code');
    }

    /**
     * @param string $mode :: global => +98 | national => 0 | free => without prefix
     * @return false|string
     */
    public function getMobileNumber (string $mode = 'global')
    {
        $metaValue = $this->getUserMeta('mobile');
        if ($metaValue != '') {
            if ($mode == 'global')
                return '+98' . $metaValue;
            elseif ($mode == 'national')
                return '0' . $metaValue;
        }
        return $metaValue;
    }

    /**
     * @return string
     */
    public function getPhoneNumber (): string
    {
        return $this->getUserMeta('home_phone_number');
    }

    /**
     * @return Type
     */
    public function getGender (): Type
    {
        global $genders;
        $metaValue = $this->getUserMeta('gender_id', 'int');
        return $metaValue == 0 ? $genders[1] : $genders[$metaValue];

    }


    /**
     * @return string
     */
    public function getAddress (): string
    {
        return $this->getUserMeta('address');
    }

    /**
     * @return WPAttachment[]
     */
    public function getAttachments (): array
    {
        $attachmentsIDArray = $this->getUserMeta('attachments', 'array');
        $attachments        = [];
        foreach ($attachmentsIDArray as $attachmentID) {
            $attachments[] = WPAttachment::Create($attachmentID);
        }
        return $attachments;
    }

    //endregion

    // region Class Personal Information Updater::

    /**
     * @param int $avatarID
     * @return bool
     */
    public function updateAvatar (int $avatarID): bool
    {
        return $this->updateUserMeta('avatar', $avatarID);
    }

    /**
     * @param string $birthday :: input must gregorian
     * @return bool
     */
    public function updateBirthday (string $birthday): bool
    {
        $birthdayDate = new SmartDate($birthday, 'string', 'jalali');

        return $this->updateUserMeta('birthday', $birthdayDate->getGregorianDateString());
    }

    /**
     * @param string $nationalCode
     * @return bool
     */
    public function updateNationalCode (string $nationalCode): bool
    {
        return $this->updateUserMeta('national_code', $nationalCode);

    }

    /**
     * @param string $mobile
     * @return bool
     */
    public function updateMobileNumber (string $mobile): bool
    {
        return $this->updateUserMeta('mobile', $mobile);
    }

    /**
     * @param string $genderID
     * @return bool
     */
    public function updateGender (string $genderID): bool
    {
        return $this->updateUserMeta('gender_id', $genderID);
    }

    /**
     * @param string $phoneNumber
     * @return bool
     */
    public function updatePhoneNumber (string $phoneNumber): bool
    {
        return $this->updateUserMeta('home_phone_number', $phoneNumber);

    }

    public function updateAddress (string $address): bool
    {
        return $this->updateUserMeta('address', $address);

    }

    /**
     * @param $attachments
     * @return bool
     */
    public function updateAttachments ($attachments): bool
    {
        return $this->updateUserMeta('attachments', $attachments);
    }
    //endregion


    // region Class System Information Getter::
    /**
     * @return Status
     */
    public function getStatus (): Status
    {
        global $userStatus;

        $metaValue = $this->getUserMeta('status_id', 'int');
        return $metaValue == 0 ? $userStatus[2] : $userStatus[$metaValue];
    }

    /**
     * @return Role[]
     */
    public function getRoles (): array
    {
        return Role::GetUserRoles($this->getID());
    }

    /**
     * @param string $by
     * @return array of strings :: ['editor','administrator']
     */
    public function getRolesListBy (string $by = 'name'): array
    {
        $rolesList = [];
        foreach ($this->getRoles() as $role) {
            if ($by == 'name') {
                $rolesList[] = strtolower($role->getName());
            }
            elseif ($by == 'slug') {
                $rolesList[] = $role->getSlug();
            }
        }
        return $rolesList;
    }

    /**
     * @return false | string
     */
    public function getTelegramChatID ()
    {
        return $this->getUserMeta('telegram_chat_id', 'string');
    }
    //endregion

    // region Class System Information Updater::
    /**
     * @param int $statusID
     * @return bool
     */
    public function updateStatus (int $statusID): bool
    {
        return $this->updateUserMeta('status_id', $statusID);
    }

    /**
     * @param string[] $roles :: ['administrator']
     * @return bool
     */
    public function updateRoles (array $roles): bool
    {
        $array = [];
        foreach ($roles as $role) {
            $array[$role] = true;
        }
        return $this->updateUserMeta('wp_capabilities', $array);
    }

    /**
     * @param int $telegramChatID
     * @return bool
     */
    public function updateTelegramChatID (int $telegramChatID): bool
    {
        return $this->updateUserMeta('telegram_chat_id', $telegramChatID);

    }


    //endregion


    // region Class Staff Getter::

    /**
     * @return false|User
     */
    public function getDirectManager ()
    {
        $metaValue = $this->getUserMeta('direct_manager_id', 'int');
        return $metaValue == 0 ? false : User::Create($metaValue);
    }

    /**
     * @return SmartDate
     */
    public function getContractAwardDate (): SmartDate
    {
        $metaValue = $this->getUserMeta('contract_award_date');
        return new SmartDate($metaValue, 'string');
    }

    /**
     * @return Type
     */
    public function getContractType (): Type
    {
        global $staffContractTypes;
        $metaValue = $this->getUserMeta('contract_type_id', 'int');
        return $metaValue == 0 ? $staffContractTypes[1] : $staffContractTypes[$metaValue];
    }

    /**
     * @return Type
     */
    public function getDegree (): Type
    {
        global $degrees;
        $metaValue = $this->getUserMeta('degree_id', 'int');
        return $metaValue == 0 ? $degrees[1] : $degrees[$metaValue];

    }

    /**
     * @return string
     */
    public function getMajor (): string
    {
        return $this->getUserMeta('major');
    }


    //endregion

    // region Class Staff Updater::

    /**
     * @param string $password
     * @return void
     */
    public function updatePassword (string $password): void
    {
        global $wpdb;

        $hash = wp_hash_password($password);
        $wpdb->update(
            $wpdb->users,
            [
                'user_pass'           => $hash,
                'user_activation_key' => '',
            ],
            ['ID' => $this->getID()]
        );
        wp_clear_auth_cookie();
        wp_set_current_user($this->getID());
        wp_set_auth_cookie($this->getID());
    }

    /**
     * @param int $managerID
     * @return bool
     */
    public function updateDirectManager (int $managerID): bool
    {
        return $this->updateUserMeta('direct_manager_id', $managerID);
    }

    /**
     * @param string $date
     * @return bool
     */
    public function updateContractAwardDate (string $date): bool
    {
        return $this->updateUserMeta('contract_award_date', $date);
    }

    /**
     * @param int $typeID
     * @return bool
     */
    public function updateContractType (int $typeID): bool
    {
        return $this->updateUserMeta('contract_type_id', $typeID);
    }

    /**
     * @param int $degreeID
     * @return bool
     */
    public function updateDegree (int $degreeID): bool
    {
        return $this->updateUserMeta('degree_id', $degreeID);

    }

    /**
     * @param string $major
     * @return bool
     */
    public function updateMajor (string $major): bool
    {
        return $this->updateUserMeta('major', $major);
    }
    //endregion


    // region Class Access Functions::

    /**
     * @param Step $step
     * @return bool بررسی میکند که آیا کاربر با توجه به نقش‌هایی که دارد در لیست دسترسی‌های این مرحله است یا نه
     */
    public function hasAccessToStep (Step $step): bool
    {
        $stepRoles = $step->getRolesAccessStep();
        foreach ($this->getRoles() as $userRole) {
            foreach ($stepRoles as $stepRole) {
                if ($userRole->getSlug() == $stepRole->getSlug()) {
                    return true;
                }
            }
        }
        return false;
    }

    public function hasAuthorizedTo (Task $task): bool
    {
        if ($this->hasAccessToStep($task->getStep())) {
            $userAssigned = $task->getAssignedUser();
            if ($userAssigned) {
                if ($userAssigned->isMe()) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param Task   $task
     * @param string $action
     * 'edit' => برای دسترسی به ادیتور
     * 'finish' => قابلیت پایان دادن به تسک
     * 'start' => قابلیت شروع کردن تسک
     * 'revert' => قابلیت برگشت تسک به سوپروایزر جهت اساین به فرد دیگر
     * 'pause' => قابلیت معلق کردن تسک
     * 'resume' => قابلیت شروع دوباره تسک
     * @return bool
     */
    public function hasAccessToDo (Task $task, string $action = 'edit'): bool
    {
        if ($action == 'edit' || $action == 'finish' || $action == 'pause') {
            if ($task->getStepStatus()->getID() == 2)
                return true;
        }
        elseif ($action == 'start') {
            if ($task->getStepStatus()->getID() == 1)
                return true;
        }
        elseif ($action == 'revert') {
            if ($task->getStepStatus()->getID() != 3)
                return true;
        }
        elseif ($action == 'resume') {
            if ($task->getStepStatus()->getID() == 4)
                return true;
        }

        return false;
    }

    /**
     * @param string $capability
     * @return bool Whether the user has the given capability.
     */
    public function can (string $capability): bool
    {

        return user_can($this->getID(), $capability);

    }

    /**
     * این تابع آبجکت یوزر ساخته شده رو با کاربر فعیلی بررسی میکند
     * @return bool
     */
    public function isMe (): bool
    {
        if ($this->getID() == self::GetCurrentUser()->getID())
            return true;
        else
            return false;
    }

    /**
     * @return bool
     */
    public function isClient (): bool
    {
        return $this->can('client');
    }

    /**
     * @param $password
     * @return bool
     */
    public function checkPassword ($password): bool
    {
        return wp_check_password($password, $this->userInfo->user_pass, $this->getID());
    }

    // endregion

    // region Class Static Functions::

    public static function CheckMobileNumber (string $mobileNumber)
    {
        return self::GetBy('mobile', $mobileNumber, true);
    }

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $mobile
     * @return false|User
     */
    public static function Insert (string $firstName, string $lastName, string $mobile)
    {

        $insertedUser = self::GetBy('mobile', $mobile, true);
        if ($insertedUser)//یعنی اگه کاربر قبلا ثبت شده از همینجا برگرد و کاربر جدید رو ثبت نکن
            return false;


        $data = [
            'user_login' => 'user_' . rand(1000, 1000000),
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'meta_input' => [
                'mobile'    => $mobile,
                'status_id' => 3,
            ],
        ];

        $userID = wp_insert_user($data);
        return (!is_wp_error($userID)) ? User::Create($userID) : false;
    }

    /**
     * @param int $ID
     * @return false|User
     */
    public static function Create (int $ID)
    {
        if ($ID > 0)
            return new self($ID);
        else
            return false;
    }

    /**
     * Current User
     * @return User
     */
    public static function GetCurrentUser (): User
    {
        return User::Create(wp_get_current_user()->ID);

    }

    /**
     * @param $metaKey
     * @param $metaValue
     * @return false|User
     */
    private static function GetByMetaData ($metaKey, $metaValue)
    {

        $user_query = new WP_User_Query(
            [
                'meta_key'   => $metaKey,
                'meta_value' => $metaValue,
            ]
        );
        $users      = $user_query->get_results();
        return (count($users) > 0) ? User::Create($users[0]->ID) : false;
    }

    /**
     * User constructor by any fields.
     * @param string $field
     * @param string $value
     * @param bool   $isMeta
     * @return User | false
     */
    public static function GetBy (string $field, string $value, bool $isMeta = false)
    {
        if ($isMeta) {
            return self::GetByMetaData($field, $value);
        }
        else {
            $selectedUser = get_user_by($field, $value);
            return !$selectedUser ? false : User::Create($selectedUser->ID);
        }
    }

    /**
     * User constructor by any fields.
     * @param Role[] $roles
     * @return User[]
     */
    public static function GetAllUsersHaveThisRoles (array $roles): array
    {
        $rolesSlugArray = [];
        foreach ($roles as $role) {
            $rolesSlugArray[] = $role->getSlug();
        }

        return self::ConvertUsersList(get_users(['role__in' => $rolesSlugArray]));
    }

    /**
     * @param string[] $rolesSlug
     * @return User[]
     */
    public static function GetAllUsersByRolesSlug (array $rolesSlug): array
    {
        return self::ConvertUsersList(get_users(['role__in' => $rolesSlug]));

    }

    /**
     * @return User[]
     */
    public static function GetAllUsers (): array
    {
        $users = get_users();
        return self::ConvertUsersList($users);

    }

    /**
     * @param $originalUsersArray
     * @return User[]
     */
    public static function ConvertUsersList ($originalUsersArray): array
    {
        $list = [];
        foreach ($originalUsersArray as $user) {
            $list[] = User::Create($user->ID);
        }
        return $list;
    }


    // endregion

}